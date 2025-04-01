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
        .invoice-info {
            margin-bottom: 20px;
        }
        .invoice-info table {
            width: 100%;
        }
        .invoice-info td {
            padding: 5px;
        }
        .invoice-details {
            margin-bottom: 30px;
        }
        .invoice-details table {
            width: 100%;
            border-collapse: collapse;
        }
        .invoice-details th, .invoice-details td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .invoice-details th {
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
        <h1>Invoice</h1>
        <p>Invoice #{{ $invoice->invoice_number }}</p>
    </div>

    <div class="invoice-info">
        <table>
            <tr>
                <td><strong>Location:</strong></td>
                <td>{{ $location->name }}</td>
            </tr>
            <tr>
                <td><strong>Date:</strong></td>
                <td>{{ $invoice->created_at->format('Y-m-d') }}</td>
            </tr>
            <tr>
                <td><strong>Due Date:</strong></td>
                <td>{{ $invoice->due_date->format('Y-m-d') }}</td>
            </tr>
        </table>
    </div>

    <div class="invoice-details">
        <table>
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Credits</th>
                    <th>Amount (EUR)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $invoice->description ?? 'Credit Purchase' }}</td>
                    <td>{{ $invoice->credits }} EUR</td>
                    <td>{{ number_format($invoice->amount, 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="total">
        <p><strong>Total Amount: EUR {{ number_format($invoice->amount, 2) }}</strong></p>
        <p><strong>Total Credits: {{ $invoice->credits }} EUR</strong></p>
    </div>

    <div class="footer">
        <p>This is an automatically generated invoice. Please contact support if you have any questions.</p>
    </div>
</body>
</html> 