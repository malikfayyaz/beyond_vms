<!DOCTYPE html>
<html>
<head>
    <title>Your Vendor Account</title>
</head>
<body>
<h1>Hello {{ $vendor->full_name }}</h1>
<p>Your Vendor account has been created successfully.</p>
<p><strong>Email:</strong> {{ $user->email }}</p>
<p><strong>First Name:</strong> {{ $vendor->first_name }}</p>
<p><strong>Last Name:</strong> {{ $vendor->last_name}}</p>
<p><strong>Created At:</strong> {{ $vendor->created_at }}</p>
<p>Thank you for joining us!</p>
</body>
</html>
