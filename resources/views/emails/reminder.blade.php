<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $reminder->title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #4a6cf7;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .content {
            padding: 20px;
            background-color: #f9f9f9;
        }
        .footer {
            text-align: center;
            padding: 20px;
            font-size: 12px;
            color: #777;
        }
        .button {
            display: inline-block;
            background-color: #4a6cf7;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            margin-top: 20px;
        }
        .details {
            background-color: white;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
        }
        .details p {
            margin: 5px 0;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: bold;
        }
        .badge-info {
            background-color: #17a2b8;
            color: white;
        }
        .badge-primary {
            background-color: #007bff;
            color: white;
        }
        .badge-success {
            background-color: #28a745;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ $reminder->title }}</h1>
        </div>
        
        <div class="content">
            <p>{{ $reminder->message }}</p>
            
            <div class="details">
                <p><strong>{{ __('reminders.form.reminder_date') }}:</strong> {{ $reminder->reminder_date->format('Y-m-d H:i') }}</p>
                <p><strong>{{ __('reminders.form.type') }}:</strong> 
                    <span class="badge {{ $reminder->type === 'in_app' ? 'badge-info' : ($reminder->type === 'email' ? 'badge-primary' : 'badge-success') }}">
                        {{ $reminder->type }}
                    </span>
                </p>
                <p><strong>{{ __('reminders.form.created_by') }}:</strong> {{ $reminder->creator->name }}</p>
                
                @if(isset($reservation))
                <p><strong>{{ __('reminders.form.reservation') }}:</strong> 
                    <a href="{{ route('reservations.view', ['id' => $reservation->id]) }}">
                        {{ $reservation->description ?? $reservation->id }}
                    </a>
                </p>
                @endif
            </div>
            
            @if(isset($reservation))
            <div style="text-align: center; margin-top: 30px;">
                <a href="{{ route('reservations.view', ['id' => $reservation->id]) }}" class="button">
                    {{ __('reminders.email.view_reservation') }}
                </a>
            </div>
            @endif
        </div>
        
        <div class="footer">
            <p>{{ __('reminders.email.footer_text') }}</p>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}</p>
        </div>
    </div>
</body>
</html> 