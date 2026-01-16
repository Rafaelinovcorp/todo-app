<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'To-Do App')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen text-gray-900">

    <header class="bg-white shadow">
        <div class="max-w-4xl mx-auto px-4 py-4 flex items-center justify-between">
            <h1 class="text-xl font-semibold">
                📝 To-Do App
            </h1>
        </div>
    </header>

    <main class="max-w-4xl mx-auto px-4 py-6">
        @yield('content')
    </main>

</body>
</html>
