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
                            <form  method="POST" action="{{ route('verification.send') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12 logo-img text-center">
                                        <img src="{{asset('images/logo.png')}}" alt="" class="" >
                                    </div>
                                    <div class="form-group col-md-12 title">
                                        <h2 >Verify Your Email Address</h2>
                                    </div>

                                    <div class="col-md-12 mt-4 mb-4">
                                        <div class="form-group text-center">
                                            <button class="btn btn-primary submit" type="submit">{{ __('click here to request another') }}</button>
                                        </div>

                                    </div>

                                </div>
                            </form>
                            @if (session()->has('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                                <p class="white" style="color: #FFFFFF">{{ __('Before proceeding, please check your email for a verification link.') }}
                                </p>
                                <p class="white" style="color: #FFFFFF">{{ __('If you did not receive the email click again to resend email.') }}</p>
                            @endif
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



