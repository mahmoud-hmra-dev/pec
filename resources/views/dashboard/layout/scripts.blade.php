<script>
    var BASE_URL = '{{url('')}}' + '/';
    var _token = '{{csrf_token()}}';
</script>

<script src="{{asset('js/app.js')}}"></script>
<script src="{{asset('js/general.js')}}"></script>
<script type="text/javascript" src="{{asset("js/jquery.repeater.js")}}" defer></script>
<script type="text/javascript" src="{{asset("js/select2.min.js")}}"></script>
<script src="{{asset("js/apexcharts/apexcharts.min.js")}}"></script>
<script src="{{asset("js/chart.js/chart.umd.js")}}"></script>
<script src="{{asset("js/echarts/echarts.min.js")}}"></script>
<script>
    $(document).ready(function() {
        $('.custom-file-input').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);
        });
        $('#safety_reports').repeater({
            initEmpty: false,
            defaultValues: {
                'text-input': 'foo'
            },
            show: function () {
                $('.custom-file-input').on('change', function() {
                    let fileName = $(this).val().split('\\').pop();
                    $(this).next('.custom-file-label').addClass("selected").html(fileName);
                });
                $(this).slideDown();
            },
            hide: function (deleteElement) {
                if(confirm('Do you want to delete this item?')) {
                    $(this).slideUp(deleteElement);
                }
            },
            ready: function (setIndexes) {

            },
            isFirstItemUndeletable: true
        });
        $('#choices').repeater({
            initEmpty: false,
            defaultValues: {
                'text-input': 'foo'
            },
            show: function () {

                $(this).slideDown();
            },
            hide: function (deleteElement) {
                if(confirm('Do you want to delete this item?')) {
                    $(this).slideUp(deleteElement);
                }
            },
            ready: function (setIndexes) {

            },
            isFirstItemUndeletable: true
        });
        $('#documents').repeater({
            initEmpty: false,
            defaultValues: {
                'text-input': 'foo'
            },
            show: function () {
                $('.custom-file-input').on('change', function() {
                    let fileName = $(this).val().split('\\').pop();
                    $(this).next('.custom-file-label').addClass("selected").html(fileName);
                });
                $(this).slideDown();
            },
            hide: function (deleteElement) {
                if(confirm('Do you want to delete this item?')) {
                    $(this).slideUp(deleteElement);
                }
            },
            ready: function (setIndexes) {

            },
            isFirstItemUndeletable: true
        });
        var service_types = $("#service_types").select2();
        $('#certificates_list').repeater({
            initEmpty: false,
            defaultValues: {
                'text-input': 'foo'
            },
            show: function () {
                $('.custom-file-input').on('change', function() {
                    let fileName = $(this).val().split('\\').pop();
                    $(this).next('.custom-file-label').addClass("selected").html(fileName);
                });
                $(this).slideDown();
            },
            hide: function (deleteElement) {
                if(confirm('Do you want to delete this item?')) {
                    $(this).slideUp(deleteElement);
                }
            },
            ready: function (setIndexes) {

            },
            isFirstItemUndeletable: true
        });
        $('#countryServicesProvider').repeater({
            initEmpty: false,
            defaultValues: {
                'text-input': 'foo'
            },
            show: function () {
                $(this).slideDown();
            },
            hide: function (deleteElement) {
                if(confirm('Do you want to delete this item?')) {
                    $(this).slideUp(deleteElement);
                }
            },
            ready: function (setIndexes) {

            },
            isFirstItemUndeletable: true
        });
    });
</script>
