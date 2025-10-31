@extends('frontend.layout.main')
@include('frontend.layout.extra_meta')
@section('content')
    @include('sweetalert::alert')
    <style>

        #register {
            background: linear-gradient(46.33deg, rgb(33 33 33 / 69%) 0%, rgb(66 66 66 / 7%) 178.98%),
            url('{{ asset('fronted/img/auth/bg-img-register.png') }}') center/cover no-repeat;

        }

    </style>
    <section id="register" class="d-flex flex-column justify-content-center align-content-center align-items-center"
             style="">
        <div class="container">
            <div class="row ">
                <div class="col-md-6 logo-img">
                    <img src="{{asset('images/logo.svg')}}" alt="" class="" >
                </div>
                <div class="col-md-6 bg-register ">
                    <div class="row align-items-center justify-content-center mt-5 mb-5">
                        <div class="col-md-12">
                            <form class="form-horizontal m-t-30" id="formRegister" action="{{ route('register') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="role" value="{{\App\Enums\RoleEnum::CLIENT}}">

                                <div class="row">
                                    <div class="form-group col-md-12 title">
                                        <span>LET'S GET YOU STARTED</span>
                                        <h2 >Create an Account</h2>
                                    </div>

                                    <div class="form-group col-md-12 mt-1 mb-1">
                                        <label class="required-label">First NAME</label>
                                        <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name') }}" placeholder="First NAME" >
                                        @error('first_name')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                    </div>
                                    <div class="form-group col-md-12 mt-1 mb-1">
                                        <label class="required-label">Last NAME</label>
                                        <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name') }}" placeholder="Last NAME" >
                                        @error('last_name')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                    </div>
                                    <div class="form-group col-md-12 mt-1 mb-1">
                                        <label  class="required-label" for="email">Your Email</label>
                                        <input type="email" name="email" id="email" value="{{old('email')}}"  class="form-control @error('email') is-invalid @enderror" placeholder="infomail@gmail.com" required>
                                        @error('email')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                    </div>
                                    <div class="form-group col-md-12 mt-1 mb-1">
                                        <label class="required-label" for="title">Your Phone</label>
                                        <input type="text" name="phone" id="phone" value="{{old('phone')}}"  class="form-control @error('phone') is-invalid @enderror" placeholder="+514818645">
                                        @error('phone')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                    </div>

                                    <div class="form-group col-md-12 mt-1 mb-1" >
                                        <label class="required-label">Country</label>
                                        <select class="form-control select2 @error('country_id') is-invalid @enderror"  id="country_id" name="country_id" >
                                            @foreach($countries as $country)
                                                <option value="{{$country->id}}" {{old('country_id') == $country->id ? 'selected':'' }}
                                                data-phone="{{$country->phone_extension}}">{{$country->name}}</option>
                                            @endforeach
                                        </select>
                                        @error('country_id')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                    </div>
                                    <div class="form-group col-md-12 mt-1 mb-1">
                                        <label class="required-label" for="password">Password</label>
                                        <input type="password" name="password" id="password" value="{{old('password')}}"  class="form-control @error('password') is-invalid @enderror" placeholder="Enter password" required>
                                        @error('password')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                    </div>
                                    <div class="form-group col-md-12 mt-1 mb-1">
                                        <label class="required-label" for="password_confirmation">Confirm Password</label>
                                        <input name="password_confirmation" type="password" class="form-control @error('password_confirmation') is-invalid @enderror" value="" placeholder="Confirm Password" required>
                                        @error('password_confirmation')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                    </div>

                                    <div class="col-md-12 mt-4 mb-4">
                                        <div class="form-group text-center">
                                            <button class="btn btn-primary submit" type="submit">GET STARTED</button>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mt-4 mb-4">
                                        <div class="form-group text-center">
                                            <h4>Already have an account? <a href="{{route('login')}}">LOGIN</a></h4>
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
        var mobile = document.querySelector("#mobile");
        var phone = document.querySelector("#phone");


        var phone_iti = window.intlTelInput(phone, {
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
            preferredCountries: ["lb"],
            separateDialCode: true,
            autoPlaceholder: "aggressive",
            nationalMode: false,
        });
        var mobile_iti = window.intlTelInput(mobile, {
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
            preferredCountries: ["lb"],
            separateDialCode: true,
            autoPlaceholder: "aggressive",
            nationalMode: false,
        });



        document.querySelector("#formRegister").addEventListener("submit", function (event) {
            phone.value =  phone_iti.getNumber();
            mobile.value =  mobile_iti.getNumber();
        });
    </script>
    <script>
        $(document).ready(function() {
            $('.select2').each(function() {
                $(this).select2({
                    width: '100%',
                })
            });
        });
    </script>
@endpush
