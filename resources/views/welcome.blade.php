<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- <title>Kiosk</title> -->

    <!-- <script src="{{ asset('js/app.js') }}" defer></script> -->
    <script src="{{ mix('/js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">


    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

</head>

<body id="body_main">
    <div class="flex-center position-ref full-height">
        @if(Auth::user())
        <div id="index" data='{{ Auth::user()->id }}'></div>
        @else
        <div id="index" data=''></div>
        @endif

    </div>
</body>
<!-- The core Firebase JS SDK is always required and must be listed first -->
<script src="https://www.gstatic.com/firebasejs/7.15.5/firebase-app.js"></script>

<!-- TODO: Add SDKs for Firebase products that you want to use
     https://firebase.google.com/docs/web/setup#available-libraries -->
<script src="https://www.gstatic.com/firebasejs/7.15.5/firebase-analytics.js"></script>

<script>
    // Your web app's Firebase configuration
    var firebaseConfig = {
        apiKey: "AIzaSyBZxjBZiC19Y7BWfkTH8-xeW1N-allvMAI",
        authDomain: "kaiosk.firebaseapp.com",
        databaseURL: "https://kaiosk.firebaseio.com",
        projectId: "kaiosk",
        storageBucket: "kaiosk.appspot.com",
        messagingSenderId: "915460093447",
        appId: "1:915460093447:web:d21adf986eb7f107d65ca2",
        measurementId: "G-XSTP6BLLG2"
    };
    // Initialize Firebase
    firebase.initializeApp(firebaseConfig);
    firebase.analytics();
</script>

</html>