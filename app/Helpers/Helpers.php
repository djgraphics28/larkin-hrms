<?php

namespace App\Helpers;

use App\Models\TaxTable;
use DateTime;
use Carbon\Carbon;
use App\Models\Asset;
use App\Models\Holiday;
use App\Models\Nasfund;
use App\Models\Payroll;
use App\Models\Payslip;
use App\Models\Business;
use App\Models\Employee;
use App\Models\Fortnight;
use App\Models\Attendance;
use App\Models\BusinessUser;
use App\Models\EmployeeHours;
use App\Models\SalaryHistory;
use Illuminate\Support\Facades\Auth;

class Helpers
{
    public static function getActiveBusinessId()
    {
        $businessUser = BusinessUser::where('user_id', Auth::user()->id)->where('is_active', true)->first();
        return $businessUser->business_id;
    }

    public static function generateEmployeeNumber($businessId)
    {
        $code = Business::find($businessId)->code;
        $lastEmployee = Employee::where('business_id', $businessId)->orderBy('employee_number', 'desc')->first();

        if ($lastEmployee) {
            $existingNumber = explode('-', $lastEmployee->employee_number)[1];
            $employeeNumber = (int) $existingNumber + 1;
        } else {
            $employeeNumber = 1; // Start from 1 for new employee
        }

        // Calculate the number of leading zeros required based on the desired format (e.g., 4 digits)
        $desiredLength = 4;
        $leadingZeros = str_repeat('0', $desiredLength - strlen($employeeNumber));

        // Concatenate the leading zeros with the employee number
        $paddedEmployeeNumber = $leadingZeros . $employeeNumber;

        return $code . '-' . $paddedEmployeeNumber;
    }

    public static function generateAssetCode($businessId)
    {
        $code = Business::find($businessId)->code;
        $lastAsset = Asset::where('business_id', $businessId)->orderBy('asset_code', 'desc')->first();

        if ($lastAsset) {
            $existingNumber = explode('-', $lastAsset->asset_code)[2]; // Change index to 2
            $assetCode = (int) $existingNumber + 1;
        } else {
            $assetCode = 1; // Start from 1 for new asset for specific business
        }

        // Calculate the number of leading zeros required based on the desired format (e.g., 7 digits)
        $desiredLength = 7;
        $leadingZeros = str_repeat('0', $desiredLength - strlen($assetCode));

        // Concatenate the leading zeros with the asset number
        $paddedAssetCode = $leadingZeros . $assetCode;

        return $code . '-A-' . $paddedAssetCode; // 'A' stands for asset
    }

    /**
     * Formats a date to a desired format.
     *
     * @param string $date The date to format.
     * @param string $format The desired output format (e.g., "Y-m-d", "d/m/Y").
     * @return string The formatted date.
     */
    public static function formatDate($date, $format = 'Y-m-d')
    {
        return Carbon::parse($date)->format($format);
    }

