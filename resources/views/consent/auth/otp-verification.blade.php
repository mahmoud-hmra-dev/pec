
@extends('dashboard.auth.layout.auth_layout')

@section('content')
    <div class="login-box">
        <!-- /.login-logo -->
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="{{route('home')}}"><img width="170" src="{{asset('images/logo.png')}}" alt="logo"></a>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success" role="alert"> {{session('success')}}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger" role="alert"> {{session('error')}}
                    </div>
                @endif

                <form action="{{ route('otp.getlogin') }}" method="post">
                    @csrf
                    <input type="hidden" name="uuid" value="{{$uuid}}" />
                    <div class="input-group mb-3">
                        <input id="otp" type="text" class="form-control @error('otp') is-invalid @enderror" name="otp" value="{{ old('otp') }}" required autocomplete="otp" autofocus placeholder="Enter OTP code">


                        <div class="input-group-append">
                            <div class="input-group-text">
                                <i class="fa-duotone fa-message-code"></i>

                            </div>
                        </div>
                        @error('otp')
                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                        @enderror
                    </div>


                    <div class="row">
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>

@endsection
