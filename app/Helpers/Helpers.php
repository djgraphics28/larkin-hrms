<?php

namespace App\Helpers;

use DateTime;
use Carbon\Carbon;
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
            $employeeNumber = (int)$existingNumber + 1;
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

    public static function computeHours($selected_fn)
    {
        $getDates = Fortnight::where('id', $selected_fn)->first();

        $start = $getDates->start;
        $end = $getDates->end;

        $endDate = new DateTime($end);

        $endDate->modify('+1 day');

        // Format the modified end date back to your desired format
        $end = $endDate->format('Y-m-d');

        $getEmployee = Employee::all();



        foreach ($getEmployee as $employee) {
            $getHours = Attendance::selectRaw('(TIME_TO_SEC(TIMEDIFF(time_out, time_in))/3600) - 1 as hours, DAYNAME(time_in) as day_name')
                ->where('employee_number', $employee->employee_number)
                ->whereBetween('time_in', [$start, $end])
                ->get();

            $total_hours = 0;
            $sunday_total_hours = 0;
            $regular_hours = 0;
            $ot_hours = 0;

            foreach ($getHours as $hours) {
                $computed_hour = $hours->hours;
                if ($hours->day_name == 'Sunday') {
                    $sunday_total_hours = $sunday_total_hours + ($computed_hour);
                }
                $total_hours = $total_hours + $computed_hour;
            }


            if ($employee->workshift->number_of_hours <= $total_hours) {
                $ot_hours = ($total_hours - $employee->workshift->number_of_hours);
                $regular_hours = $employee->workshift->number_of_hours;
            } else {
                $regular_hours = $total_hours;
            }

            // dd($sunday_total_hours, "", $total_hours, $employee->workshift->number_of_hours, $ot_hours);

            EmployeeHours::updateOrCreate(
                [
                    'employee_id' => $employee->id,
                    'fortnight_id' => $getDates->id,
                ],
                [
                    'regular_hr' => $regular_hours,
                    'overtime_hr' => $ot_hours,
                    'sunday_ot_hr' => $sunday_total_hours,
                    'holiday_ot_hr' => 0
                ]
            );
        }
    }

    public static function computePay($selected_fn)
    {
        $get_hours = EmployeeHours::where('fortnight_id', $selected_fn)->get();

        foreach ($get_hours as $hours) {
            $get_rate = SalaryHistory::where('employee_id', $hours->employee_id)
                ->where('is_active', 1)->first();

            $regular = $hours->regular_hr * $get_rate->salary_rate;
            $overtime = ($hours->overtime_hr * $get_rate->salary_rate) * 1.5;
            $sunday_ot = $hours->sunday_ot_hr * $get_rate->salary_rate;
            $holiday_ot = $hours->holiday_ot_hr * $get_rate->salary_rate;

            Payslip::updateOrCreate(
                [
                    'employee_id' => $hours->employee_id,
                    'fortnight_id' => $selected_fn,
                ],
                [
                    'regular' => $regular,
                    'overtime' => $overtime,
                    'sunday_ot' => $sunday_ot,
                    'holiday_ot' => $holiday_ot,
                    'plp_alp_fp' => 0,
                    'other' => 0,

                    'fn_tax' => 0,
                    'npf' => 0,
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
            default:
                return null; // Return null if designation is not found
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
                return null; // Return null if status is not found
        }
    }

}
