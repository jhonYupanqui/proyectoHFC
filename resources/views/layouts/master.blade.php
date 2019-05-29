
<!DOCTYPE html>
<html  lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

   <!-- CSRF Token -->
   <meta name="csrf-token" content="{{ csrf_token() }}">

   <title>{{ config('app.name', 'Laravel') }}</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="css/icofont.min.css">
  <link rel="stylesheet" href="css/app.css">
  
  @yield('estilos')
  @yield('scripts-header')

</head>
    <body class="hold-transition sidebar-mini" >
        <div class="wrapper">

            @include('layouts.partials.navbar')
            
            @include('layouts.partials.aside-left') {{-- Aside --}}

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                    <div class="col-sm-6">
                        @yield('title-container')
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            @section('ruta-navegacion-container') 
                                <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                            @show
                        
                        </ol>
                    </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
                </div>
                <!-- /.content-header -->

                <!-- Main content -->
                <section class="content" id="app">
                <div class="container-fluid">
                    @yield('content')
                </div>
                </section>
                <!-- /.content -->
            </div>
            <!-- /.content-wrapper -->
            @include('layouts.partials.footer')

            <!-- Control Sidebar -->
            <aside class="control-sidebar control-sidebar-light">
                @yield('aside-right')
            </aside>
            <!-- /.control-sidebar -->
        
        
        </div>
        <!-- ./wrapper -->
    
    <script src="/js/app.js"></script>
    @yield('scripts-footer')

    </body>
</html>
