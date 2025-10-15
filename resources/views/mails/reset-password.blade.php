<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Password</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #f8f9fa;
            line-height: 1.6;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .email-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 30px 20px;
            text-align: center;
            color: white;
        }

        .email-header h1 {
            font-size: 28px;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .email-body {
            padding: 40px 30px;
        }

        .email-body p {
            margin-bottom: 20px;
            font-size: 16px;
            color: #555;
        }

        .reset-button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            padding: 14px 30px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 16px;
            margin: 20px 0;
            text-align: center;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
            transition: all 0.3s ease;
        }

        .reset-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
        }

        .alternative-link {
            margin-top: 30px;
            padding-top: 30px;
            border-top: 1px solid #e9ecef;
            font-size: 14px;
            color: #666666;
        }

        .alternative-link p {
            margin: 10px 0;
        }

        .link-text {
            word-break: break-all;
            color: #667eea;
            text-decoration: none;
        }

        .email-footer {
            padding: 20px 30px;
            background-color: #f5f7fb;
            text-align: center;
            font-size: 14px;
            color: #777;
            border-top: 1px solid #eaeaea;
        }

        .email-footer p {
            margin-bottom: 10px;
        }

        .expiry-notice {
            background-color: #fff9e6;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 25px 0;
            border-radius: 4px;
        }


        @media (max-width: 600px) {
            .email-body {
                padding: 30px 20px;
            }

            .email-header h1 {
                font-size: 24px;
            }
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="email-header">
            <h1>Reset Your Password</h1>
        </div>

        <div class="email-body">
            <p>Hello {{ $user->name }},</p>

            <p>We received a request to reset your password for your account. If you didn't make this request, you can
                safely ignore this email.</p>

            <p>To reset your password, click the button below:</p>

            <div style="text-align: center;">
                <a href="{{ $url }}" class="reset-button">Reset Password</a>
            </div>

            <div class="expiry-notice">
                <strong>⏰ Important:</strong> This verification link will expire in 60 minutes for security purposes.
            </div>

                <div class="alternative-link">
                    <p>If the button above doesn't work, copy and paste the following link into your browser:</p>
                    <p><a href="{{ $url }}" class="link-text">{{ $url }}</a></p>
                </div>

            <p>If you have any questions, feel free to contact our support team.</p>

            <p>Best regards,<br>The YourApp Team</p>
        </div>

        <div class="email-footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            <p>This is an automated email, please do not reply to this message.</p>
        </div>
    </div>
</body>

</html>
