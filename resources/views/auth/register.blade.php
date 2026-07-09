<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MetroHub · Register</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;1,300&display=swap" rel="stylesheet">
    @vite(['resources/css/login.css', 'resources/js/app.js'])
</head>
<body>
    <div class="login-full">
        <div class="login-main">

            <div class="login-left">
                <h1 class="login-title">Sign Up</h1>

                @if($errors->any())
                    <div class="login-error">
                        {{ $errors->first() }}
                    </div>
                @endif

                @if(session('success'))
                    <div class="login-success">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="/register" method="POST">
                    @csrf

                    <input type="hidden" name="role" value="passenger">

                    <div class="login-field-wrap">
                        <input type="text" name="name" class="login-field" placeholder="Full name" value="{{ old('name') }}">
                    </div>

                    <div class="login-field-wrap">
                        <input type="email" name="email" class="login-field" placeholder="Email address" value="{{ old('email') }}">
                    </div>

                    <div class="login-field-wrap">
                        <input type="password" name="password" class="login-field" placeholder="Password">
                    </div>



                    <button type="submit" class="login-btn">Sign Up</button>
                </form>
            </div>

            <div class="login-right">
                <div class="login-right-inner">
                    <div class="login-right-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:28px;height:28px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                        </svg>
                    </div>
                    <h2 class="login-right-title">Welcome!</h2>
                    <p class="login-right-sub">Already have an account? Sign In now.</p>
                    <a href="/login" class="login-signup-btn">Sign In</a>
                    <a href="/register/role" class="login-role-link">Staff portal →</a>
                </div>
            </div>

        </div>
    </div>
</body>
</html>