    /**
     * Generates a random string of a specified length.
     *
     * @param int $length The desired length of the random string.
     * @return string The generated random string.
     */
    public static function generateRandomString($length = 16)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, strlen($characters) - 1)];
        }
        return $randomString;
    }

    public static function computeHours($fn_id, $businessId, $module, $payrun_id, $employee_ids)
    {

        $getDates = Fortnight::where('id', $fn_id)->first();

        $start = $getDates->start;
        $end = $getDates->end;

        $endDate = new DateTime($end);

        $endDateAttendance = new DateTime($end);
        $endDateAttendance->modify('+1 day');

        $end = $endDate->format('Y-m-d');

        // $businessId = BusinessUser::where('user_id', Auth::user()->id)->where('is_active', true)->first()->business_id;

        $getEmployee = Employee::where('business_id', $businessId)
            ->whereIn('id', $employee_ids)
            ->whereHas('attendances', function ($query) use ($fn_id) {
                $query->where('fortnight_id', $fn_id);
            })
            ->get();

        foreach ($getEmployee as $employee) {
            //check existing record
            $check_salary = EmployeeHours::where('employee_id', $employee->id)
                ->where('fortnight_id', $getDates->id)->first();

            $current_salary = SalaryHistory::where('is_active', 1)
                ->where('employee_id', $employee->id)->first();

            if ($check_salary) {
                $rate_id = $check_salary->salary_id;
            } else {
                $rate_id = $current_salary?->id;
            }

            $getHours = Attendance::selectRaw('date, time_in, time_out, time_in_2, time_out_2, is_break, late_in_minutes, DAYNAME(date) as day_name')
                ->where('employee_number', $employee->employee_number)
                ->whereBetween('date', [$start, $endDateAttendance])
                ->get();

            $total_hours = 0;
            $sunday_total_hours = 0;
            $regular_hours = 0;
            $ot_hours = 0;
            $holiday_hours = 0;
            $holiday_work = [];



            foreach ($getHours as $hours) {

                $computed_hour = Helpers::compute_daily_hr($hours->date, $hours->time_in, $hours->time_out, $hours->time_in_2, $hours->time_out_2, $hours->is_break);


                $checkHoliday = Holiday::where('holiday_date', $hours->date)->first();

                if ($checkHoliday) {
                    $holiday_hours = $holiday_hours + $computed_hour;
                    $holiday_work[$hours->date] = $computed_hour;
                    continue;
                }

                if ($hours->day_name == 'Sunday') {
                    $sunday_total_hours = $sunday_total_hours + ($computed_hour);
                }

                $total_hours = $total_hours + $computed_hour;
            }

            $getHoliday = Holiday::whereBetween('holiday_date', [$start, $end])->get();

            if ($getHoliday) {
                foreach ($getHoliday as $holiday) {
                    if (array_key_exists($holiday->holiday_date, $holiday_work)) {
                        foreach ($holiday_work as $date => $hours_worked) {
                            if ($date === $holiday->holiday_date) {
                                $total_hours = $total_hours + $hours_worked;
                            }
                        }
                    } else {
                        $total_hours = $total_hours + 8;
                    }
                }
            }


            if ($employee->workshift->number_of_hours_fn <= $total_hours) {
                $ot_hours = ($total_hours - $employee->workshift->number_of_hours_fn);
                $regular_hours = $employee->workshift->number_of_hours_fn;
            } else {
                $regular_hours = $total_hours;
            }

            if ($rate_id) {
                EmployeeHours::updateOrCreate(
                    [
                        'employee_id' => $employee->id,
                        'fortnight_id' => $getDates->id,
                        'salary_id' => $rate_id,
                    ],
                    [
                        'regular_hr' => $regular_hours,
                        'overtime_hr' => $ot_hours,
                        'sunday_ot_hr' => $sunday_total_hours,
                        'holiday_ot_hr' => $holiday_hours
                    ]
                );
            }
        }

        if ($module === 'payrun') {
            Helpers::computePay($payrun_id, $fn_id, $employee_ids);
        }
    }

    public static function computePay($payrun_id, $fn_id, $employee_ids)
    {
        $fn_number = Helpers::fn_number();
        $get_hours = EmployeeHours::where('fortnight_id', $fn_id)
            ->whereIn('employee_id', $employee_ids)->get();

        foreach ($get_hours as $hours) {
            $employee_info = Employee::where('id', $hours->employee_id)->first();
            $total_work_hours_fn = $employee_info->workshift->number_of_hours_fn;

            $get_rate = SalaryHistory::where('employee_id', $hours->employee_id)
                ->where('id', $hours->salary_id)->first();

            $fn_rate = $get_rate->salary_rate * $total_work_hours_fn;
            $regular = ($fn_rate * $hours->regular_hr) / $total_work_hours_fn;
            $overtime = ($hours->overtime_hr * $get_rate->salary_rate) * 1.5;
            $sunday_ot = $hours->sunday_ot_hr * $get_rate->salary_rate;
            $holiday_ot = $hours->holiday_ot_hr * $get_rate->salary_rate;
            $plp_alp_fp = 0;
            $other = 0;
            $gross = $regular + $overtime + $sunday_ot + $holiday_ot + $plp_alp_fp + $other;
            $fn_tax = ($gross < 769.27) ? 0 : (((($gross * $fn_number) * 0.3) - 6000) / $fn_number);

            $npf = 0;
            if ($employee_info->collect_nasfund === 1 && $employee_info->nasfund_number !== null) {
                $npf = $regular * 0.06;
            }

            Payslip::updateOrCreate(
                [
                    'employee_id' => $hours->employee_id,
                    'fortnight_id' => $fn_id,
                ],
                [

                    'payrun_id' => $payrun_id,

                    'regular' => $regular,
                    'overtime' => $overtime,
                    'sunday_ot' => $sunday_ot,
                    'holiday_ot' => $holiday_ot,
                    'plp_alp_fp' => $plp_alp_fp,
                    'other' => $other,

                    'fn_tax' => $fn_tax,
                    'npf' => $npf,
                    'ncsl' => 0,
                    'cash_adv' => 0
                ]
            );
        }
    }

    public static function explodeFullName($fullName)
    {
        $parts = explode(' ', $fullName); // Split the full name by spaces

        $firstName = array_shift($parts); // Take the first part as the first name

        $lastName = '';

        // Check if there are remaining parts for the last name
        if (!empty($parts)) {
            // If there are remaining parts, take the last part as the last name
            $lastName = array_pop($parts);

            // If there are still parts remaining, they belong to the first name
            if (!empty($parts)) {
                $firstName .= ' ' . implode(' ', $parts);
            }
        }

        return [
            'first_name' => $firstName,
            'last_name' => $lastName,
        ];
    }

    public static function designationToId($designation)
    {
        switch ($designation) {
            case 'Admin Clerk':
                return 1;
                break;
            case 'Bricklayer':
                return 2;
                break;
            case 'Driver':
                return 3;
                break;
            case 'Electrician':
                return 4;
                break;
            case 'General Labourer':
                return 5;
                break;
            case 'Helper':
                return 6;
                break;
            case 'Painter':
                return 7;
                break;
            case 'Plasterer':
                return 8;
                break;
            case 'Plumber Helper':
                return 9;
                break;
            case 'Tiler':
                return 10;
                break;
            case 'Welder':
                return 11;
                break;
            case 'Cook':
                return 12;
                break;
            case 'Accounts Clerk':
                return 13;
                break;
            case 'Aluminium Fitter':
                return 14;
                break;
            case 'Cleaner':
                return 15;
                break;
            case 'Financial Controller':
                return 16;
                break;
            case 'G&A Fitter':
                return 17;
                break;
            case 'Glass Cutter':
                return 18;
                break;
            case 'Glass Fitter':
                return 19;
                break;
            case 'Joiner':
                return 20;
                break;
            case 'Joinery':
                return 21;
                break;
            case 'Lead Carpenter':
                return 22;
                break;
            case 'Leadman':
                return 23;
                break;
            case 'Logistic Officer':
                return 24;
                break;
            case 'Mason':
                return 25;
                break;
            case 'Mechanic':
                return 26;
                break;
            case 'Office Clerk':
                return 27;
                break;
            case 'Foreman':
                return 28;
                break;
            case 'Plumber':
                return 29;
                break;
            case 'Security Guard':
                return 30;
                break;
            case 'Stone Installer':
                return 31;
                break;
            case 'Storeman':
                return 32;
                break;
            case 'Trade Supervisor':
                return 33;
                break;
            case 'Trade Supervisor (Finishing)':
                return 34;
                break;
            case 'Trade Supervisor-Welding':
                return 35;
                break;
            case 'Trade Supervisor/Coordinator (Carpentry)':
                return 36;
                break;
            case 'Trade Supervisor/Coordinator (Electrical)':
                return 37;
                break;
            case 'Trade Supervisor/Coordinator (Finishing)':
                return 38;
                break;
            case 'Carpenter':
                return 39;
                break;
            case 'Admin Director':
                return 40;
                break;
            case 'Fabricator':
                return 41;
                break;
            case 'Graphic Artist':
                return 42;
                break;
            case 'Managing Director':
                return 43;
                break;
            case 'Marketing':
                return 44;
                break;
            case 'Operations Manager':
                return 45;
                break;
            case 'Production Supervisor':
                return 46;
                break;
            case 'Production Staff':
                return 47;
                break;
            case 'Works Manager':
                return 48;
                break;
            case 'Sales & Marketing Manager':
                return 49;
                break;
            case 'Security Manager':
                return 50;
                break;
            case 'General Manager':
                return 51;
                break;
            case 'Reserved Guard':
                return 52;
                break;
            case 'Supervisor':
                return 53;
                break;
            case 'Kitchen Staff':
                return 54;
                break;
            case 'Chef':
                return 55;
                break;
            case 'Assistant Chef':
                return 56;
                break;
            case 'Head Chef':
                return 57;
                break;
            case 'Wait Staff':
                return 58;
                break;
            case 'OJT':
                return 59;
                break;
            case 'Restaurant Manager':
                return 60;
                break;
            case 'Construction Manager':
                return 61;
                break;
            default:
                return 1; // Return 1 if designation is not found
        }
    }

    public static function employeeStatusToId($status)
    {
        switch ($status) {
            case 'Active':
                return 1;
                break;
            case 'Present':
                return 2;
                break;
            case 'AWOL':
                return 3;
                break;
            case 'Terminated':
                return 4;
                break;
            case 'Resigned':
                return 5;
                break;
            case 'Deported':
                return 6;
                break;
            case 'New Contract':
                return 7;
                break;
            case 'Redundant':
                return 8;
                break;
            case 'Stand Down':
                return 9;
                break;
            case 'Transferred':
                return 10;
                break;
            case 'Project Completion':
                return 11;
                break;
            case 'Suspended':
                return 12;
                break;
            case 'Did not pass trade test':
                return 13;
                break;
            default:
                return 1; // Return 1 if status is not found
        }
    }

    public static function computeNPF($selected_fn, $businessId)
    {
        $get_employees = Employee::where('business_id', $businessId)->get();

        foreach ($get_employees as $employee) {
            $get_pay = Payslip::where('employee_id', $employee->id)
                ->where('fortnight_id', $selected_fn)
                ->first();

            $er = 0;
            $ee = 0;

            if ($employee->collect_nasfund === 1 && $employee->nasfund_number !== null) {
                $er = $get_pay->regular * 0.084;
                $ee = $get_pay->regular * 0.06;
            }

            if ($get_pay) {
                Nasfund::updateOrCreate(
                    [
                        'employee_id' => $employee->id,
                        'fortnight_id' => $selected_fn
                    ],
                    [
                        'pay' => $get_pay->regular,
                        'ER' => $er,
                        'EE' => $ee
                    ]
                );
            }
        }
    }

    public static function fn_number()
    {
        $year = date('Y');
        $fn_number = Fortnight::where('year', $year)->count();
        return $fn_number;
    }

    public static function compute_daily_hr($date, $time_in, $time_out, $time_in_2, $time_out_2, $is_break)
    {
        $date = $date;
        $comp_time_out = new DateTime($date . ' ' . $time_out);
        $comp_time_in = new DateTime($date . ' ' . $time_in);
        $comp_time_out_2 = new DateTime($date . ' ' . $time_out_2);
        $comp_time_in_2 = new DateTime($date . ' ' . $time_in_2);

        $hour_diff_am = $comp_time_out->diff($comp_time_in);
        $hour_diff_pm = $comp_time_out_2->diff($comp_time_in_2);

        $hour_diff_total = $hour_diff_am->h + $hour_diff_pm->h;

        if (($time_out === '' || $time_in_2 === '' || $time_out === null || $time_in_2 === null) && $is_break === 1) {
            $hour_diff_total = $comp_time_out_2->diff($comp_time_in)->h - 1;
        } elseif (($time_out === '' || $time_in_2 === '' || $time_out === null || $time_in_2 === null) && $is_break === 0) {
            $hour_diff_total = $comp_time_out_2->diff($comp_time_in)->h;
        }

        return $hour_diff_total;
    }

    public static function getFortnightIdByDate($date)
    {
        $year = date('Y');
        $fortnights = Fortnight::where('year', $year)->get();

        foreach ($fortnights as $fortnight) {
            if ($date >= $fortnight->start && $date <= $fortnight->end) {
                return $fortnight->id;
            }
        }
    }

    public static function activeFortnights()
    {
        $year = date('Y');
        $fortnights = Fortnight::where('year', $year)->get();

        return $fortnights;
    }

    public static function payrollCodeGenerator($businessId, $fortnight)
    {
        $fornightCode = Fortnight::find($fortnight)->code;
        $code = Business::find($businessId)->code;
        $lastPayroll = Payroll::where('business_id', $businessId)->latest()->first();

        if ($lastPayroll) {
            $existingNumber = explode('-', $lastPayroll->payroll_code)[3]; // Corrected index for payroll code
            $payrollCode = (int) $existingNumber + 1;
        } else {
            $payrollCode = 1; // Start from 1 for new payroll
        }

        // Calculate the number of leading zeros required based on the desired format (e.g., 5 digits)
        $desiredLength = 5;
        $leadingZeros = str_repeat('0', $desiredLength - strlen($payrollCode));

        // Concatenate the leading zeros with the payroll number
        $paddedPayrollCode = $leadingZeros . $payrollCode;

        return 'P-' . $code . '-' . $fornightCode . '-' . $paddedPayrollCode;
    }

    public static function computeTax($gross)
    {
        $taxTable = TaxTable::with('tax_table_ranges')->where('is_active', 1)->first();
        $taxValue = 0;

        if ($taxTable) {
            foreach($taxTable->tax_table_ranges as $taxRange) {
                if(!is_null($taxRange->from) && !is_null($taxRange->to)) {
                    if($gross >= $taxRange->from && $gross <= $taxRange->to) {
                        $taxValue = $gross * ($taxRange->percentage / 100);
                    }
                } elseif(is_null($taxRange->to)) {
                    if($gross >= $taxRange->from) {
                        $taxValue = $gross * ($taxRange->percentage / 100);
                    }
                }
            }
        }
        return $taxValue;
    }

}
