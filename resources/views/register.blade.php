<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

<div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
    <h2 class="text-2xl font-bold mb-6 text-center">Register</h2>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="/register" class="space-y-4">
        @csrf
        <input type="text" name="name" placeholder="Name"
               class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        <input type="email" name="email" placeholder="Email"
               class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        <input type="password" name="password" placeholder="Password"
               class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        <input type="password" name="password_confirmation" placeholder="Confirm Password"
               class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        <button type="submit"
                class="w-full bg-green-500 text-white py-2 rounded hover:bg-green-600 transition">
            Register
        </button>
    </form>

    <p class="mt-4 text-center text-gray-600">
        Already have an account? <a href="/login" class="text-blue-500 hover:underline">Login</a>
    </p>
</div>

</body>
</html>
