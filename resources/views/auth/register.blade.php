@extends('auth.layout.main')
@include('auth.layout.extra_meta')
@section('content')
    @include('sweetalert::alert')
    <style>
        #hero {
            background: url('{{asset('fronted/img/home/img-home.png')}}');
            margin-bottom: 0px;
        }

    </style>

    <section id="hero" class="d-flex flex-column justify-content-center align-items-center">
        <div class="container aos-init aos-animate" data-aos="fade-in">
            <div class="row d-flex flex-column justify-content-center align-items-center">
                <div class="col-md-8">
                    <h2>Need Help...</h2>
                    <h1>Being Life Saver For Someone</h1>
                    <div class="d-flex align-items-center">
                        <a href="{{route('register.nonprofits')}}" class="btn-get-started scrollto">Be a nonprofite</a>
                        <a href="{{route('register.clients')}}" class="btn-get-started scrollto">Signup</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@push('scripts')

    <script>
        $(document).ready(function () {
            $('.select2').each(function () {
                $(this).select2({
                    width: '100%',
                })
            })

        });

    </script>
@endpush

