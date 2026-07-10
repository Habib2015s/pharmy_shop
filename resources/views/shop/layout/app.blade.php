<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title','داروخانه')</title>

    @vite(['resources/css/app.css','resources/js/app.js'])

    @stack('styles')
</head>

<body>

@yield('content')

@stack('scripts')

</body>
</html>
