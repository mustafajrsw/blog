<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Alert</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f7f9fc;
            padding: 20px 0;
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
            background: linear-gradient(135deg, #ff6b6b 0%, #ff8e8e 100%);
            padding: 30px 20px;
            text-align: center;
            color: white;
        }

        .email-header h1 {
            font-size: 28px;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .email-header p {
            font-size: 16px;
            opacity: 0.9;
        }

        .email-body {
            padding: 40px 30px;
        }

        .email-body p {
            margin-bottom: 20px;
            font-size: 16px;
            color: #555;
        }

        .login-details {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
            border-left: 4px solid #ff6b6b;
        }

        .detail-row {
            display: flex;
            margin-bottom: 12px;
            padding-bottom: 12px;
            border-bottom: 1px solid #eaeaea;
        }

        .detail-row:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }

        .detail-label {
            font-weight: 600;
            width: 120px;
            color: #555;
        }

        .detail-value {
            flex: 1;
            color: #333;
        }

        .security-actions {
            display: flex;
            gap: 15px;
            margin: 25px 0;
        }

        .action-button {
            display: inline-block;
            text-decoration: none;
            padding: 12px 25px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 15px;
            text-align: center;
            flex: 1;
            transition: all 0.3s ease;
        }

        .reset-btn {
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
            color: white;
            box-shadow: 0 4px 10px rgba(13, 110, 253, 0.3);
        }

        .reset-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(13, 110, 253, 0.4);
        }

        .support-btn {
            background-color: #f8f9fa;
            color: #495057;
            border: 1px solid #dee2e6;
        }

        .support-btn:hover {
            background-color: #e9ecef;
        }

        .security-tips {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
        }

        .security-tips h3 {
            color: #856404;
            margin-bottom: 15px;
            font-size: 18px;
        }

        .security-tips ul {
            padding-left: 20px;
            color: #856404;
        }

        .security-tips li {
            margin-bottom: 8px;
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

        .logo {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .device-icon {
            font-size: 18px;
            margin-right: 8px;
        }

        @media (max-width: 600px) {
            .email-body {
                padding: 30px 20px;
            }

            .email-header h1 {
                font-size: 24px;
            }

            .security-actions {
                flex-direction: column;
            }

            .detail-row {
                flex-direction: column;
            }

            .detail-label {
                width: 100%;
                margin-bottom: 5px;
            }
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="email-header">
            <div class="logo">YourApp</div>
            <h1>New Login Detected</h1>
            <p>We noticed a login to your account</p>
        </div>

        <div class="email-body">
            <p>Hello <strong>{{$user->name}}</strong>,</p>

            <p>We noticed a recent login to your account. If this was you, you can safely ignore this email. If you
                don't recognize this activity, please secure your account immediately.</p>

            <div class="login-details">
                <h3 style="margin-bottom: 15px; color: #ff6b6b;">Login Details</h3>

                <div class="detail-row">
                    <span class="detail-label">Date & Time:</span>
                    <span class="detail-value">{{$loginInfo['login_time']}}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">IP Address:</span>
                    <span class="detail-value">{{$loginInfo['ip']}}
                    </span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Device:</span>
                    <span class="detail-value">
                        <span class="device-icon">
                            {{$loginInfo['device'] === 'Mobile' ? '📱' : ($loginInfo['device'] === 'Tablet' ? '📟' : '💻')}}
                        </span>
                        {{$loginInfo['device']}} - {{$loginInfo['user_agent']}}
                    </span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Status:</span>
                    <span class="detail-value" style="color: #28a745;">
                        ✅ Login Successful
                    </span>
                </div>
            </div>

            <div class="security-actions">
                <a href="{{ $resetLink }}" class="action-button reset-btn">Reset Password</a>
                {{-- <a href="{{ url('support') }}" class="action-button support-btn">Contact Support</a> --}}
            </div>

            <div class="security-tips">
                <h3>🔒 Security Tips</h3>
                <ul>
                    <li>Use a strong, unique password for your account</li>
                    <li>Enable two-factor authentication for extra security</li>
                    <li>Be cautious of phishing emails and suspicious links</li>
                    <li>Regularly review your account activity</li>
                </ul>
            </div>

            <p>If you have any questions or concerns, our support team is here to help.</p>

            <p>Best regards,<br>The {{ config('app.name') }} Team</p>
        </div>

        <div class="email-footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            <p>This is an automated email, please do not reply to this message.</p>
            <p>
                <a href="#" style="color: #0d6efd; text-decoration: none;">Privacy Policy</a> |
                <a href="#" style="color: #0d6efd; text-decoration: none;">Security Center</a> |
                <a href="#" style="color: #0d6efd; text-decoration: none;">Unsubscribe</a>
            </p>
        </div>
    </div>
</body>

</html>
