<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reply from aiZAP Creatives</title>
</head>
<body style="margin:0;padding:0;font-family:Arial,sans-serif;color:#111;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f4f5;padding:24px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:12px;overflow:hidden;">
                    <tr>
                        <td style="background:#111;color:#fff;padding:24px;text-align:center;">
                            <h1 style="margin:0;font-size:24px;">aiZAP Creatives</h1>
                            <p style="margin:8px 0 0;color:#d1d1d6;">Reply to your message</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:24px;">
                            <p style="margin:0 0 16px;">Hi {{ $contactMessage->name }},</p>
                            <p style="margin:0 0 16px;">Thank you for contacting us. Here is our response:</p>
                            <div style="background:#f8f8f9;border-radius:10px;padding:18px;margin-bottom:20px;">
                                <p style="margin:0;white-space:pre-wrap;">{{ $replyMessage }}</p>
                            </div>
                            <p style="margin:0 0 12px;font-weight:700;">Original message</p>
                            <p style="margin:0 0 4px;"><strong>Message:</strong> {{ $contactMessage->message }}</p>
                            <p style="margin:0 0 24px;"><strong>Submitted on:</strong> {{ $contactMessage->created_at?->format('M j, Y g:ia') }}</p>
                            <p style="margin:0;">If you have any follow-up questions, reply to this email and we will get back to you.</p>
                            <p style="margin:24px 0 0;">Best regards,<br>aiZAP Creatives</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="background:#f4f4f5;color:#6b6b6b;padding:16px;font-size:12px;text-align:center;">
                            aiZAP Creatives • <a href="mailto:{{ config('mail.from.address', 'noreply@example.com') }}" style="color:#6b6b6b;text-decoration:none;">{{ config('mail.from.address', 'noreply@example.com') }}</a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
