<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PDFController extends Controller
{
    public function generateFinalPay($id)
    {
        $data = Employee::find($id);
        $pdf = Pdf::loadView('pdf.final-pay', compact('data'));

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
            }, $data->employee_number.'-final-pay.pdf');
    }

    public function bulkGenerateFinalPay($ids)
    {
        $ids = json_decode($ids);
        $employees = Employee::whereIn('id', $ids)->get();
        $pdf = Pdf::loadView('pdf.bulk-final-pay', compact('employees'));

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
            }, 'bulk-final-pay.pdf');
    }
}
