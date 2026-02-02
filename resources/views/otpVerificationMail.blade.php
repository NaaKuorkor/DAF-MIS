<!DOCTYPE html>
<html>
    <body>
        <h2>Hello, {{ $user->name }}!</h2>
        <p>We received a request to reset your password. Please use the following One-Time Password (OTP) to proceed:</p>

        <div style="text-align:center;margin:30px 0;">
            <div style="font-size:36px;font-weight:bold;letter-spacing:8px;color:#333;">
                {{ $otp }}
            </div>
        </div>

        <p>This OTP will expire in 10 minutes.</p>
        <p>If you did not request a password reset, please ignore this email and your password will remain unchanged.</p>
    </body>
</html>
