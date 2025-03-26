<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Changed</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Poppins', Arial, sans-serif; background-color: #f4f4f4;">
    <table role="presentation" style="width: 100%; max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <tr>
            <td style="padding: 40px 30px;">
                <h1 style="color: #333333; font-size: 24px; margin-bottom: 20px; text-align: center;">Password Changed Successfully</h1>
                
                <p style="color: #666666; font-size: 16px; line-height: 24px; margin-bottom: 30px;">
                    Hi {{ $user->username }},<br><br>
                    Your password has been successfully changed. If you did not make this change, please contact us immediately.
                </p>

                <p style="color: #666666; font-size: 14px; line-height: 24px; text-align: center;">
                    Thank you for using DiaryNote!
                </p>
            </td>
        </tr>
    </table>
</body>
</html>