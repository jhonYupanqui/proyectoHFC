@extends('layouts.loginTemplate')

@section('content_form_login')
<div class="row">
    <div class="d-none d-sm-none d-md-block  col-md-7 col-lg-8  content-image-login-sis">
    <img src="{{ url('/images/portada_login.jpg') }}" class="img-content-login"/>
    </div>
    <div class="col-sm-12 col-md-5 col-lg-4 content-form-login-sis d-flex align-items-end">
        <div class="cuadro-content-login">
            <h2 class="text-center">System Tower</h2>
            <p class="txt-bn-login text-center">Bienvenido al <br/>  CENTRO DE CONTROL PRINCIPAL</p>
            <i class="linea-login"></i>
            <p class="txt-bn-login ingresa-txt text-center"><strong>INGRESA AL SISTEMA</strong></p>
            <form method="POST" action="{{ route('login') }}" aria-label="{{ __('Login') }}" id="form_login">
                    @csrf 
                    
                    <div class="form-group"> 
                        <label for="name" class="col-form-label">Usuario</label>
                        <input id="name" type="name" class="form-control" name="name" required autofocus>
                    </div>

                    <div class="form-group">
                        <label for="password" class="col-form-label">{{ __('Password') }}</label>
                        <input id="password" type="password" class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
                        
                        @if ($errors->has('password'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div> 
                    <div class="form-group">
                        <div id="respuesta-server-login"></div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn  btn-sistema" id="ingresar">
                            {{ __('Ingresar') }}
                        </button>
                    </div>
                    <div class="form-group texto-form-login">
                        <a class="btn btn-link" href="{{ route('password.request') }}">
                                {{ __('Olvido su contraseña?') }}
                        </a> 
                    </div>
                </form>
        </div>
    </div>
</div>
 
@endsection
