@extends('layouts.auth')

@section('content')
    <form method="POST" class="max-w-sm mx-auto mt-20 bg-white p-6 shadow">
        @csrf

        <h1 class="text-xl mb-4">Login</h1>

        <input name="email" type="email" placeholder="Email" class="w-full mb-3">
        <input name="password" type="password" placeholder="Password" class="w-full mb-3">

        <button class="w-full bg-black text-white py-2">
            Login
        </button>
    </form>
@endsection
