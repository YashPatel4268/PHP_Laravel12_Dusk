<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col items-center">

<div class="bg-white p-8 rounded-lg shadow-md mt-10 w-full max-w-lg text-center">
    <h2 class="text-2xl font-bold mb-6">Welcome, {{ auth()->user()->name }}</h2>

    <p class="mb-6">You have successfully logged in!</p>

    <form method="POST" action="/logout">
        @csrf
        <button type="submit"
                class="bg-red-500 text-white px-6 py-2 rounded hover:bg-red-600 transition">
            Logout
        </button>
    </form>
</div>

</body>
</html>
