<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>{{ config('app.name', 'Echoes of war') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('images/logo.png')}}">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font/css/materialdesignicons.min.css" rel="stylesheet">
    <meta name="theme-color" content="#FFF">

    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <link rel="manifest" href="{{asset('manifest.webmanifest')}}">

    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="EchoesOfWar">
    <link rel="apple-touch-icon" href="{{asset('images/logo.png')}}">
    <style>
        body {
            background-color: #F9FAFF !important;
        }


        .logo {
            padding-top: 10px;
            width: 160px;
            height: 80px;
        }

        .v-card {
            border-radius: 0.8rem !important;
            margin-top: 0.5rem !important;
            margin-bottom: 0.5rem !important;
        }

        .loader {
            border: 10px solid #f3f3f3;
            border-top: 10px solid #000000;
            border-radius: 50%;
            width: 80px;
            height: 80px;
            animation: spin 1s linear infinite;
            margin-top: 20px;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        #loading-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            z-index: 9999;
            transition: opacity 0.5s ease, transform 0.5s ease;
        }

        #loading-screen.hide {
            opacity: 0;
            transform: scale(1.05);
            pointer-events: none;
        }
    </style>
</head>

<body>
    <div id="loading-screen" style="
        position:fixed; 
        top:0; left:0; 
        width:100vw; height:100vh; 
        background:white; 
        display:flex; 
        align-items:center; 
        justify-content:center; 
        z-index:9999;
        flex-direction: column;
    ">
        <img src="/images/ECHOS OF WAR.png" style="width: min(80vw, 500px); height: auto; margin-bottom: 20px;">
        <div class="loader"></div>
    </div>
    <div id="app">
        <vc-principal></vc-principal>
    </div>
    <script type="text/javascript" src="{{ mix('js/app.js') }}"></script>
    <script>
        window.addEventListener('load', function () {
            const loader = document.getElementById('loading-screen');
            loader.classList.add('hide');
            setTimeout(() => loader.remove(), 500);
        });
    </script>
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then((registro) => {
                        console.log('Service worker registrado:', registro.scope);
                    })
                    .catch((error) => {
                        console.error('Error al registrar el service worker:', error);
                    });
            });
        }
    </script>
</body>


</html>