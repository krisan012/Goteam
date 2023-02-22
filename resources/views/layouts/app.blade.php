<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <link href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" rel="stylesheet">
    
    @vite(['resources/js/app.js', 'resources/css/app.css'])

</head>

<body class="antialiased">
    <div id="app"></div>
</body>

</html>
