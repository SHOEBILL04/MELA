<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - MELA System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

<div class="bg-white p-8 rounded-lg shadow-xl w-full max-w-md">
    <h2 class="text-2xl font-bold mb-6 text-center text-indigo-600">Sign in to MELA</h2>

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('login.post') }}" method="POST">
        @csrf
        
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2" for="email">Email Address</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required 
                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 font-semibold mb-2" for="password">Password</label>
            <input type="password" name="password" id="password" required 
                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>

        <div class="flex items-center justify-between mb-6">
            <label class="flex items-center">
                <input type="checkbox" name="remember" class="form-checkbox text-indigo-600">
                <span class="ml-2 text-gray-700">Remember me</span>
            </label>
        </div>

        <button type="submit" class="w-full bg-indigo-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-indigo-700 transition duration-300">
            Log In
        </button>
    </form>
    
    <p class="mt-4 text-center text-gray-600">
        Don't have an account? <a href="{{ route('register') }}" class="text-indigo-600 hover:underline">Register</a>
    </p>
</div>

</body>
</html>
