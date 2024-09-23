<!DOCTYPE html>
<html>
<head>
    <title>Your Client Account</title>
</head>
<body>
<h1>Hello {{ $client->first_name }} {{ $client->last_name }}</h1>
<p>Your client account has been created successfully.</p>
<p><strong>Email:</strong> {{ $client->email }}</p>
<p><strong>Password:</strong> {{ $password }}</p>
<p>Thank you for joining us!</p>
</body>
</html>
