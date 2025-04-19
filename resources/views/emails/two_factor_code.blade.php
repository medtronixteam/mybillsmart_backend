<!DOCTYPE html>
<html>
<head>
    <title>Your 2FA Code</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
    <div style="background-color: #ffffff; max-width: 600px; margin: 0 auto; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
        <h1 style="color: #333333;">Your 2FA Code</h1>
        <p style="color: #555555;">Please use the following code for 2FA verifications :</p>
        <div style="background-color: #f0f0f0; padding: 10px; text-align: center; font-size: 24px; font-weight: bold; color: #333333; border-radius: 4px;">
            {{ $twoFactorCode }}
        </div>
        <p style="color: #777777; margin-top: 20px;">This code will expire in 10 minutes. If you did not request this, please ignore this email.</p>
    </div>
</body>
</html>
