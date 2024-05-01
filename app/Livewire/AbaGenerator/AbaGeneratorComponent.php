<?php

namespace App\Livewire\AbaGenerator;

use Carbon\Carbon;
use App\Models\Payslip;
use Livewire\Component;
use App\Models\Employee;
use App\Models\Fortnight;
use App\Models\BankDetail;
use App\Models\BusinessUser;
use Illuminate\Support\Facades\Auth;
use ABWebDevelopers\AbaGenerator\Facades\Aba;


class AbaGeneratorComponent extends Component
{

    public $businessId;
    public $fortnights = [];
    public $selectedFN;
    public $transaction = [];

    public function render()
    {
        return view('livewire.aba-generator.aba-generator-component');
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
        $this->generate();
    }

    public function generate()
    {
        $this->validate([
            'selectedFN' => 'required'
        ]);

        $employees = Employee::where('business_id', $this->businessId)
            ->whereIn('id', [4, 5])
            ->get();

        $fortnights = Fortnight::where('id', $this->selectedFN)->first();

        $total_amount = 0;


        $now = Carbon::now();

        // Aba::addFileDetails([
        //     'bank_name' => 'CBA',
        //     'user_name' => 'LARKIN ENTERPRISES LIMITED',
        //     'bsb' => '088-950',
        //     'account_number' => '700927641',
        //     'remitter' => 'LARKIN',
        //     'user_number' => '301500',
        //     'description' => 'Payroll',
        //     'process_date'  => $now->format('dmy')
        // ]);

        Aba::addFileDetails([
            'bank_name' => 'CBA',
            'user_name' => 'Your account name',
            'bsb' => '062-111',
            'account_number' => '101010101',
            'remitter' => 'Name of remitter',
            'user_number' => '301500',
            'description' => 'Payroll',
            'process_date'  => '270616'
        ]);

        foreach ($employees as $employee) {
            $get_payslip = Payslip::where('fortnight_id', $this->selectedFN)
                ->where('employee_id', $employee->id)
                ->first();

            $payslip_amount  = ($get_payslip->regular + $get_payslip->overtime + $get_payslip->sunday_ot + $get_payslip->holiday_ot + $get_payslip->plp_afp_ap + $get_payslip->other) - ($get_payslip->fn_tax + $get_payslip->npf + $get_payslip->ncsl + $get_payslip->cash_adv);
            $total_amount = $total_amount + $payslip_amount;

            $get_bank = BankDetail::where('employee_id', $employee->id)
                ->where('is_active', 1)
                ->first();
            $transaction = [
                'bsb' => $get_bank->bsb_code,
                'account_number' => $get_bank->account_number,
                'account_name'  => $get_bank->account_name,
                'reference' => $fortnights->code,
                'transaction_code'  => '53',
                'amount' => $payslip_amount,
            ];
            // Aba::addTransaction($transaction);
        }

        $transactions = [
            [
                'bsb' => '111-111', // bsb with hyphen
                'account_number' => '999999999',
                'account_name'  => 'Jhon doe',
                'reference' => 'Payroll number',
                'transaction_code'  => '53',
                'amount' => '250.87'
            ],
            [
                'bsb' => '222-222', // bsb with hyphen
                'account_number' => '888888888',
                'account_name'  => 'Foo Bar',
                'reference' => 'Rent',
                'transaction_code'  => '50',
                'amount' => '300'
            ]
        ];

        foreach ($transactions as $transaction) {
            Aba::addTransaction($transaction);
        }


        Aba::generate();

        Aba::download();
    }
}
