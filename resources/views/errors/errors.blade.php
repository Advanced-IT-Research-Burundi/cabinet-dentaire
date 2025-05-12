<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Cabinet Dentaire</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #007bff;
            --secondary: #6dc0ea;
            --accent: #00c9a7;
            --dark: #2d3748;
            --light: #f8fafc;
            --danger: #e53e3e;
            --warning: #ffc107;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
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

        .error-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            flex-grow: 1;
        }

        .error-code {
            font-size: 8rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 0.5rem;
            line-height: 1;
        }

        .error-title {
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            color: var(--dark);
        }

        .error-message {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            max-width: 600px;
        }

        .error-illustration {
            max-width: 100%;
            height: auto;
            margin: 2rem 0;
        }

        .btn {
            display: inline-block;
            background-color: var(--primary);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0, 123, 255, 0.1);
        }

        .btn:hover {
            background-color: #0069d9;
            transform: translateY(-2px);
            box-shadow: 0 6px 8px rgba(0, 123, 255, 0.2);
        }

        .btn-secondary {
            background-color: #fff;
            color: var(--primary);
            border: 1px solid var(--primary);
            margin-left: 1rem;
        }

        .btn-secondary:hover {
            background-color: #f8f9fa;
            color: #0069d9;
        }

        .action-buttons {
            margin-top: 2rem;
        }

        .dental-icon {
            width: 80px;
            height: 80px;
            fill: var(--primary);
            margin-bottom: 2rem;
        }

        .wave-decoration {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            overflow: hidden;
            line-height: 0;
            transform: rotate(180deg);
            z-index: -1;
        }

        .wave-decoration svg {
            position: relative;
            display: block;
            width: calc(100% + 1.3px);
            height: 150px;
        }

        .wave-decoration .shape-fill {
            fill: rgba(0, 123, 255, 0.1);
        }

        footer {
            background-color: var(--primary);
            color: white;
            text-align: center;
            padding: 1rem;
            margin-top: auto;
        }

        footer p {
            margin: 0;
        }

        @media (max-width: 768px) {
            .error-code {
                font-size: 6rem;
            }

            .error-title {
                font-size: 1.5rem;
            }

            .error-message {
                font-size: 1rem;
            }

            .action-buttons {
                display: flex;
                flex-direction: column;
                gap: 1rem;
            }

            .btn-secondary {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <div class="wave-decoration">
        <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
            <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" class="shape-fill"></path>
        </svg>
    </div>

    <div class="error-container">
        <svg class="dental-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
            <path d="M392.2,291.2c-18.9,0-34.1-15.3-34.1-34.1c0-18.9,15.3-34.1,34.1-34.1c18.9,0,34.1,15.3,34.1,34.1 C426.4,275.9,411.1,291.2,392.2,291.2z M119.8,291.2c-18.9,0-34.1-15.3-34.1-34.1c0-18.9,15.3-34.1,34.1-34.1 c18.9,0,34.1,15.3,34.1,34.1C153.9,275.9,138.6,291.2,119.8,291.2z M256,389.7c-18.9,0-34.1-15.3-34.1-34.1 c0-18.9,15.3-34.1,34.1-34.1c18.9,0,34.1,15.3,34.1,34.1C290.1,374.4,274.9,389.7,256,389.7z M256,192.5 c-18.9,0-34.1-15.3-34.1-34.1c0-18.9,15.3-34.1,34.1-34.1c18.9,0,34.1,15.3,34.1,34.1C290.1,177.2,274.9,192.5,256,192.5z M424.9,143.4c-34.7-39.3-84.9-61.9-137.4-61.9h-62.9c-52.5,0-102.7,22.6-137.4,61.9C49.7,185.5,32.6,242.3,40.2,298.1l9.4,69.4 c5.5,40.3,40.2,70.2,81,70.2h250.7c40.8,0,75.5-29.9,81-70.2l9.4-69.4C479.4,242.3,462.3,185.5,424.9,143.4z M435.1,293.9 l-9.4,69.4c-3.8,27.5-27.4,47.9-55.3,47.9H141.6c-27.9,0-51.5-20.4-55.3-47.9l-9.4-69.4c-6.8-49.9,7.3-100.2,38.8-137.7 c29.9-35.4,73.4-55.8,118.9-55.8h62.9c45.5,0,89,20.3,118.9,55.8C427.8,193.7,441.9,244,435.1,293.9z"></path>
        </svg>

        <div class="error-code">@yield('code')</div>
        <h1 class="error-title">@yield('title')</h1>
        <div class="error-message">@yield('message')</div>

        <div class="action-buttons">
            <a href="{{ url('/') }}" class="btn">Retour à l'accueil</a>
            <a href="{{ url()->previous() }}" class="btn btn-secondary">Page précédente</a>
        </div>
    </div>

    <footer>
        <p>&copy; {{ date('Y') }} Cabinet Dentaire. Tous droits réservés.</p>
    </footer>
</body>
</html>
