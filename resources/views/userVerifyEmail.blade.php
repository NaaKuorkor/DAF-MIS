<!DOCTYPE html>
<html>
    <body>
        <h2>Welcome, {{ $user->name }}!</h2>

        <p>Thank you for registering. Please verify your email to complete your registration:</p>

        <p>
            <a href="{{ $verificationLink }}" style="background:#4CAF50;color:white;padding:10px 20px;text-decoration:none;border-radius:5px;">
                Verify My Email
            </a>
        </p>

        <p>If you did not register, please ignore this email.</p>
    </body>
</html>

