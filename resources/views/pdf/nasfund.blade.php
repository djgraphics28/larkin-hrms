<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>NASFUND</title>
    <!-- Link to Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        @page {
            size: A4 landscape;
            margin: 5mm 5mm 5mm 5mm; /* Top, Right, Bottom, Left */
        }
        body {
            margin: 0; /* Reset body margin to zero */
        }
        .container {
            padding: 10mm; /* Adjust as needed */
        }
        table {
            font-size: 12px; /* Adjust table font size */
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="{{ public_path('assets/images/logo.png') }}" alt="larkin" width="200" />
        <h5 class="text-center">NASFUND</h5>
        <p class="mb-0">Date Range: {{ \Carbon\Carbon::parse($fortnight->start)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($fortnight->end)->format('M d, Y') }}</p>
        <p >Generate Date: {{ date('M d, Y') }}</p>

        <table class="table table-bordered table-striped table-sm">
            <thead>
                <tr>
                    <th class="text-center">EMP. NO</th>
                    <th class="text-center">LAST NAME</th>
                    <th class="text-center">FIRST NAME</th>
                    <th class="text-center">EMPLOYMENT DATE</th>
                    <th class="text-center">NPF NUMBER</th>
                    <th class="text-center">EMPLOYER RN</th>
                    <th class="text-center">PAY</th>
                    <th class="text-center">ER (8.4%)</th>
                    <th class="text-center">EE (6%)</th>
                    <th class="text-center">TOTAL</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total_pay = 0;
                    $total_er = 0;
                    $total_ee = 0;
                    $no_record = false;
                @endphp
                @forelse($employees as $employee)

                    <tr>
                        <td class="text-center">{{ $employee->employee_number }}</td>
                        <td class="text-center">{{ $employee->last_name }}</td>
                        <td class="text-center">{{ $employee->first_name }}</td>
                        <td class="text-center">{{ date('d-M-y', strtotime($employee->joining_date)) }}</td>
                        <td class="text-center">{{ $employee->nasfund_number }}</td>
                        <td class="text-center">{{ $employerRN }}</td>

                        @forelse($employee->aba_payslip as $payslip)
                            @if((int)$payslip->fortnight_id == $fnId && (int)$payslip->employee_id == $employee->id)

                                @php
                                    $total_pay += $payslip->regular;
                                    $total_er += $er = round($payslip->regular * 0.084, 2);
                                    $total_ee += $ee = round($payslip->regular * 0.06, 2);
                                @endphp

                                <td class="text-right">{{ number_format($payslip->regular, 2) }}</td>
                                <td class="text-right">{{ number_format($er, 2) }}</td>
                                <td class="text-right">{{ number_format($ee, 2) }}</td>
                                <td class="text-right">{{ number_format($er + $ee, 2) }}</td>
                            @endif
                        @empty
                            <td colspan="10">No Records Found</td>
                        @endforelse
                    </tr>

                @empty
                    @php
                        $no_record = true;
                    @endphp
                    <tr>
                        <td colspan="10" class="text-center">No Records Found</td>
                    </tr>
                @endforelse
            </tbody>

            @if($no_record == false)
                <tfoot>
                    <tr>
                        <th colspan="7" class="text-right">K {{ number_format($total_pay, 2) }}</th>
                        <th class="text-right">K {{ number_format($total_er,2) }}</th>
                        <th class="text-right">K {{ number_format($total_ee,2) }}</th>
                        <th class="text-right">K {{ number_format($total_er + $total_ee,2) }}</th>
                    </tr>
                </tfoot>
            @endif
        </table>
    </div>
</body>
</html>