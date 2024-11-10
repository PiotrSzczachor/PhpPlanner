<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@700&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
</head>
<body>
    <div class="login-container">
        <img src="{{ asset('images/logo_AI_Generated.jpg') }}" class="logo"/>

        <form class="form" action="{{ route('login') }}" method="POST">
            @csrf
            <div class="row">
                <input type="email" id="email" name="email" placeholder="E-mail" required>
            </div>
            <div class="row">
                <input type="password" id="password" name="password" placeholder="Password" required>
            </div>
            
            <button type="submit">Login</button>
        </form>
        <br>
        <p>Don't have an account? <a href="{{ route('register') }}">Register here</a> Or continue as a <a href="{{ route('timeline') }}">Guest</a></p>
    </div>
    
</body>
</html>
