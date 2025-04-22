<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome to Our System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f8;
            padding: 30px;
        }
        .container {
            background-color: #ffffff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            max-width: 600px;
            margin: auto;
        }
        h1 {
            color: #2c3e50;
        }
        p {
            color: #34495e;
            font-size: 16px;
        }
        .code-box {
            background-color: #ecf0f1;
            padding: 15px;
            border-radius: 8px;
            font-size: 20px;
            font-weight: bold;
            text-align: center;
            letter-spacing: 2px;
            color: #2980b9;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome, {{ $name }} 👋</h1>

        <p>We're excited to let you know that you’ve been successfully added to our system.</p>

        <p>Please use the following code to verify your account:</p>

        <p class="warning">⚠️ Do not give this code to anyone.</p>
        
        <div class="code-box">{{ $verification_token }}</div>

        <p>If you didn’t request this, please ignore this email.</p>

        <p style="margin-top: 30px;">Best regards,<br><strong>The Team</strong></p>
    </div>
</body>
</html>
