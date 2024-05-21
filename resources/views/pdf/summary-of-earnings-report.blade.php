<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Summary of Earnings Reports</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        .table th {
            background-color: #f2f2f2;
            text-align: left;
        }
        .table-info {
            background-color: #d1ecf1;
        }
        .text-center {
            text-align: center;
        }
        .sticky-header {
            position: sticky;
            top: 0;
            z-index: 1;
        }
    </style>
</head>
<body>
    <h1>Nasfund Reporting</h1>
    <table class="table table-sm table-striped">
        <thead class="table-info sticky-header">
            <tr>
                <th>Emp Code</th>
                <th>Last Name</th>
                <th>First Name</th>
                <th class="text-center">Employment Date</th>
                <th>NPF Number</th>
                <th>Employer RN</th>
                <th>Pay</th>
                <th>ER(8.4%)</th>
                <th>EE(6%)</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($records as $item)
                <tr>
                    <td>{{ $item->employee_number }}</td>
                    <td>{{ $item->last_name }}</td>
                    <td>{{ $item->first_name }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($item->joining_date)->format('M-d-Y') }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            @empty
                <tr>
                    <td colspan="10">No data found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
