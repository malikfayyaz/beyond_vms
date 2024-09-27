<!DOCTYPE html>
<html>
<head>
    <title>Your Admin Account</title>
</head>
<body>
<h1>Hello {{ $admin->full_name }}</h1>
<p>Your Admin account has been created successfully.</p>
<p><strong>Email:</strong> {{ $user->email }}</p>
<p><strong>First Name:</strong> {{ $admin->first_name }}</p>
<p><strong>Last Name:</strong> {{ $admin->last_name}}</p>
<p><strong>Created At:</strong> {{ $admin->created_at }}</p>
<p>Thank you for joining us!</p>
</body>
</html>
