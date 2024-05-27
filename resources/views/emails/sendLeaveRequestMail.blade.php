<x-mail::message>

Dear Boss,

I hope this email finds you well. {{ $employee->first_name }} has submitted a leave request for your approval.

Leave Details:

Start Date: {{ $data->date_from }}<br>
End Date: {{ $data->date_to }}<br>
Total Days: {{ $data->number_of_day }}<br>
Reason: {{ $data->reason }}

To approve, please click the button below:

<x-mail::button :url="$link">
    Approve
</x-mail::button>

Thank you for your prompt attention to this matter.

Best regards,<br>
{{ config('app.name') }}

</x-mail::message>
