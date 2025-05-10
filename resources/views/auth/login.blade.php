<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login - Dental Services</title>
    <style>
        @import url('https://rsms.me/inter/inter-ui.css');
        ::selection {
            background: #2D2F36;
        }
        ::-webkit-selection {
            background: #2D2F36;
        }
        ::-moz-selection {
            background: #2D2F36;
        }
        body {
            background: white;
            font-family: 'Inter UI', sans-serif;
            margin: 0;
        }
        .page {
            background: #e2e2e5;
            display: flex;
            flex-direction: column;
            height: 100vh;
            place-content: center;
            width: 100vw;
        }
        .container {
            display: flex;
            height: 320px;
            margin: 0 auto;
            width: 640px;
        }
        .left {
            background: white;
            height: calc(100% - 40px);
            top: 20px;
            position: relative;
            width: 50%;
        }
        .login {
            font-size: 50px;
            font-weight: 900;
            margin: 50px 40px 40px;
        }
        .right {
            background: #474A59;
            box-shadow: 0px 0px 40px 16px rgba(0,0,0,0.22);
            color: #F1F1F2;
            position: relative;
            width: 50%;
        }
        .form {
            margin: 40px;
            position: absolute;
        }
        label {
            color: #c2c2c5;
            display: block;
            font-size: 14px;
            height: 16px;
            margin-top: 20px;
            margin-bottom: 5px;
        }
        input {
            background: transparent;
            border: 0;
            color: #f2f2f2;
            font-size: 20px;
            height: 30px;
            line-height: 30px;
            outline: none !important;
            width: 100%;
        }
        .header {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            height: 100px;
            font-size: 30px;
            font-weight: 900;
            font-style: italic;
            color: #474A59;
            text-decoration: underline;
        }
        input[type="submit"] {
            background: #474A59;
            color: #F1F1F2;
            cursor: pointer;
            font-size: 20px;
            height: 40px;
            line-height: 40px;
            margin-top: 20px;
            width: 100%;
        }
    </style>
</head>
<body>
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="page">
            <div class="header text-center">Dental Services</div>
            <div class="container">
                <div class="left">
                    <div class="login">
                        <img src="{{ asset('img/logo.png') }}" width="100%" height="200px" alt="">
                    </div>
                </div>
                <div class="right">
                    <div class="form">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" required autofocus>

                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" required>

                        <input type="submit" id="submit" value="Login" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded cursor-pointer">
                    </div>
                </div>
            </div>
        </div>
    </form>
</body>
</html>
