<?php

namespace App\Console\Commands;

use App\Helpers\Helpers;
use App\Models\Employee;
use App\Models\Fortnight;
use App\Models\EmployeeHours;
use Illuminate\Console\Command;

class EmployeeHoursGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:employee-hours-generator';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate employee hours';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $year = date('Y');
        $fortnights = Fortnight::where('year', $year)->get();

        foreach ($fortnights as $item) {
            $fnId = $item->id;
            $ranges = Helpers::getRanges($fnId);

            foreach ($ranges as $range) {
                $employees = Employee::where('is_discontinued', false)
                    ->orderBy('business_id', 'ASC')
                    ->get();

                foreach ($employees as $employee) {
                    // Check active salary
                    $activeSalary = $employee->active_salary;
                    if (!$activeSalary) {
                        continue;
                    }

                    // Update or create EmployeeHour
                    EmployeeHours::updateOrCreate(
                        [
                            'fortnight_id' => $fnId,
                            'employee_id' => $employee->id,
                            'salary_id' => $activeSalary->id
                        ],
                        [
                            'regular_hr' => 0,
                            'overtime_hr' => 0,
                            'sunday_ot_hr' => 0,
                            'holiday_ot_hr' => 0
                        ]
                    );
                }
            }
        }
    }

}
