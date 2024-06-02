<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Employee;
use App\Models\Fortnight;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfDownloadController extends Controller
{
    public function nasfund($businessId, $fnId)
    {

        $employees = Employee::with(['payslip' => function ($query) use ($fnId) {
            $query->where('fortnight_id', $fnId)
                ->where('is_approved', 1);
        }])
            ->where('business_id', $businessId)
            ->where('collect_nasfund', 1)
            ->whereNotNull('nasfund_number')
            ->where('is_discontinued', false)
            ->get();

        $data['employees'] = $employees;
        $data['businessId'] = $businessId;
        $data['employerRN'] = '131934';
        $data['fnId'] = $fnId;
        $data['fortnight'] = Fortnight::where('id', $fnId)->first();

        $pdf = Pdf::loadView('pdf/nasfund', $data);

        $pdf->setPaper('A4', 'landscape');

        return $pdf->stream('nasfund.pdf');
    }

    public function attendanceLog($employeeIds, $fnId)
    {
        $data = [];

        $ranges = $this->getRanges($fnId);

        $data ['ranges'] = $ranges;
        $data ['ranges'] = $ranges;

        $pdf = Pdf::loadView('pdf/attendance-log', $data);

        $pdf->setPaper('A4', 'landscape');

        return $pdf->stream('nasfund.pdf');
    }

    public function getRanges($fnId)
    {
        $dateRangeArray = [];
        $isHoliday = false;

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
            // Push to the array
            $dateRangeArray[] = ['day' => $formattedDay . ($x > 7 ? "2" : ""), 'date' => $formattedDate, "fortnight_id" => $data->id, "full_date" => $fulldate, 'is_holiday' => $isHoliday];

            // Move to the next day
            $startDate->addDay();
            $x++;
        }

        return $dateRangeArray;
    }
}
