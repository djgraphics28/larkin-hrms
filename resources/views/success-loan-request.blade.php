<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loan Request</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #007bff;
        }

        p {
            margin-top: 20px;
            text-align: center;
            font-size: 18px;
        }

        .btn {
            display: block;
            width: 200px;
            margin: 20px auto;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            text-align: center;
            padding: 10px;
            border-radius: 5px;
        }

        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="container">
        @if (session('success'))
            <p>{{ session('success') }}</p>
        @else
            <p>{{ session('error') }}</p>
        @endif

        {{-- <a href="{{ route('dashboard') }}" class="btn">Back to Dashboard</a> --}}
    </div>
</body>

</html>
