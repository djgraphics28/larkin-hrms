<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Fortnight;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfDownloadController extends Controller
{
    public function nasfund($businessId, $fnId)
    {

        $employees = Employee::where('business_id', $businessId)
            ->where('collect_nasfund', 1)
            ->where('nasfund_number', '<>', null)
            ->whereIn('id', function ($query) use ($fnId) {
                $query->from('payslips')
                    ->select('employee_id')
                    ->where('fortnight_id', $fnId)
                    ->where('is_approved', 1);
            })
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
}
