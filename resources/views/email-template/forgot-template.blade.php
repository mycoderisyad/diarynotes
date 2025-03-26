<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Password</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Poppins', Arial, sans-serif; background-color: #f4f4f4;">
    <table role="presentation" style="width: 100%; border-collapse: collapse;">
        <tr>
            <td style="padding: 20px 0; text-align: center; background-color: #ffffff;">
                <img src="{{ asset('image/Diary Notes 1 (1).png') }}" alt="DiaryNote Logo" style="width: 150px;">
            </td>
        </tr>
    </table>

    <table role="presentation" style="width: 100%; max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <tr>
            <td style="padding: 40px 30px;">
                <h1 style="color: #333333; font-size: 24px; margin-bottom: 20px; text-align: center;">Reset Your Password</h1>
                
                <p style="color: #666666; font-size: 16px; line-height: 24px; margin-bottom: 30px;">
                    We received a request to reset your password. Click the button below to create a new password:
                </p>

                <div style="text-align: center; margin-bottom: 30px;">
                    <a href="{{ $actionLink }}" target="_blank" style="display: inline-block; padding: 12px 30px; background-color: #61C0BF; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: 500;">Reset Password</a>
                </div>

                <p style="color: #666666; font-size: 14px; line-height: 24px;">
                    If you didn't request this password reset, please ignore this email or contact support if you have questions.
                </p>
                
                <p style="color: #666666; font-size: 14px; line-height: 24px;">
                    This password reset link will expire in 30 minutes.
                </p>
            </td>
        </tr>
    </table>

    <table role="presentation" style="width: 100%; max-width: 600px; margin: 0 auto;">
        <tr>
            <td style="padding: 20px; text-align: center;">
                <p style="color: #999999; font-size: 12px; margin: 0;">
                    &copy; {{ date('Y') }} DiaryNote. All rights reserved.
                </p>
            </td>
        </tr>
    </table>
</body>
</html>