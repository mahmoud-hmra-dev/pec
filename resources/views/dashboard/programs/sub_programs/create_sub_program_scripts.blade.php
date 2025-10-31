<script>
    $(document).ready(function() {



        $('.has_calls').change(function() {
            if($(this).is(":checked")) {
                $('.has_calls_holder').show();
            } else {
                $('.has_calls_holder').hide();
            }
        });
        $('.has_FOC').change(function() {
            if($(this).is(":checked")) {
                $('.has_foc_holder').show();
            } else {
                $('.has_foc_holder').hide();
            }
        });

        $('.has_visits').change(function() {
            if($(this).is(":checked")) {
                $('.has_visits_holder').show();
            } else {
                $('.has_visits_holder').hide();
            }
        });
    });
</script>
