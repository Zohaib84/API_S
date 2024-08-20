<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}"> <!-- Link to your CSS file -->
    <style>
        body {
            background: linear-gradient(135deg, #89f7fe 0%, #66a6ff 100%);
            font-family: 'Arial', sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            overflow: hidden;
        }
        .container {
            max-width: 100%;
            padding: 15px;
            box-sizing: border-box;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            background-color: #fff;
            animation: fadeIn 1s ease-out;
        }
        .card-header {
            background-color: #007bff;
            color: #fff;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .card-body {
            padding: 40px;
        }
        .form-control {
            border-radius: 10px;
            height: 50px;
            font-size: 16px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            transition: border-color 0.3s ease;
        }
        .form-control:focus {
            border-color: #007bff;
            box-shadow: none;
        }
        .btn-primary {
            background-color: #007bff;
            border-radius: 10px;
            border: none;
            font-size: 18px;
            padding: 12px 20px;
            width: 100%;
            transition: background-color 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .alert {
            margin-top: 15px;
            display: none;
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">
                        <h3>Login</h3>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-danger" id="errorAlert" role="alert">
                            <!-- Error message will be displayed here -->
                        </div>
                        <div class="mb-3">
                            <input type="email" id="email" class="form-control" placeholder="Enter your email" required>
                        </div>
                        <div class="mb-3">
                            <input type="password" id="password" class="form-control" placeholder="Enter your password" required>
                        </div>
                        <button id="loginButton" class="btn btn-primary">Login</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" 
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" 
    crossorigin="anonymous"></script>
    
    <script>
        $(document).ready(function(){
            $("#loginButton").on('click', function(){
                const email = $("#email").val();
                const password = $("#password").val();
                
                $.ajax({
                    url: '/api/login',
                    type: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({
                        email : email,
                        password : password,
                    }),
                    success: function(response){
                        // Handle success response
                        console.log(response);
                       localStorage.setItem('api_token', response.token);
                       window.location.href = "/allposts";
                    },
                    error: function(response){
                        // Handle error response
                        $("#errorAlert").text('Invalid credentials, please try again.').show();
                    }
                });
            });
        });
    </script>
</body>
</html>
