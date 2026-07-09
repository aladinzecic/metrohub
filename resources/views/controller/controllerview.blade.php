<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MetroHub · Controller</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;1,300&display=swap" rel="stylesheet">
    @vite(['resources/css/mainview.css','resources/css/controllerscan.css','resources/css/controllerdashboard.css', 'resources/css/controllerview.css', 'resources/js/app.js'])
</head>
<body>
    <div class="main-full">

        <div class="main-top">
            <div class="main-top-left">
                <div class="main-top-logo-dot"></div>
                <span class="main-top-logo">MetroHub</span>
            </div>
            <div class="main-top-right">
                <div class="main-top-user-info">
                    <span class="main-top-user-name">{{ auth()->user()->name }}</span>
                    <span class="main-top-user-role">{{ ucfirst(auth()->user()->role) }}</span>
                </div>
                <div class="main-top-avatar">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div class="main-top-divider"></div>
                <form action="/logout" method="POST" style="margin:0;">
                    @csrf
                    <button type="submit" class="main-top-logout">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:16px;height:16px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15m-3 0-3-3m0 0 3-3m-3 3H15" />
                        </svg>
                        Log out
                    </button>
                </form>
            </div>
        </div>

        <div class="main-bottom">
            <div class="ctrl-sidebar">
                <h2 class="ctrl-sb-label">MAIN</h2>


                <div class="{{ request()->is('controller/tickets') ? 'ctrl-sb-item-active' : 'ctrl-sb-item' }}">
                    <a href="/controller/tickets">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0 1 3.75 9.375v-4.5ZM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 0 1-1.125-1.125v-4.5ZM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0 1 13.5 9.375v-4.5Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 6.75h.75v.75h-.75v-.75ZM6.75 16.5h.75v.75h-.75v-.75ZM16.5 6.75h.75v.75h-.75v-.75ZM13.5 13.5h.75v.75h-.75v-.75ZM13.5 19.5h.75v.75h-.75v-.75ZM19.5 13.5h.75v.75h-.75v-.75ZM19.5 19.5h.75v.75h-.75v-.75ZM16.5 16.5h.75v.75h-.75v-.75Z" />
                        </svg>
                        <p>Scan ticket</p>
                    </a>
                </div>





            </div>
            <div class="main-content">
                @yield('main-content')
            </div>
        </div>

    </div>
</body>
</html>