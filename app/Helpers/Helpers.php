<?php

namespace App\Helpers;

use DateTime;
use DateInterval;
use Carbon\Carbon;
use App\Models\Asset;
use App\Models\Holiday;
use App\Models\Nasfund;
use App\Models\Payroll;
use App\Models\Payslip;
use App\Models\Business;
use App\Models\Employee;
use App\Models\TaxTable;
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
        $lastEmployee = Employee::withTrashed()->where('business_id', $businessId)->orderBy('employee_number', 'desc')->first();

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

    public static function computeHours($fnId, $businessId, $module, $payrunId, $employeeIds)
    {
        $getDates = Fortnight::where('id', $fnId)->first();

        $start = $getDates->start;
        $end = $getDates->end;

        $endDate = new DateTime($end);
        $endDateAttendance = new DateTime($end);
        $endDateAttendance->modify('+1 day');

        $end = $endDate->format('Y-m-d');

        $getEmployee = Employee::where('business_id', $businessId)
            ->whereIn('id', $employeeIds)
            ->whereHas('attendances', function ($query) use ($fnId) {
                $query->where('fortnight_id', $fnId);
            })
            ->get();

        foreach ($getEmployee as $employee) {
            $checkSalary = EmployeeHours::where('employee_id', $employee->id)
                ->where('fortnight_id', $getDates->id)->first();

            $currentSalary = SalaryHistory::where('is_active', 1)
                ->where('employee_id', $employee->id)->first();

            $rateId = $checkSalary ? $checkSalary->salary_id : ($currentSalary ? $currentSalary->id : null);

            $getHours = Attendance::selectRaw('date, time_in, time_out, time_in_2, time_out_2, is_break, late_in_minutes, DAYNAME(date) as day_name, on_leave')
                ->where('employee_number', $employee->employee_number)
                ->whereBetween('date', [$start, $endDateAttendance])
                ->get();

            $totalHours = 0;
            $sundayTotalHours = 0;
            $regularHours = 0;
            $otHours = 0;
            $holidayHours = 0;
            $holidayWork = [];

            foreach ($getHours as $hours) {
                $computedHour = Helpers::computeDailyHr($hours->date, $hours->time_in, $hours->time_out, $hours->time_in_2, $hours->time_out_2, $hours->is_break, $hours->on_leave);

                $checkHoliday = Holiday::where('holiday_date', $hours->date)->first();

                if ($checkHoliday) {
                    $holidayHours += $computedHour;
                    $holidayWork[$hours->date] = $computedHour;
                    continue;
                }

                if ($hours->day_name == 'Sunday') {
                    $sundayTotalHours += $computedHour;
                }

                $totalHours += $computedHour;
            }

            $getHoliday = Holiday::whereBetween('holiday_date', [$start, $end])->get();

            if ($getHoliday) {
                foreach ($getHoliday as $holiday) {
                    if (array_key_exists($holiday->holiday_date, $holidayWork)) {
                        foreach ($holidayWork as $date => $hoursWorked) {
                            if ($date === $holiday->holiday_date) {
                                $totalHours += $hoursWorked;
                            }
                        }
                    } else {
                        $totalHours += 8;
                    }
                }
            }

            if ($employee->workshift->number_of_hours_fn <= $totalHours) {
                $otHours = ($totalHours - $employee->workshift->number_of_hours_fn);
                $regularHours = $employee->workshift->number_of_hours_fn;
            } else {
                $regularHours = $totalHours;
            }

            if ($rateId) {
                EmployeeHours::updateOrCreate(
                    [
                        'employee_id' => $employee->id,
                        'fortnight_id' => $getDates->id,
                        'salary_id' => $rateId,
                    ],
                    [
                        'regular_hr' => $regularHours,
                        'overtime_hr' => $otHours,
                        'sunday_ot_hr' => $sundayTotalHours,
                        'holiday_ot_hr' => $holidayHours
                    ]
                );
            }
        }

        if ($module === 'payrun') {
            Helpers::computePay($payrunId, $fnId, $employeeIds);
        }
    }

    public static function computePay($payrunId, $fnId, $employeeIds)
    {
        $fnNumber = Helpers::fnNumber();
        $getHours = EmployeeHours::where('fortnight_id', $fnId)
            ->whereIn('employee_id', $employeeIds)->get();

        foreach ($getHours as $hours) {
            $employeeInfo = Employee::where('id', $hours->employee_id)->first();
            $totalWorkHoursFn = $employeeInfo->workshift->number_of_hours_fn;

            $getRate = SalaryHistory::where('employee_id', $hours->employee_id)
                ->where('id', $hours->salary_id)->first();

            $fnRate = $getRate->salary_rate * $totalWorkHoursFn;
            $regular = ($fnRate * $hours->regular_hr) / $totalWorkHoursFn;
            $overtime = ($hours->overtime_hr * $getRate->salary_rate) * 1.5;
            $sundayOt = $hours->sunday_ot_hr * $getRate->salary_rate;
            $holidayOt = $hours->holiday_ot_hr * $getRate->salary_rate;
            $plpAlpFp = 0;
            $other = 0;
            $gross = $regular + $overtime + $sundayOt + $holidayOt + $plpAlpFp + $other;
            $fnTax = ($gross < 769.27) ? 0 : (((($gross * $fnNumber) * 0.3) - 6000) / $fnNumber);

            $npf = 0;
            if ($employeeInfo->collect_nasfund === 1 && $employeeInfo->nasfund_number !== null) {
                $npf = $regular * 0.06;
            }

            Payslip::updateOrCreate(
                [
                    'employee_id' => $hours->employee_id,
                    'fortnight_id' => $fnId,
                ],
                [
                    'payrun_id' => $payrunId,
                    'regular' => $regular,
                    'overtime' => $overtime,
                    'sunday_ot' => $sundayOt,
                    'holiday_ot' => $holidayOt,
                    'plp_alp_fp' => $plpAlpFp,
                    'other' => $other,
                    'fn_tax' => $fnTax,
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

    // public static function departmentToId($department)

    public static function computeNpf($selectedFn, $businessId)
    {
        $getEmployees = Employee::where('business_id', $businessId)->get();

        foreach ($getEmployees as $employee) {
            $getPay = Payslip::where('employee_id', $employee->id)
                ->where('fortnight_id', $selectedFn)
                ->first();

            $er = 0;
            $ee = 0;

            if ($employee->collect_nasfund === 1 && $employee->nasfund_number !== null) {
                $er = $getPay->regular * 0.084;
                $ee = $getPay->regular * 0.06;
            }

            if ($getPay) {
                Nasfund::updateOrCreate(
                    [
                        'employee_id' => $employee->id,
                        'fortnight_id' => $selectedFn
                    ],
                    [
                        'pay' => $getPay->regular,
                        'ER' => $er,
                        'EE' => $ee
                    ]
                );
            }
        }
    }

    public static function fnNumber()
    {
        $year = date('Y');
        $fnNumber = Fortnight::where('year', $year)->count();
        return $fnNumber;
    }

    public static function computeDailyHr($data)
    {
        $hours = 0;
        $empId = $data['id'];
        $date = $data['date'];
        $timeIn = $data['time_in'];
        $timeOut = $data['time_out'];
        $timeIn2 = $data['time_in_2'];
        $timeOut2 = $data['time_out_2'];
        $onLeave = $data['on_leave'];
        $lateInMinutes = $data['late_in_minutes'];
        $earlyTimeOutInMinutes = $data['early_time_out_in_minutes'];
        $isBreak = isset($data['is_break']) && $data['is_break'] ? 1 : 0;

        if (!empty($onLeave)) {
            if ($onLeave == 'whole_day') {
                $hours = 8;
            } elseif ($onLeave == 'first_half' || $onLeave == 'second_half') {
                $hours = 4;
            }
        } else {
            $employee = Employee::find($empId);
            if (!$employee) {
                return $hours; // Return 0 if employee is not found
            }

            $workshiftStart = $employee->workshift->start;
            $workshiftEnd = $employee->workshift->end;
            $breakTimeHours = $employee->workshift->break_time_hours;

            $workshiftStartTime = new DateTime($date . ' ' . $workshiftStart);
            $workshiftEndTime = new DateTime($date . ' ' . $workshiftEnd);

            $timeIn = !empty($timeIn) ? new DateTime($date . ' ' . $timeIn) : null;
            $timeOut = !empty($timeOut) ? new DateTime($date . ' ' . $timeOut) : null;
            $timeIn2 = !empty($timeIn2) ? new DateTime($date . ' ' . $timeIn2) : null;
            $timeOut2 = !empty($timeOut2) ? new DateTime($date . ' ' . $timeOut2) : null;

            $computedMinutes = 0;

            if ($timeIn !== null && $timeOut2 !== null) {
                // Full day with break
                $computedMinutes = ($timeIn->diff($timeOut2)->h * 60 + $timeIn->diff($timeOut2)->i);

                if ($isBreak) {
                    $computedMinutes -= ($breakTimeHours * 60);
                }

            } elseif ($timeIn !== null && $timeOut !== null) {
                // First shift only
                $computedMinutes = ($timeIn->diff($timeOut)->h * 60 + $timeIn->diff($timeOut)->i);
            } elseif ($timeIn2 !== null && $timeOut2 !== null) {
                // Second shift only
                $computedMinutes = ($timeIn2->diff($timeOut2)->h * 60 + $timeIn2->diff($timeOut2)->i);
            } else {
                $computedMinutes = 0;
            }

            $computedMinutes -= ($lateInMinutes + $earlyTimeOutInMinutes);

            $hours = self::convertMinutesToHours($computedMinutes);
        }

        return $hours;
    }


    public static function convertMinutesToHours($minutes)
    {
        return $minutes / 60;
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
            foreach ($taxTable->tax_table_ranges as $taxRange) {
                if (!is_null($taxRange->from) && !is_null($taxRange->to)) {
                    if ($gross >= $taxRange->from && $gross <= $taxRange->to) {
                        $taxValue = $gross * ($taxRange->percentage / 100);
                    }
                } elseif (is_null($taxRange->to)) {
                    if ($gross >= $taxRange->from) {
                        $taxValue = $gross * ($taxRange->percentage / 100);
                    }
                }
            }
        }
        return $taxValue;
    }

    public static function computePayslip($employeeId, $fnId)
    {
        $getDates = Fortnight::where('id', $fnId)->first();

        if (!$getDates) {
            return ['error' => 'Fortnight not found'];
        }

        $start = $getDates->start;
        $end = $getDates->end;

        $endDate = new DateTime($end);
        $endDateAttendance = new DateTime($end);
        $endDateAttendance->modify('+1 day');
        $end = $endDate->format('Y-m-d');

        $employee = Employee::where('id', $employeeId)->first();

        if (!$employee) {
            return ['error' => 'Employee not found'];
        }

        $rate = SalaryHistory::where('is_active', 1)
            ->where('employee_id', $employee->id)->first();

        if ($employee->label === 'Expatriate') {
            $employee_rate = round((($rate?->monthly_rate * 12) / 26) / 84, 2);
        } elseif ($employee->label === 'National') {
            $employee_rate = $rate?->salary_rate;
        }


        $getHours = Attendance::selectRaw('date, time_in, time_out, time_in_2, time_out_2, is_break, late_in_minutes, DAYNAME(date) as day_name, on_leave')
            ->where('employee_number', $employee->employee_number)
            ->whereBetween('date', [$start, $endDateAttendance])
            ->get();

        $totalHours = 0;
        $sundayTotalHours = 0;
        $regularHours = 0;
        $otHours = 0;
        $holidayHours = 0;
        $holidayWork = [];

        foreach ($getHours as $hours) {
            $attendance = [
                'id' => $employeeId,
                'date' => $hours->date,
                'time_in' => $hours->time_in,
                'time_out' => $hours->time_out,
                'time_in_2' => $hours->time_in_2,
                'time_out_2' => $hours->time_out_2,
                'is_break' => $hours->is_break,
                'on_leave' => $hours->on_leave,
                'late_in_minutes' => $hours->late_in_minutes,
                'early_time_out_in_minutes' => $hours->early_time_out_in_minutes,
            ];

            $computedHour = Helpers::computeDailyHr($attendance);

            $checkHoliday = Holiday::where('holiday_date', $hours->date)->first();

            if ($checkHoliday) {
                $holidayHours += $computedHour;
                $holidayWork[$hours->date] = $computedHour;
                continue;
            }

            if ($hours->day_name == 'Sunday') {
                $sundayTotalHours += $computedHour;
            }

            $totalHours += $computedHour;
        }

        $getHoliday = Holiday::whereBetween('holiday_date', [$start, $endDateAttendance])->get();

        if ($getHoliday) {
            foreach ($getHoliday as $holiday) {
                if (array_key_exists($holiday->holiday_date, $holidayWork)) {
                    foreach ($holidayWork as $date => $hoursWorked) {
                        if ($date === $holiday->holiday_date) {
                            $totalHours += $hoursWorked;
                        }
                    }
                } else {
                    $totalHours += 8;
                }
            }
        }

        if ($employee->workshift->number_of_hours_fn <= $totalHours) {
            $otHours = $totalHours - $employee->workshift->number_of_hours_fn;
            $regularHours = $employee->workshift->number_of_hours_fn;
        } else {
            $regularHours = $totalHours;
        }

        $totalWorkHoursFn = $employee->workshift->number_of_hours_fn;

        $fnRate = $employeeRate * $totalWorkHoursFn;
        $regular = ($fnRate * $regularHours) / $totalWorkHoursFn;
        $overtime = ($otHours * $employeeRate) * 1.5;
        $sundayOt = $sundayTotalHours * $employeeRate;
        $holidayOt = $holidayHours * $employeeRate;

        $data = [
            'regular' => $regular,
            'overtime' => $overtime,
            'sunday_ot' => $sundayOt,
            'holiday_ot' => $holidayOt,
        ];

        return $data;
    }

    public static function computeEmployeeNPF($employeeId, $regular)
    {
        $employee_npf = Employee::where('id', $employeeId)->first();

        $npf = 0;

        if ($employee_npf->collect_nasfund === 1 && $employee_npf->nasfund_number !== null) {

            $npf = $regular * 0.06;
        }

        return $npf;
    }

    public static function getRanges($fnId)
    {
        $dateRangeArray = [];
        $isHoliday = false;
        $isSunday = false;

        $data = Fortnight::where('id', $fnId)->first();

        if (!$data) {
            return $dateRangeArray;
        }

        $startDate = Carbon::createFromFormat('Y-m-d', $data->start);
        $endDate = Carbon::createFromFormat('Y-m-d', $data->end);

        // Iterate through each day between start and end dates
        $x = 1;
        while ($startDate->lte($endDate)) {
            // Format the date and day
            $fulldate = $startDate->format('Y-m-d');
            $formattedDate = $startDate->format('d-M');
            $formattedDay = $startDate->format('D');

            $check = Holiday::where('holiday_date', $fulldate)->first();
            $isHoliday = $check ? true : false;

            $isSunday = $formattedDay == 'Sun' || $formattedDay == 'Sun2' ? true : false;
            // Push to the array
            $dateRangeArray[] = ['day' => $formattedDay . ($x > 7 ? "2" : ""), 'date' => $formattedDate, "fortnight_id" => $data->id, "full_date" => $fulldate, 'is_holiday' => $isHoliday, 'is_sunday' => $isSunday];

            // Move to the next day
            $startDate->addDay();
            $x++;
        }

        return $dateRangeArray;
    }
}
