<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>To-Do App</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

    <div class="bg-white p-8 rounded shadow text-center max-w-md">
        <h1 class="text-2xl font-bold mb-4">📝 To-Do App</h1>

        <p class="text-gray-600 mb-6">
            Organiza as tuas tarefas de forma simples.
        </p>

        <div class="flex justify-center gap-4">
            <a href="{{ route('login') }}"
               class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Login
            </a>

            <a href="{{ route('register') }}"
               class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">
                Registar
            </a>
        </div>
    </div>

</body>
</html>
