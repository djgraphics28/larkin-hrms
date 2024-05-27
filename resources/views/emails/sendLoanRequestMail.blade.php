<x-mail::message>

Dear Boss,

I hope this email finds you well. {{ $employee->first_name }} has submitted a loan request for your approval.

Loan Details:

Date Requested: {{ $data->date_requested }}<br>
Reason: {{ $data->reason }}<br>
Loan Type: {{ $data->loan_type->name }}<br>
Amount: K{{ $data->amount }}<br>

To approve, please click the button below:

<x-mail::button :url="$link">
    Approve
</x-mail::button>

Thank you for your prompt attention to this matter.

Best regards,<br>
{{ config('app.name') }}

</x-mail::message>
