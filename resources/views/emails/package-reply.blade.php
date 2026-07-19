<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Package Request Reply</title>
</head>
<body style="font-family: Arial, sans-serif; color: #111; line-height: 1.5; margin: 0; padding: 0;">
    <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="background: #f4f4f5; width: 100%; padding: 24px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" role="presentation" style="background: #ffffff; border-radius: 16px; overflow: hidden;">
                    <tr>
                        <td style="padding: 24px; text-align: center; background: #111; color: #fff;">
                            <h1 style="margin: 0; font-size: 24px;">aiZAP Creatives</h1>
                            <p style="margin: 8px 0 0; color: #dcdcdc;">Your package request reply</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 24px;">
                            <p style="margin: 0 0 16px;">Hi {{ $packageRequest->name }},</p>
                            <p style="margin: 0 0 16px;">Thank you for your package request. Below is our response:</p>
                            <div style="padding: 18px; background: #f8f8f9; border-radius: 12px; margin-bottom: 16px;">
                                <p style="margin: 0; white-space: pre-wrap;">{{ $replyMessage }}</p>
                            </div>
                            <p style="margin: 0 0 12px;">Request details:</p>
                            <ul style="margin: 0 0 24px 16px; color: #444;">
                                <li><strong>Package:</strong> {{ $packageRequest->package }}</li>
                                <li><strong>Message:</strong> {{ $packageRequest->message }}</li>
                            </ul>
                            <p style="margin: 0 0 24px;">If you have any follow-up questions, feel free to reply to this email.</p>
                            <p style="margin: 0;">Best regards,<br>aiZAP Creatives</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 16px; text-align: center; font-size: 12px; color: #999; background: #f4f4f5;">
                            aiZAP Creatives • <a href="mailto:{{ config('mail.from.address', 'noreply@example.com') }}" style="color: #999; text-decoration: none;">{{ config('mail.from.address', 'noreply@example.com') }}</a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
