<!DOCTYPE html>
<html>
<head>
    <style>
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';;
        }
        .email-header {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
        }
        .email-header img {
            max-width: 200px;
            margin-bottom: 20px;
        }
        .email-content {
            padding: 20px;
            background: #ffffff;
        }
        .reset-button {
            display: inline-block;
            padding: 12px 24px;
            background: #0d6efd;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 4px;
            margin: 20px 0;
        }
        .email-footer {
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <img src="https://bugagency.tech/wp-content/uploads/assets/logo_main.png" alt="Bug Agency Logo">
            <h2>Reset Your Password</h2>
        </div>

        <div class="email-content">
            <p>Hi,</p>
            
            <p>You are receiving this email because we received a password reset request for your account at {{ config('app.name') }}.</p>
            
            <div style="text-align: center;">
                <a href="{{ $resetUrl }}" class="reset-button">Reset Password</a>
            </div>

            <p>This password reset link will expire in {{ config('auth.passwords.'.config('auth.defaults.passwords').'.expire') }} minutes.</p>
            
            <p>If you did not request a password reset, no further action is required.</p>
            
            <p>Best regards,<br>{{ config('app.name') }} Team</p>
        </div>

        <div class="email-footer">
            <p>If you're having trouble clicking the "Reset Password" button, copy and paste this URL into your web browser:</p>
            <p style="word-break: break-all;">{{ $resetUrl }}</p>
        </div>
    </div>
</body>
</html> 