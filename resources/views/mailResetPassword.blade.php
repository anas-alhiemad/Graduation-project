<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Your Password</title>
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
            line-height: 1.6;
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
            margin: 20px 0;
        }
        .footer {
            margin-top: 30px;
            font-size: 14px;
            color: #7f8c8d;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Hello, {{ $name }} 👋</h1>

        <p>We received a request to reset your password. Please use the following code to continue the process:</p>

        <div class="code-box">{{ $token }}</div>

        <p>⚠️ Please do not share this code with anyone for security reasons.</p>

        <p>If you did not request a password reset, you can safely ignore this email.</p>

        <p class="footer">
            Best regards,<br>
            <strong>The Support Team</strong>
        </p>
    </div>
</body>
</html>
