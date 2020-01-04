
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>JABEZ-FNB|LOGIN</title>
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no">
    
    <link rel="apple-touch-icon" sizes="180x180" href="apple-touch-icon.png">
    <link rel="icon" type="image/png" href="favicon-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="favicon-16x16.png" sizes="16x16">
    <link rel="manifest" href="manifest.json">
    <link rel="mask-icon" href="safari-pinned-tab.svg" color="#2c3e50">
    <meta name="theme-color" content="#ffffff">
    {{-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,400italic,500,700"> --}}
    <link rel="stylesheet" href="{{url('css/vendor.min.css')}}">
    <link rel="stylesheet" href="{{url('css/elephant.min.css')}}">
    <link rel="stylesheet" href="{{url('css/login-1.min.css')}}">
  </head>
  <body>
    <div class="login">
      <div class="login-body">
        
        <h3 class="login-heading"> JABEZ<small>FNB</small></h3>
        <div class="login-form">
           <form method="POST" action="{{ route('login') }}">
                        @csrf
            <div class="form-group">
                <input id="text" type="text" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }} text-center" name="email" value="{{ old('email') }}" placeholder="Username" required autofocus autocomplete="off">

                @if ($errors->has('email'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
                @endif
            </div>
            <div class="form-group">
                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }} text-center" placeholder="Password" name="password" required>

                @if ($errors->has('password'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
                @endif
            </div>

                                     
            
            <div class="form-group">
              <button class="btn btn-primary btn-block" type="submit">LOGIN</button>
            </div>


           
          </form>

            
        </div>
      </div>
     
    </div>
    <script src="{{url('js/vendor.min.js')}}"></script>
    <script src="{{url('js/elephant.min.js')}}"></script>
  
  </body>
</html>





