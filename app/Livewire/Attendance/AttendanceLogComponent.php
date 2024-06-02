<?php

namespace App\Livewire\Attendance;

use DateTime;
use Carbon\Carbon;
use Livewire\Component;
use App\Helpers\Helpers;
use App\Models\Employee;
use App\Models\Fortnight;
use App\Models\Attendance;
use App\Models\BusinessUser;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use App\Models\EmployeeHours;
use App\Models\Holiday;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;

class AttendanceLogComponent extends Component
{
    use WithPagination;

    // filters
    public $businessId;
    public $perPage = 10;
    public $search = '';
    public $fortnights = [];
    #[Url]
    public $selectedFN;
    public $records = [];
    public $ranges = [];
    public $holidays = [];

    public $fn_start;
    public $fn_end;
    public $fn_id;

    public $selectAll = false;
    public $selectedRows = [];
    public $employee_ids = [];

    #[Title('Attendance Logs')]
    public function render()
    {
        return view('livewire.attendance.attendance-log-component');
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedRows = $this->records->pluck('id');
        } else {
            $this->selectedRows = [];
        }
    }

    public function mount()
    {
        $businessUser = BusinessUser::where('user_id', Auth::user()->id)
            ->where('is_active', true)
            ->first();
        if (!$businessUser) {
            return redirect()->back();
        }

        $this->businessId = $businessUser->business_id;

        $this->fortnights = Fortnight::all();

        $this->getRanges();
    }

    public function generate()
    {
        if ($this->selectedFN == '') {
            $this->records = [];
            $this->ranges = [];
        } else {
            // Get employees based on business_id and search criteria
            $employees = Employee::where('business_id', $this->businessId)
                ->search(trim($this->search))->get();

            // Get date ranges
            $ranges = $this->getRanges();

            $records = [];

            // Loop through employees to calculate records
            foreach ($employees as $data) {

                $days = [];
                $totalHours = 0;
                $otHours = 0;
                $otSunday = 0;
                $dailyHours = 0;
                $otHoliday = 0;
                // Loop through date ranges
                foreach ($ranges as $range) {
                    $attendance = Attendance::where('fortnight_id', $this->selectedFN)
                        ->where('date', $range['full_date'])
                        ->where('employee_number', $data->employee_number)
                        ->first();

                    if ($attendance) {
                        $dailyHour = [
                            'id' => $data->id,
                            'date' => $attendance->date,
                            'time_in' => $attendance->time_in,
                            'time_out' => $attendance->time_out,
                            'time_in_2' => $attendance->time_in_2,
                            'time_out_2' => $attendance->time_out_2,
                            'is_break' => $attendance->is_break,
                            'on_leave' => $attendance->on_leave,
                            'late_in_minutes' => $attendance->late_in_minutes,
                            'early_time_out_in_minutes' => $attendance->early_time_out_in_minutes,
                        ];

                        $dailyHours = Helpers::computeDailyHr($dailyHour);

                        if ($range['day'] == 'Sun' || $range['day'] == 'Sun2') {
                            $otSunday += $dailyHours;
                        } else {
                            $totalHours += $dailyHours;
                        }

                        if ($range['is_holiday']) {
                            $otHoliday += $dailyHours;
                        }

                        $days[] = [
                            'daily_hours' => $dailyHours,
                            'is_sunday' => in_array($range['day'], ['Sun', 'Sun2']),
                            'is_holiday' => $range['is_holiday']
                        ];
                    } else {

                        $days[] = [
                            'daily_hours' => 0,
                            'is_sunday' => in_array($range['day'], ['Sun', 'Sun2']),
                            'is_holiday' => $range['is_holiday']
                        ];
                    }
                }

                // Calculate regular and overtime hours
                if ($totalHours > $data->workshift->number_of_hours_fn) {
                    $regular = $data->workshift->number_of_hours_fn;
                    $otHours = $totalHours - $data->workshift->number_of_hours_fn;
                } else {
                    $regular = $totalHours;
                }

                if (is_null($data->active_salary))
                    continue;

                //update or create employee hours
                EmployeeHours::updateOrCreate(
                    ['fortnight_id' => $this->selectedFN, 'employee_id' => $data->id, 'salary_id' => $data->active_salary->id],
                    ['salary_id' => $data->active_salary->id, 'regular_hr' => $regular, 'overtime_hr' => $otHours, 'sunday_ot_hr' => $otSunday, 'holiday_ot_hr' => $otHoliday]
                );

                // Add record to the records array
                $records[] = [
                    'id' => $data->id,
                    'employee_number' => $data->employee_number,
                    'employee_name' => strtoupper($data->fullName),
                    'regular' => $regular,
                    'ot_hours' => $otHours,
                    'sunday_ot' => $otSunday,
                    'holiday' => $otHoliday,
                    'days' => $days,
                ];
            }

            // Collect records
            $this->records = collect($records);

            // Set ranges
            $this->ranges = $ranges;
        }
    }


    public function getRanges()
    {
        $dateRangeArray = [];
        $isHoliday = false;
        $isSunday = false;

        $data = Fortnight::where('id', $this->selectedFN)->first();

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

    public function generatePdf()
    {
        return redirect()->route('attendance-log-pdf', ['employeeIds' => $this->selectedRows, 'fnId' => $this->selectedFN]);
    }
}
