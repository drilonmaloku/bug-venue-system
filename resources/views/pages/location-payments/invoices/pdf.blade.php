<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{__('dashboard.invoice')}} {{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .invoice-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .invoice-number {
            font-size: 16px;
            color: #666;
        }
        .info-section {
            margin-bottom: 30px;
        }
        .info-section h3 {
            font-size: 16px;
            margin-bottom: 10px;
            color: #333;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 150px 1fr;
            gap: 10px;
        }
        .info-label {
            font-weight: bold;
            color: #666;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
        .status-paid {
            background-color: #28a745;
            color: white;
        }
        .status-pending {
            background-color: #ffc107;
            color: #000;
        }
        .status-cancelled {
            background-color: #dc3545;
            color: white;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="invoice-title">{{__('dashboard.invoice')}}</div>
        <div class="invoice-number">{{ $invoice->invoice_number }}</div>
    </div>

    <div class="info-section">
        <h3>{{__('dashboard.invoice_info')}}</h3>
        <div class="info-grid">
            <div class="info-label">{{__('dashboard.invoice_number')}}:</div>
            <div>{{ $invoice->invoice_number }}</div>
            
            <div class="info-label">{{__('dashboard.credits')}}:</div>
            <div>{{ $invoice->credits }}</div>
            
            <div class="info-label">{{__('dashboard.status')}}:</div>
            <div>
                @switch($invoice->status)
                    @case('paid')
                        <span class="status-badge status-paid">{{__('dashboard.paid')}}</span>
                        @break
                    @case('pending')
                        <span class="status-badge status-pending">{{__('dashboard.pending')}}</span>
                        @break
                    @case('cancelled')
                        <span class="status-badge status-cancelled">{{__('dashboard.cancelled')}}</span>
                        @break
                @endswitch
            </div>
            
            <div class="info-label">{{__('dashboard.due_date')}}:</div>
            <div>{{ $invoice->due_date->format('Y-m-d') }}</div>
            
            @if($invoice->paid_date)
                <div class="info-label">{{__('dashboard.paid_date')}}:</div>
                <div>{{ $invoice->paid_date->format('Y-m-d') }}</div>
            @endif
            
            <div class="info-label">{{__('dashboard.description')}}:</div>
            <div>{{ $invoice->description }}</div>
        </div>
    </div>

    <div class="info-section">
        <h3>{{__('dashboard.location_info')}}</h3>
        <div class="info-grid">
            <div class="info-label">{{__('dashboard.name')}}:</div>
            <div>{{ $location->name }}</div>
            
            <div class="info-label">{{__('dashboard.credits')}}:</div>
            <div>{{ $location->credits }}</div>
        </div>
    </div>

    @if($invoice->payments->count() > 0)
        <div class="info-section">
            <h3>{{__('dashboard.payments')}}</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background-color: #f8f9fa;">
                        <th style="text-align: left; padding: 8px; border-bottom: 1px solid #dee2e6;">{{__('dashboard.amount_paid')}}</th>
                        <th style="text-align: left; padding: 8px; border-bottom: 1px solid #dee2e6;">{{__('dashboard.payment_date')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoice->payments as $payment)
                        <tr>
                            <td style="padding: 8px; border-bottom: 1px solid #dee2e6;">€{{ number_format($payment->amount_paid, 2) }}</td>
                            <td style="padding: 8px; border-bottom: 1px solid #dee2e6;">{{ $payment->payment_date->format('Y-m-d') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <div class="footer">
        {{__('dashboard.computer_generated')}}
    </div>
</body>
</html> 