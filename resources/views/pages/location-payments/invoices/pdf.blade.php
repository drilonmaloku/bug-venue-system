<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{__('dashboard.invoice')}} #{{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            line-height: 1.6;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .invoice-info {
            margin-bottom: 30px;
        }
        .invoice-info table {
            width: 100%;
            border-collapse: collapse;
        }
        .invoice-info th {
            text-align: left;
            width: 150px;
            padding: 8px;
        }
        .invoice-info td {
            padding: 8px;
        }
        .location-info {
            margin-bottom: 30px;
        }
        .location-info table {
            width: 100%;
            border-collapse: collapse;
        }
        .location-info th {
            text-align: left;
            width: 150px;
            padding: 8px;
        }
        .location-info td {
            padding: 8px;
        }
        .status {
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: bold;
        }
        .status-paid {
            color: #28a745;
        }
        .status-pending {
            color: #ffc107;
        }
        .status-cancelled {
            color: #dc3545;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{__('dashboard.invoice')}} #{{ $invoice->invoice_number }}</h1>
    </div>

    <div class="invoice-info">
        <h3>{{__('dashboard.invoice_info')}}</h3>
        <table>
            <tr>
                <th>{{__('dashboard.invoice_number')}}</th>
                <td>{{ $invoice->invoice_number }}</td>
            </tr>
            <tr>
                <th>{{__('dashboard.credits')}}</th>
                <td>{{ $invoice->credits }}</td>
            </tr>
            <tr>
                <th>{{__('dashboard.amount')}}</th>
                <td>€{{ number_format($invoice->amount, 2) }}</td>
            </tr>
            <tr>
                <th>{{__('dashboard.status')}}</th>
                <td>
                    <span class="status status-{{ $invoice->status }}">
                        {{__('dashboard.' . $invoice->status)}}
                    </span>
                </td>
            </tr>
            <tr>
                <th>{{__('dashboard.due_date')}}</th>
                <td>{{ $invoice->due_date->format('d/m/Y') }}</td>
            </tr>
            @if($invoice->paid_date)
            <tr>
                <th>{{__('dashboard.paid_date')}}</th>
                <td>{{ $invoice->paid_date->format('d/m/Y') }}</td>
            </tr>
            @endif
            @if($invoice->description)
            <tr>
                <th>{{__('dashboard.description')}}</th>
                <td>{{ $invoice->description }}</td>
            </tr>
            @endif
        </table>
    </div>

    <div class="location-info">
        <h3>{{__('dashboard.location_info')}}</h3>
        <table>
            <tr>
                <th>{{__('dashboard.name')}}</th>
                <td>{{ $location->name }}</td>
            </tr>
            <tr>
                <th>{{__('dashboard.address')}}</th>
                <td>{{ $location->address }}</td>
            </tr>
            <tr>
                <th>{{__('dashboard.phone')}}</th>
                <td>{{ $location->phone }}</td>
            </tr>
            <tr>
                <th>{{__('dashboard.email')}}</th>
                <td>{{ $location->email }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        {{__('dashboard.generated_on')}}: {{ now()->format('d/m/Y H:i:s') }}
    </div>
</body>
</html> 