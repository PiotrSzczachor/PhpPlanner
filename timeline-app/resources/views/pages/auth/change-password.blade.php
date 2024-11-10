<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@700&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
</head>
<body>
    <div class="login-container">
        <img src="{{ asset('images/logo_AI_Generated.jpg') }}" class="logo" alt="Logo"/>

        <form id="change-password-form" class="form" action="/change-password" method="POST">
            @csrf
            <div class="row">
                <label for="old-password">Old Password:</label>
                <input type="password" name="old_password" id="old-password" placeholder="Old Password" required class="input-field">
            </div>
            <div class="row">
                <label for="new-password">New Password:</label>
                <input type="password" name="new_password" id="new-password" placeholder="New Password" required class="input-field">
            </div>
            <div class="row">
                <label for="new-password-confirm">Confirm New Password:</label>
                <input type="password" name="new_password_confirmation" id="new-password-confirm" placeholder="Confirm New Password" required class="input-field">
            </div>
            <button type="submit" class="submit-btn">Change Password</button>
        </form>
    </div>
</body>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('change-password-form').addEventListener('submit', function(e) {
            e.preventDefault();

            // Get the values of the passwords
            const newPassword = document.getElementById('new-password').value;
            const confirmPassword = document.getElementById('new-password-confirm').value;

            // Check if the new password matches the confirmation password
            if (newPassword !== confirmPassword) {
                alert('The new password and the confirmation password do not match.');
                return; // Stop form submission if passwords don't match
            }

            // If passwords match, proceed with form submission
            const formData = new FormData(this);
            fetch('/change-password', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    alert(data.message);
                    window.location.href = '/';
                } else if (data.error) {
                    alert(data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    });
</script>
