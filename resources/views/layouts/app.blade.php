<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'HR Dashboard')</title>
</head>
<body class="bg-gray-100 min-h-screen">

<div class="flex">
    <aside class="w-64 bg-white shadow min-h-screen flex flex-col">
        <div class="p-4 font-bold text-lg border-b">
            HR Platform
        </div>

        <nav class="px-4 py-4 space-y-2 flex-1">
            <a href="{{ route('dashboard') }}" class="block">
                Dashboard
            </a>

            @if(auth()->user()->canAccessAllCompanies())
                <a href="{{ route('companies.index') }}" class="block">
                    Companies
                </a>
            @endif
        </nav>

        {{-- Logout --}}
        <div class="p-4 border-t">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button
                    type="submit"
                    class="w-full text-left text-red-600 hover:text-red-800"
                >
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 p-6">
        @yield('content')
    </main>
</div>

</body>
</html>
