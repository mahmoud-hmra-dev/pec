<script>
    var BASE_URL = '{{url('')}}' + '/';
    var _token = '{{csrf_token()}}';
</script>
<script src="{{asset('js/app.js')}}"></script>
<script src="{{asset('js/general.js')}}"></script>
<script type="text/javascript" src="{{asset("js/jquery.repeater.js")}}" defer></script>
<script type="text/javascript" src="{{asset("js/select2.min.js")}}"></script>
<script type="text/javascript" src="{{asset("js/summernote-bs4.min.js")}}"></script>
<script type="text/javascript" src="{{asset("js/bootstrap.bundle.min.js")}}"></script>


<script src="{{asset('fronted/vendor/aos/aos.js')}}"></script>
<script src="{{asset('fronted/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('fronted/vendor/glightbox/js/glightbox.min.js')}}"></script>
<script src="{{asset('fronted/vendor/isotope-layout/isotope.pkgd.min.js')}}"></script>
<script src="{{asset('fronted/vendor/swiper/swiper-bundle.min.js')}}"></script>
<script src="{{asset('fronted/vendor/waypoints/noframework.waypoints.js')}}"></script>
<script src="{{asset('fronted/vendor/php-email-form/validate.js')}}"></script>
<script src="{{asset('tinymce/tinymce.min.js')}}"></script>
<script src="{{asset('fronted/js/main.js')}}"></script>
<script src="{{asset('fronted/js/sweetalert2.min.js')}}"></script>


<script src="https://www.google.com/recaptcha/api.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>

<script>
    $(document).ready(function() {
        $('.custom-file-input').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);
        });
    });
</script>

