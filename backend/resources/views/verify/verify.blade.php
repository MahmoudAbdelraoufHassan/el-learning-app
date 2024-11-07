<!DOCTYPE html>
<html>
<head>
    <style>
        /* Add any custom styles here */
    </style>
</head>
<body>
    <h2>Hello, {{ $user->name }}</h2>
    <p>Thank you for registering. Please verify your email by clicking the button below:</p>

    <a href="{{ $verificationUrl }}" style="background-color: #4CAF50; color: white; padding: 10px 20px; text-align: center; text-decoration: none; display: inline-block; border-radius: 5px;">Verify Email</a>

    <p>If you didn’t create an account, please ignore this email.</p>

    <p>Regards,<br>Your Application Team</p>
</body>
</html>