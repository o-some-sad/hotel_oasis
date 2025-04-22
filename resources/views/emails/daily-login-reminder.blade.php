<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Reminder</title>
</head>
<body>
<h1>Hello {{ $user->name }},</h1>
<p>This is your daily login reminder!</p>
<p><a href="{{ route('login') }}">Click here to login</a></p>
</body>
</html>
