<div>
    <table class="table table-sm">
        <thead>
            <tr>
                <th>FN Code</th>
                <th colspan="2" class="text-center">Wage Period</th>
                <th class="text-center">Payment Date</th>
                <th class="text-center">Rate/Hrs</th>
                <th class="text-center">TW Hrs</th>
                <th class="text-center">Absent</th>
                <th class="text-center">G Salary</th>
                <th class="text-center">Income Tax</th>
                <th class="text-center">Allw_Depn</th>
                <th class="text-center">Allw/Db/Cr</th>
                <th class="text-center">Net Salary</th>
            </tr>
        </thead>
        <tbody>
            @forelse($payslip as $payslip)
                @php
                    $gross = $payslip->regular + $payslip->overtime + $payslip->sunday_ot + $payslip->holiday_ot + $payslip->plp_alp_fp + $payslip->other;
                    $deduction = $payslip->fn_tax + $payslip->npf + $payslip->ncsl + $payslip->cash_adv;
                    $netSalary = $gross - $deduction;
                @endphp
                <tr>
                    <td class="text-center">{{$payslip->fortnight->code}}</td>
                    <td class="text-center">{{date('d-F', strtotime($payslip->fortnight->start))}}</td>
                    <td class="text-center">{{date('d-F', strtotime($payslip->fortnight->end))}}</td>
                    <td class="text-center"> --- </td>
                    <td class="text-center">K


                        @php
                            foreach ($payslip->employee->employee_hours as $salary) {
                                if($payslip->fortnight_id === $salary->fortnight_id){
                                    echo $salary->salary->salary_rate;
                                }

                            }
                        @endphp
                    </td>
                    <td class="text-center"> --- </td>
                    <td class="text-center"> --- </td>
                    <td class="text-center">K {{number_format($gross,2)}}</td>
                    <td class="text-center">K {{number_format($payslip->fn_tax,2)}}</td>
                    <td class="text-center">K 0.00</td>
                    <td class="text-center">K 0.00</td>
                    <td class="text-center">K {{number_format($netSalary,2)}}</td>
                </tr>
            @empty
            @endforelse
        </tbody>
    </table>
</div>
