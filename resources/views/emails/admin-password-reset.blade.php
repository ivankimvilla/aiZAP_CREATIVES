<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Reset your password</title>
</head>
<body style="font-family: Arial, sans-serif; background:#f5f7fb; padding:24px; color:#111827;">
    <div style="max-width:560px; margin:0 auto; background:#ffffff; border-radius:12px; padding:24px; box-shadow:0 10px 30px rgba(0,0,0,0.05);">
        <h2 style="margin-top:0;">Reset your admin password</h2>
        <p>Hello {{ $user->name ?? 'there' }},</p>
        <p>We received a request to reset your admin password. Use the link below to continue:</p>
        <p><a href="{{ $resetUrl }}" style="display:inline-block; padding:12px 18px; background:#111827; color:#ffffff; text-decoration:none; border-radius:8px;">Reset password</a></p>
        <p>This link expires in 1 minute.</p>
        <p>If you did not request this, you can ignore this email.</p>
    </div>
</body>
</html>
