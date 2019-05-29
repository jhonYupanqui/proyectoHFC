<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name', 'Laravel') }}</title> 
<link rel="stylesheet" type="text/css" href="{{ url('/css/login.css') }}">
</head>
<body>
    <section role="main" class="container-fluid contenedor_login">
        @yield('content_form_login')
    </section>
    
</body>
</html>