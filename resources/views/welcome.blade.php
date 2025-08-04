<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Page d'accueil | EduPortal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-white min-h-screen flex items-center justify-center px-4">

<div class="max-w-2xl w-full bg-gray-50 border border-gray-200 rounded-3xl shadow-xl p-10 text-center">
    <h1 class="text-4xl font-bold text-gray-900 mb-4">Bienvenue sur <span class="text-blue-600">EduPortal</span></h1>
    <p class="text-lg text-gray-600 mb-8">Gérez votre établissement scolaire avec une solution simple, intuitive et rapide.</p>

    @if (Route::has('login'))
        <div class="flex justify-center gap-4 flex-wrap">
            @auth
                <a href="{{ url('/dashboard') }}"
                   class="px-6 py-2.5 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition-all duration-200 shadow-sm">
                    Accéder au tableau de bord
                </a>
            @else
                <a href="{{ route('login') }}"
                   class="px-6 py-2.5 bg-gray-800 text-white rounded-full hover:bg-gray-900 transition-all duration-200 shadow-sm">
                    Se connecter
                </a>


            @endauth
        </div>
    @endif
</div>

</body>
</html>
