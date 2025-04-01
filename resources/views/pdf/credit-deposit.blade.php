<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .location-info {
            margin-bottom: 20px;
        }
        .location-info table {
            width: 100%;
        }
        .location-info td {
            padding: 5px;
        }
        .deposits-table {
            margin-bottom: 30px;
            width: 100%;
            border-collapse: collapse;
        }
        .deposits-table th, .deposits-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .deposits-table th {
            background-color: #f5f5f5;
        }
        .total {
            text-align: right;
            margin-top: 20px;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Credit Deposits Report</h1>
        <p>Location: {{ $location->name }}</p>
    </div>

    <div class="location-info">
        <table>
            <tr>
                <td><strong>Location ID:</strong></td>
                <td>{{ $location->id }}</td>
            </tr>
            <tr>
                <td><strong>Current Credits:</strong></td>
                <td>{{ $location->credits }}</td>
            </tr>
            <tr>
                <td><strong>Generated Date:</strong></td>
                <td>{{ now()->format('Y-m-d H:i:s') }}</td>
            </tr>
        </table>
    </div>

    <div class="deposits-table">
        <table>
            <thead>
                <tr>
                    <th>Deposit Number</th>
                    <th>Date</th>
                    <th>Credits</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                @foreach($deposits as $deposit)
                <tr>
                    <td>{{ $deposit->deposit_number }}</td>
                    <td>{{ $deposit->created_at->format('Y-m-d H:i:s') }}</td>
                    <td>{{ $deposit->credits }} </td>
                    <td>{{ $deposit->notes ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="total">
        <p><strong>Total Deposits: {{ $deposits->count() }}</strong></p>
        <p><strong>Total Credits Deposited: {{ $deposits->sum('credits') }}</strong></p>
    </div>

    <div class="footer">
        <p>This is an automatically generated credit deposits report. Please contact support if you have any questions.</p>
    </div>
</body>
</html> 