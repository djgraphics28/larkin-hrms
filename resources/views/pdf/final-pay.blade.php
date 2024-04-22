<!doctype html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice</title>
</head>
<style>
    body {
        font-family: sans-serif;
    }
    h4 {
        margin: 0;
    }

    .w-full {
        width: 100%;
    }

    .w-half {
        width: 50%;
    }

    .margin-top {
        margin-top: 1.25rem;
    }

    .footer {
        font-size: 0.875rem;
        padding: 1rem;
        background-color: rgb(241 245 249);
    }

    table {
        width: 100%;
        border-spacing: 0;
    }

    table.products {
        font-size: 0.875rem;
    }

    table.products tr {
        background-color: rgb(96 165 250);
    }

    table.products th {
        color: #ffffff;
        padding: 0.5rem;
    }

    table tr.items {
        background-color: rgb(241 245 249);
    }

    table tr.items td {
        padding: 0.5rem;
    }

    .total {
        text-align: right;
        margin-top: 1rem;
        font-size: 0.875rem;
    }

    .cut-line {
      border-bottom: 1px dashed #000;
      margin: 20px 0;
    }
</style>

<body>
    <table class="w-full">
        <tr>
            <td class="w-half">
                <img src="{{ asset('assets/images/larkin-logo.jpg') }}" alt="larkin logo" width="100" />
            </td>
            <td class="w-half">
                Employer's Copy
            </td>
        </tr>
    </table>

    <div class="margin-top">
        <table class="w-full">
            <tr>
                <td class="w-half">
                    <div>EMPLOYEE NUMBER: {{ $data->first_name }}</div>
                    <div>NAME: {{ $data->first_name }} {{ $data->last_name }}</div>
                    <div>POSITION: {{ $data->designation->name }}</div>
                    <div>DATE COMMENCED: {{ $data->joining_date }}</div>
                    <div>PAY-OUT DATE: {{ $data->end_date }}</div>
                    <div>BASIC SALARY: </div>
                    <div>ANUAL LEAVE PAY:</div>
                </td>
            </tr>
        </table>
    </div>
    <div class="margin-top">
    </div>

    <div class="cut-line"></div>

    <table class="w-full">
        <tr>
            <td class="w-half">
                <img src="{{ asset('assets/images/larkin-logo.jpg') }}" alt="larkin logo" width="100" />
            </td>
            <td class="w-half">
                Employee's Copy
            </td>
        </tr>
    </table>

    <div class="margin-top">
        <table class="w-full">
            <tr>
                <td class="w-half">
                    <div>EMPLOYEE NUMBER: {{ $data->first_name }}</div>
                    <div>NAME: {{ $data->first_name }} {{ $data->last_name }}</div>
                    <div>POSITION: {{ $data->designation->name }}</div>
                    <div>DATE COMMENCED: {{ $data->joining_date }}</div>
                    <div>PAY-OUT DATE: {{ $data->end_date }}</div>
                    <div>BASIC SALARY: </div>
                    <div>ANUAL LEAVE PAY:</div>
                </td>
            </tr>
        </table>
    </div>
    <div class="margin-top">
    </div>

</body>

</html>
