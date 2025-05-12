<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Connexion - Cabinet Dentaire</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary: #1e73be;
            --primary-dark: #0d5da6;
            --primary-light: #e8f0fe;
            --accent: #00c9a7;
            --accent-dark: #00a98c;
            --dark: #2d3748;
            --light: #f8fafc;
            --gray: #e2e8f0;
            --white: #ffffff;
            --error: #e53e3e;
            --shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light);
            color: var(--dark);
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .page {
            display: flex;
            flex-direction: column;
            height: 100vh;
            width: 100%;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--primary-light), var(--light));
            position: relative;
            overflow: hidden;
        }

        .page::after {
            content: '';
            position: absolute;
            bottom: -50px;
            left: -50px;
            right: -50px;
            height: 200px;
            background: var(--white);
            transform: rotate(-3deg);
            z-index: 0;
        }

        .dental-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image:
                url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='40' height='40' viewBox='0 0 24 24' fill='%231e73be' opacity='0.05'%3E%3Cpath d='M12,2C7.36,2,4,5.36,4,10c0,2.79,1.35,5.25,3.58,7.07c0.39,0.32,0.83,0.59,1.29,0.82c0.34,0.17,0.64,0.39,0.89,0.65c0.25,0.26,0.45,0.55,0.59,0.88c0.14,0.33,0.23,0.69,0.27,1.06c0.04,0.37,0.06,0.75,0.06,1.13v0.39h2.64v-0.39c0-0.38,0.02-0.75,0.06-1.13c0.04-0.37,0.13-0.73,0.27-1.06c0.14-0.33,0.34-0.62,0.59-0.88c0.25-0.26,0.55-0.48,0.89-0.65c0.46-0.23,0.9-0.5,1.29-0.82C18.65,15.25,20,12.79,20,10C20,5.36,16.64,2,12,2z'%3E%3C/path%3E%3C/svg%3E");
            opacity: 0.07;
            z-index: 0;
        }

        .header {
            padding: 30px 20px;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 2;
        }

        .logo-text {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo-text i {
            color: var(--primary);
        }

        .container {
            display: flex;
            width: 900px;
            max-width: 90%;
            height: 500px;
            max-height: 80vh;
            margin: 0 auto;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: var(--shadow);
            position: relative;
            z-index: 2;
        }

        .left {
            width: 50%;
            padding: 40px;
            background: var(--white);
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
        }

        .left-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
        }

        .welcome-text {
            text-align: center;
            margin-bottom: 30px;
        }

        .welcome-text h2 {
            font-size: 1.8rem;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 10px;
        }

        .welcome-text p {
            color: #64748b;
            font-size: 0.95rem;
        }

        .login-graphic {
            width: 80%;
            max-width: 250px;
            margin-bottom: 30px;
        }

        .right {
            width: 50%;
            padding: 40px;
            background: var(--primary);
            background: linear-gradient(145deg, var(--primary), var(--primary-dark));
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .right h2 {
            font-size: 1.8rem;
            font-weight: 600;
            color: var(--white);
            margin-bottom: 30px;
        }

        .form {
            width: 100%;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-group label {
            color: var(--white);
            font-size: 0.9rem;
            font-weight: 500;
            margin-bottom: 8px;
            display: block;
        }

        .input-group {
            position: relative;
        }

        .input-group i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--white);
            opacity: 0.7;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px 12px 45px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            color: var(--white);
            font-size: 1rem;
            transition: all 0.3s ease;
            outline: none;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 255, 255, 0.4);
            box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.1);
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .remember-me {
            display: flex;
            align-items: center;
        }

        .remember-me input {
            margin-right: 8px;
            accent-color: var(--accent);
        }

        .remember-me label {
            margin-bottom: 0;
            cursor: pointer;
        }

        .forgot-password {
            color: var(--white);
            text-decoration: none;
            font-size: 0.9rem;
            transition: opacity 0.3s;
        }

        .forgot-password:hover {
            opacity: 0.8;
        }

        .btn {
            width: 100%;
            padding: 14px;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            outline: none;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: var(--accent);
            color: var(--white);
        }

        .btn-primary:hover {
            background: var(--accent-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 201, 167, 0.3);
        }

        .btn-primary:active {
            transform: translateY(0);
            box-shadow: none;
        }

        .error-message {
            color: #fecaca;
            font-size: 0.85rem;
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .dental-icon {
            width: 40px;
            height: 40px;
            fill: var(--primary);
        }

        .social-login {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        .social-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.1);
            color: var(--white);
            transition: all 0.3s ease;
        }

        .social-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
                height: auto;
                max-height: none;
                width: 95%;
            }

            .left, .right {
                width: 100%;
                padding: 30px;
            }

            .left {
                order: 2;
                background: var(--white);
                display: none;
            }

            .right {
                order: 1;
                border-radius: 20px;
            }

            .page::after {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="dental-bg"></div>

        <header class="header">
            <div class="logo-text">
                <svg class="dental-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path fill="#1e73be" d="M12,2C7.36,2,4,5.36,4,10c0,2.79,1.35,5.25,3.58,7.07c0.39,0.32,0.83,0.59,1.29,0.82c0.34,0.17,0.64,0.39,0.89,0.65c0.25,0.26,0.45,0.55,0.59,0.88c0.14,0.33,0.23,0.69,0.27,1.06c0.04,0.37,0.06,0.75,0.06,1.13v0.39h2.64v-0.39c0-0.38,0.02-0.75,0.06-1.13c0.04-0.37,0.13-0.73,0.27-1.06c0.14-0.33,0.34-0.62,0.59-0.88c0.25-0.26,0.55-0.48,0.89-0.65c0.46-0.23,0.9-0.5,1.29-0.82C18.65,15.25,20,12.79,20,10C20,5.36,16.64,2,12,2z"/>
                </svg>
                BuDental Service
            </div>
        </header>

        <div class="container">
            <div class="left">
                <div class="left-content">
                    <div class="welcome-text">
                        <h2>Bienvenue!</h2>
                        <p>Ravis de vous revoir dans notre application de gestion dentaire</p>
                    </div>

                    <img class="login-graphic" src="{{ asset('img/logo.png') }}" alt="Logo Cabinet Dentaire">
                </div>
            </div>

            <div class="right">
                <form method="POST" action="{{ route('login') }}" class="form">
                    @csrf
                    <h2>Connexion</h2>

                    <div class="form-group">
                        <label for="email">Adresse Email</label>
                        <div class="input-group">
                            <i class="fas fa-envelope"></i>
                            <input type="email" id="email" name="email" class="form-control" placeholder="Entrez votre email" required autofocus>
                        </div>
                        @error('email')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password">Mot de passe</label>
                        <div class="input-group">
                            <i class="fas fa-lock"></i>
                            <input type="password" id="password" name="password" class="form-control" placeholder="Entrez votre mot de passe" required>
                        </div>
                        @error('password')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="remember-forgot">
                        {{-- <div class="remember-me">
                            <input type="checkbox" id="remember" name="remember">
                            <label for="remember">Se souvenir de moi</label>
                        </div> --}}

                        {{-- @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="forgot-password">
                                Mot de passe oublié?
                            </a>
                        @endif --}}
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-sign-in-alt"></i>
                        Se connecter
                    </button>

                    {{-- <div class="social-login">
                        <a href="#" class="social-btn">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="social-btn">
                            <i class="fab fa-google"></i>
                        </a>
                    </div> --}}
                </form>
            </div>
        </div>
    </div>
</body>
</html>
