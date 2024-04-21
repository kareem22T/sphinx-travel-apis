<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sphinx Travel Api</title>
</head>
<body>
    {{-- <form action=""  id="resetPasswordForm" style="background: white;border: 0 none;border-radius: 3px;box-shadow: 0 0 15px 1px rgba(0, 0, 0, 0.4);padding: 20px 30px;box-sizing: border-box;width: 80%;margin: 0 10%;position: relative;max-width: 500px;width: 100%;margin: auto;display: flex;flex-direction: column;gap: 1rem;border-radius: 10px;margin-top: 2rem;">
        <img src="https://sphinx-travel.com/wp-content/uploads/2021/12/Sphinx-for-websit-1.png" alt="" style="margin: auto;" width="170">
        <h1 style="font-size: 23px;font-family: arial;margin: auto;color: #0e026d;">Reset Your Password</h1>
        <input id="password" name="new_password" type="password" placeholder="Old Password" style="padding: 15px;border: 1px solid #ccc;border-radius: 3px;margin-bottom: 10px;width: 100%;box-sizing: border-box;color: #2C3E50;font-size: 13px;">
        <input id="password_confirmation" name="new_password_confirmation" type="password" placeholder="New Password Confirmation" style="padding: 15px;border: 1px solid #ccc;border-radius: 3px;margin-bottom: 10px;width: 100%;box-sizing: border-box;color: #2C3E50;font-size: 13px;">
        <button style="width: 100px;background: #8dc645;font-weight: bold;color: white;border: 0 none;border-radius: 1px;cursor: pointer;padding: 10px;margin: 10px 5px;text-decoration: none;font-size: 14px;margin: auto;width: 100%;">Reset</button>
    </form> --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('resetPasswordForm').addEventListener('submit', function (event) {
                event.preventDefault(); // Prevent default form submission

                // Get form data
                var formData = {
                    password: document.getElementById('password').value,
                    password_confirmation: document.getElementById('password_confirmation').value
                };

                // Create and configure XMLHttpRequest object
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'reset_password_endpoint.php'); // Replace with your endpoint
                xhr.setRequestHeader('Content-Type', 'application/json');

                // Handle response
                xhr.onload = function () {
                    if (xhr.status === 200) {
                        // On success, hide form and show success message
                        document.getElementById('resetForm').style.display = 'none';
                        document.getElementById('successMessage').style.display = 'block';
                    } else {
                        console.error(xhr.responseText);
                        alert('Error: Failed to reset password. Please try again.');
                    }
                };

                // Handle network errors
                xhr.onerror = function () {
                    console.error('Request failed');
                    alert('Error: Failed to reset password. Please try again.');
                };

                // Send AJAX request
                xhr.send(JSON.stringify(formData));
            });
        });
        window.location.href = 'https://sphinx-travel.com/'
    </script>
</body>
</html>
