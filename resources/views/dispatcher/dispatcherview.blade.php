<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;1,300&display=swap" rel="stylesheet">
@vite(['resources/css/mainview.css','resources/css/dispatcherrequests.css','resources/css/dispatcherfleet.css','resources/css/livemapp.css','resources/js/app.js'])
</head>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js" defer></script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    let ticketId = 12345;

    let qrValue = "TICKET-" + ticketId;

    new QRCode(document.getElementById("qrcode"), {
        width: 150,
        height: 150,
        colorDark: "#000000",
        colorLight: "#F5F2ED",
            correctLevel: QRCode.CorrectLevel.L

    }).makeCode(qrValue);

});
</script>
<body>
    <div class="main-full">
<div class="main-top">
    <div class="main-top-left">
        <div class="main-top-logo-dot"></div>
        <span class="main-top-logo">MetroHub</span>
    </div>
    <div class="main-top-right">
        <div class="main-top-user-info">
            <span class="main-top-user-name">{{auth()->user()->name}}</span>
            <span class="main-top-user-role">Dispatcher</span>
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
        <div class="sidebar">
            <h2>NAVIGATION</h2>
            <div class="{{ request()->is('home') ? 'side-bar-item-active' : 'side-bar-item' }}">
                <a href="/dispatcher/fleet">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M6 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                    <path d="M18 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                    <path d="M4 17h-2v-11a1 1 0 0 1 1 -1h14a5 7 0 0 1 5 7v5h-2m-4 0h-8" />
                    <path d="M16 6l1.5 7h-13.5" />
                    <path d="M2 10l15 0" />
                    <path d="M7 6l0 4" />
                    <path d="M12 6l0 4" />
                </svg>
                    <p>Fleet</p>
                </a>
            </div>
            <div class="{{ request()->is('/dispatcher/livemap') ? 'side-bar-item-active' : 'side-bar-item' }}">
                <a href="/dispatcher/livemap">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                </svg>
                    <p>Live Map</p>
                </a>
            </div>


            <h2>OPERATIONS</h2>

            <div class="{{ request()->is('history') ? 'side-bar-item-active' : 'side-bar-item' }}">
                <a href="/dispatcher/requests">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                </svg>
                    <p>Staff Requests</p>
                </a>
            </div>

        </div>
        <div class="main-content">
            @yield('main-content')
        </div>
    </div>
    </div>
    @stack('scripts')
</body>
</html>