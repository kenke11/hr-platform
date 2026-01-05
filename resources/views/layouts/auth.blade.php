<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Auth')</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-100 flex items-center justify-center">

<div class="w-full max-w-md">
    {{-- Logo / App name --}}
    <div class="text-center mb-6">
        <h1 class="text-2xl font-bold">
            HR Platform
        </h1>
        <p class="text-sm text-gray-500">
            Sign in to your account
        </p>
    </div>

    {{-- Auth card --}}
    <div class="bg-white shadow rounded-lg p-6">
        {{-- Validation errors --}}
        @if ($errors->any())
            <div class="mb-4 text-sm text-red-600">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Page content --}}
        @yield('content')
    </div>
</div>

</body>
</html>
