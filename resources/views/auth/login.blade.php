<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: "Segoe UI", sans-serif;
            background-color: #f9fafb;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .container {
            background-color: white;
            padding: 2.5rem;
            border-radius: 1.5rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .container h2 {
            font-size: 1.75rem;
            color: #111827;
            margin-bottom: 1.5rem;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        label {
            display: block;
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.5rem;
            text-align: left;
            width: 100%;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            outline: none;
            font-size: 1rem;
            margin-bottom: 0.5rem;
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 2px #c7d2fe;
        }

        .checkbox-group {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            margin: 1rem 0;
            font-size: 0.9rem;
        }

        .checkbox-group label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #4b5563;
        }

        .checkbox-group a {
            color: #4f46e5;
            text-decoration: none;
        }

        .checkbox-group a:hover {
            text-decoration: underline;
        }

        .btn {
            width: 100%;
            background-color: #4f46e5;
            color: white;
            padding: 0.75rem;
            border: none;
            border-radius: 0.5rem;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.2s ease-in-out;
        }

        .btn:hover {
            background-color: #4338ca;
        }

        .error {
            color: red;
            font-size: 0.85rem;
            text-align: left;
            width: 100%;
            margin-top: 0.25rem;
        }

        .register {
            text-align: center;
            font-size: 0.9rem;
            margin-top: 1.5rem;
        }

        .register a {
            color: #4f46e5;
            text-decoration: none;
        }

        .register a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Connexion à votre compte</h2>

    @if (session('status'))
        <div class="success">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div style="width: 100%;">
            <label for="email">Adresse Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
            @error('email')
            <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div style="width: 100%;">
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" required>
            @error('password')
            <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="checkbox-group">
            <label>
                <input type="checkbox" name="remember"> Se souvenir de moi
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}">Mot de passe oublié ?</a>
            @endif
        </div>

        <button type="submit" class="btn">Se connecter</button>
    </form>

    <div class="register">
        Pas encore inscrit ? <a href="{{ route('register') }}">Créer un compte</a>
    </div>
</div>

</body>
</html>
