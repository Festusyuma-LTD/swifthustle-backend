<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>

<body>
<h3>Dear User,</h3>
<br/>
 Your email address is {{$resetPassword->email}}, Kindly click the link below to reset your password
<br/>



<a href="{{url('reset-password', $resetPassword->remember_token)}}">Reset Password</a>


</body>

</html>
