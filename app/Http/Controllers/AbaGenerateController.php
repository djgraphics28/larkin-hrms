<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Payrun;
use App\Models\Employee;
use App\Models\Fortnight;
use Illuminate\Http\Request;
use ABWebDevelopers\AbaGenerator\Aba;

class AbaGenerateController extends Controller
{
    public function generate($payrun_id)
    {
        $payrun = Payrun::where('id', $payrun_id)->first();
        $FN = Fortnight::find($payrun->fortnight_id)->code;


        //use 'with' to eager load the relationship
        $employees = Employee::with(['aba_payslip', 'active_bank_detail'])
            ->whereHas('aba_payslip', function ($query) use ($payrun_id) {
                $query->where('payrun_id', $payrun_id);
            })
            ->wherehas('active_bank_detail')
            ->get();




        $now = Carbon::now();

        $aba = new Aba();

        $aba->addFileDetails([
            'bank_name' => 'CBA',
            'user_name' => 'LARKIN ENTERPRISES LIMITED',
            'bsb' => '088-950',
            'account_number' => '700927641',
            'remitter' => 'LARKIN',
            'user_number' => '301500',
            'description' => 'Payroll',
            'process_date'  => $now->format('dmy')
        ]);
        $total_amount = 0;
        $transactions = [];
        foreach ($employees as $employee) {

            if (!is_null($employee->active_bank_detail)) { //only employees with active bank details will be included / generated

                if (!empty($employee->aba_payslip)) {

                    $payslip_amount =  ($employee->aba_payslip->regular + $employee->aba_payslip->overtime + $employee->aba_payslip->sunday_ot + $employee->aba_payslip->holiday_ot + $employee->aba_payslip->plp_afp_ap + $employee->aba_payslip->other) - ($employee->aba_payslip->fn_tax + $employee->aba_payslip->npf + $employee->aba_payslip->ncsl + $employee->aba_payslip->cash_adv);

                    $total_amount += $payslip_amount;

                    $transactions[] = [
                        'bsb' => $employee->active_bank_detail->bsb_code, // bsb with hyphen
                        'account_number' => $employee->active_bank_detail->account_number,
                        'account_name'  => $employee->active_bank_detail->account_name,
                        'reference' => 'Payroll number',
                        'transaction_code'  => '53',
                        'amount' => $payslip_amount
                    ];
                }
            }
        }

        foreach ($transactions as $transaction) {
            $aba->addTransaction($transaction);
        }

        $aba->generate();

        $aba->download('ABA_File_' . $FN . ' (' . $payrun->remarks . ')');
    }
}
