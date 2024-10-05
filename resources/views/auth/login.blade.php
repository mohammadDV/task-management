@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="w-full max-w-sm mx-auto mt-16">
    <h2 class="text-center text-2xl font-bold m-2">Login to Your Account</h2>
    <p id="loginResponse" class="m-2 text-center text-red-500"></p>
    <form id="loginForm" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                Email Address
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="email" type="email" placeholder="Email">
        </div>
        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                Password
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" id="password" type="password" placeholder="Password">
        </div>
        <div class="flex items-center justify-between">
            <button type="button" onclick="loginUser()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Login
            </button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    // Login function using Axios
    function loginUser() {
        axios.post('/login', {
            email: document.getElementById('email').value,
            password: document.getElementById('password').value
        })
        .then(response => {
            localStorage.setItem('auth_token', response.data.token);
            window.location.href = '/dashboard';
        })
        .catch(error => {
            document.getElementById('loginResponse').innerText = error.response.data.message;
        });
    }

    // Get the token
    const token = localStorage.getItem('auth_token');

    // Check whether the user is logged in or not
    if (token) {
        window.location.href = '/dashboard';
    }
</script>
@endsection
