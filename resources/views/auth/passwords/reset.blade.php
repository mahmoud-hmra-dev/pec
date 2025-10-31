@extends('auth.layout.main')
@include('auth.layout.extra_meta')
@section('content')
    @include('sweetalert::alert')
    <style>
        #register {
            background: linear-gradient(46.33deg, rgb(33 33 33 / 69%) 0%, rgb(66 66 66 / 7%) 178.98%),
            url('{{ asset('images/login1.png') }}') center/cover no-repeat;

        }
    </style>
    <section id="register" class="d-flex flex-column justify-content-center align-content-center align-items-center"
             style="">
        <div class="container">
            <div class="row align-items-center justify-content-end">
                <div class="col-md-6 bg-register ">
                    <div class="row align-items-center justify-content-center  mb-5">
                        <div class="col-md-12">
                            <form method="POST" action="{{ route('password.update') }}">
                                @csrf
                                <input type="hidden" name="token" value="{{$request->token}}">
                                <div class="row">
                                    <div class="col-md-12 logo-img text-center">
                                        <img src="{{asset('images/logo.png')}}" alt="" class="" >
                                    </div>
                                    <div class="form-group col-md-12 title">
                                        <h2>New Password</h2>
                                    </div>
                                    <div class="form-group col-md-12 mt-1 mb-1 login-input">
                                        <label  class="required-label" for="email">Your Email</label>
                                        <input type="email" name="email" id="email" value="{{old('email')}}"  class="form-control @error('email') is-invalid @enderror" placeholder="infomail@gmail.com" required>
                                        @error('email')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                    </div>
                                    <div class="form-group col-md-12 mt-1 mb-1 login-input">
                                        <label class="required-label" for="password">Password</label>
                                        <input type="password" name="password" id="password" value="{{old('password')}}"  class="form-control @error('password') is-invalid @enderror" placeholder="Enter password" required>
                                        @error('password')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                    </div>
                                    <div class="form-group col-md-12 mt-1 mb-1 login-input">
                                        <label class="required-label" for="password_confirmation">Password confirmation</label>
                                        <input type="password" name="password_confirmation" id="password_confirmation" value="{{old('password_confirmation')}}"  class="form-control @error('password_confirmation') is-invalid @enderror" placeholder="Password confirmation" required>
                                        @error('password_confirmation')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                    </div>
                                    <div class="col-md-12 mt-4 mb-4">
                                        <div class="form-group text-center">
                                            <button class="btn btn-primary submit" type="submit">Request new password</button>
                                        </div>
                                    </div>
                                </div>
                            </form>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@stop
@push('scripts')
    <script>
        $(document).ready(function() {
            $('input.form-control[value!=""]').css('background-color', 'transparent');
        });
    </script>
@endpush




