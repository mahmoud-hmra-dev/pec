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
                            <form action="{{route('password.email')}}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12 logo-img text-center">
                                        <img src="{{asset('images/logo.png')}}" alt="" class="" >
                                    </div>
                                    <div class="form-group col-md-12 title">
                                        <h2>You forgot your password?</h2>
                                        <span>Here you can easily retrieve a new password.</span>
                                    </div>
                                    <div class="form-group col-md-12 mt-1 mb-1 login-input">
                                        <label  class="required-label" for="email">Your Email</label>
                                        <input type="email" name="email" id="email" value="{{old('email')}}"  class="form-control @error('email') is-invalid @enderror" placeholder="infomail@gmail.com" required>
                                        @error('email')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                    </div>

                                    <div class="col-md-12 mt-4 mb-4 pl-0 pr-0">
                                        <div class="form-group text-center">
                                            <button class="btn btn-primary submit" type="submit">Request new password</button>
                                        </div>

                                    </div>
                                </div>
                                @if (session('status'))
                                    <div class="alert alert-success" role="alert">
                                        {{ session('status') }}
                                    </div>
                                @endif
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

