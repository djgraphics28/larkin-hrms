<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Employee;
use App\Models\Fortnight;
use Illuminate\Http\Request;
use ABWebDevelopers\AbaGenerator\Aba;

class AbaGenerateController extends Controller
{
    public function generate($businessId, $selectedFN, $employeeIds)
    {
        $FN = Fortnight::find($selectedFN)->code;
        // Convert the string back to an array
        $employeeIdsArray = json_decode($employeeIds);

        //use 'with' to eager load the relationship
        $employees = Employee::with(['active_bank_detail','aba_payslip' => function($query) use($selectedFN) {
            $query->where('fortnight_id', $selectedFN);
        }])->where('business_id', $businessId)
            ->whereIn('id', $employeeIdsArray)
            ->get();

        $fortnights = Fortnight::where('id', $selectedFN)->first();

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

            // $get_payslip = Payslip::where('fortnight_id', $this->selectedFN)
            //     ->where('employee_id', $employee->id)
            //     ->first();
                if(!empty($employee->aba_payslip)) {
                    $payslip_amount =  ($employee->aba_payslip->regular + $employee->aba_payslip->overtime + $employee->aba_payslip->sunday_ot + $employee->aba_payslip->holiday_ot + $employee->aba_payslip->plp_afp_ap + $employee->aba_payslip->other) - ($employee->aba_payslip->fn_tax + $employee->aba_payslip->npf + $employee->aba_payslip->ncsl + $employee->aba_payslip->cash_adv);
                    // $payslip_amount  = ($get_payslip->regular + $get_payslip->overtime + $get_payslip->sunday_ot + $get_payslip->holiday_ot + $get_payslip->plp_afp_ap + $get_payslip->other) - ($get_payslip->fn_tax + $get_payslip->npf + $get_payslip->ncsl + $get_payslip->cash_adv);
                    $total_amount += $payslip_amount;

                    $transactions[] = [
                        'bsb' => $employee->active_bank_detail->bsb_code, // bsb with hyphen
                        'account_number' => $employee->active_bank_detail->account_number,
                        'account_name'  => $employee->active_bank_detail->account_name,
                        'reference' => 'Payroll number',
                        'transaction_code'  => '53',
                        'amount' => $total_amount
                    ];
                }

            }
        }

        foreach ($transactions as $transaction) {
            $aba->addTransaction($transaction);
        }

        $aba->generate();

        $aba->download('ABA_File_'.$FN);
    }
}
