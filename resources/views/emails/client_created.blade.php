<!DOCTYPE html>
<html>
<head>
    <title>Your Client Account</title>
</head>
<body>
<h1>Hello {{ $client->full_name }}</h1>
<p>Your client account has been created successfully.</p>
<p><strong>Email:</strong> {{ $user->email }}</p>
<p><strong>Organization:</strong> {{ $client->organization }}</p>
<p><strong>Business Name:</strong> {{ $client->business_name }}</p>
<p><strong>Created At:</strong> {{ $client->created_at }}</p>
<p>Thank you for joining us!</p>
</body>
</html>
