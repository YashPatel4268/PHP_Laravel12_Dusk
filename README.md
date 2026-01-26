# PHP_Laravel12_Dusk


## Project Overview

This is a Laravel 12 authentication project with browser automation testing using Laravel Dusk.
The project demonstrates user registration, login, dashboard access, logout functionality, and automated UI testing.

This project is designed especially for freshers to understand:

Laravel authentication flow

MVC architecture

Blade templating

Middleware usage

Browser testing with Laravel Dusk

## Project Features

User Registration

User Login

Authentication using Laravel Auth

Protected Dashboard (Auth Middleware)

Logout functionality

Tailwind CSS UI (via CDN)

Laravel Dusk Browser Test

MySQL Database Integration


## Technologies Used :

PHP 8+

Laravel 12

MySQL

Laravel Dusk

Blade Template Engine

Tailwind CSS (CDN)

Composer & NPM

---



# Project SetUp

---



## STEP 1: Create New Laravel 12 Project

### Run Command :

```
composer create-project laravel/laravel PHP_Laravel12_Dusk "12.*"


```

### Go inside project:

```
cd PHP_Laravel12_Dusk


```

Make sure Laravel 12 is installed successfully.





## STEP 2: Setup Database

### Open .env

```

APP_NAME=Laravel
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost



DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=php_laravel12_dusk
DB_USERNAME=root
DB_PASSWORD=

```
### Create database:

```
php_laravel12_dusk

```


### Then run:

```
php artisan migrate

```


## STEP 3: Install Laravel Dusk

### Run this command:

```
composer require laravel/dusk --dev

```

### Now install Dusk files:

```
php artisan dusk:install

```
This creates:

tests/Browser
tests/DuskTestCase.php



## STEP 4: Open ExampleTest File

### Open this file : tests/Browser/ExampleTest.php

```

<?php

namespace Tests\Browser;

use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ExampleTest extends DuskTestCase
{
   public function test_basic_example(): void
   {
       $user = User::factory()->create();

       $this->browse(function (Browser $browser) use ($user) {
           $browser->loginAs($user)
                   ->visit('/dashboard')
                   ->assertPathBeginsWith('/dashboard');
       });
   }
}

```


## STEP 5: Run Dusk Again

### Run:

```
php artisan dusk

```

### Output Explanation:

```
PASS  Tests\Browser\ExampleTest
basic example  2.33s

```

PASS → Test passed successfully

Tests\Browser\ExampleTest → File that ran

basic example → The specific test method

2.33s → Time it took





## STEP 6: Setup Routes

### Add your web routes in routes/web.php:

```

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/register', [AuthController::class, 'showRegisterForm']);
Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', [AuthController::class, 'showLoginForm']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/dashboard', [AuthController::class, 'dashboard'])->middleware('auth');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');


```



## STEP 7: Create the controllers

### Open terminal inside your project:

```
php artisan make:controller AuthController

```


### app/Http/Controllers/AuthController.php:

```

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Show registration form
    public function showRegisterForm()
    {
        return view('register');
    }

    // Register user
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Hash is very important
        ]);

        return redirect('/login')->with('success', 'Registration successful! Login now.');
    }

    // Show login form
    public function showLoginForm()
    {
        return view('login');
    }

    // Login user
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return redirect()->intended('/dashboard');
        }

        return back()->with('error', 'Invalid credentials');
    }

    // Dashboard
    public function dashboard()
    {
        return view('dashboard');
    }

    // Logout
    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}


```



## STEP 8: Blade Views

### Login Page: resources/views/login.blade.php

```

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

<div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
    <h2 class="text-2xl font-bold mb-6 text-center">Login</h2>

    @if(session('error'))
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="/login" class="space-y-4">
        @csrf
        <input type="email" name="email" placeholder="Email"
               class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        <input type="password" name="password" placeholder="Password"
               class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        <button type="submit"
                class="w-full bg-blue-500 text-white py-2 rounded hover:bg-blue-600 transition">
            Login
        </button>
    </form>

    <p class="mt-4 text-center text-gray-600">
        Don't have an account? <a href="/register" class="text-blue-500 hover:underline">Register</a>
    </p>
</div>

</body>
</html>


```

### Dashboard Page: resources/views/dashboard.blade.php

```

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


```

### Register Page: resources/views/register.blade.php

```

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


```


## STEP 9: Install Tailwind via npm

### Open your Laravel 12 project folder in the terminal:

```
cd D:\xampp\htdocs\PHP_Laravel12_Dusk

npm install -D tailwindcss postcss autoprefixer

npx tailwindcss init -p

```

tailwind.config.js → Tailwind configuration file.

postcss.config.js → tells Laravel Mix/PostCSS to process Tailwind CSS.



## STEP 10: Configure Tailwind

### In tailwind.config.js, update the content paths so Tailwind scans your Blade templates:

```

/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}


```


## STEP 11: Compile CSS using Laravel Mix

### Laravel 12 uses Vite by default, so check vite.config.js. Then, run:

```
npm install
npm run dev

```

This compiles resources/css/app.css into public/build/assets/app.css.

The npm run dev command automatically watches for changes while developing.



## STEP 12: Running the App

### Finally run the development server:

```
php artisan serve

```

### Visit in browser:

```
http://localhost:8000

```


## So You can see this type Output:

### Register Page:


<img width="1913" height="959" alt="Screenshot 2026-01-22 124801" src="https://github.com/user-attachments/assets/993d7c6f-ea9c-4866-8f40-80800b852bef" />


### Login Page:


<img width="1919" height="968" alt="Screenshot 2026-01-22 124727" src="https://github.com/user-attachments/assets/865a7320-d511-4d97-9019-cd8b9b79ebea" />


### Dashboard Page:


<img width="1919" height="960" alt="Screenshot 2026-01-22 124739" src="https://github.com/user-attachments/assets/b505934b-98c3-468a-a922-730b2d126204" />



---


# Project Folder Structure:

```


PHP_Laravel12_Dusk/
│
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── AuthController.php     # Handles register, login, dashboard, logout
│   │
│   └── Models/
│       └── User.php                   # Default Laravel user model
│
├── bootstrap/
│
├── config/
│
├── database/
│   ├── factories/
│   │   └── UserFactory.php            # Used by Laravel Dusk test
│   ├── migrations/
│   │   └── xxxx_xx_xx_create_users_table.php
│   └── seeders/
│
├── public/
│
├── resources/
│   ├── views/
│   │   ├── login.blade.php             # Login page
│   │   ├── register.blade.php          # Register page
│   │   └── dashboard.blade.php         # Dashboard page
│
├── routes/
│   └── web.php                         # All web routes
│
├── storage/
│
├── tests/
│   ├── Browser/
│   │   └── ExampleTest.php             # Laravel Dusk browser test
│   └── DuskTestCase.php
│
├── .env                                # Environment configuration
├── artisan                            # Laravel CLI
├── composer.json
├── package.json
├── phpunit.xml
└── README.md                          # Project documentation


```
