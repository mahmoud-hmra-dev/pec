<script>
    $("#add_sub_program_btn").on('click',function (event) {
        event.preventDefault();
        let client_id = $('#client_id').val();
        if(client_id === "no_client") {
            new swal('warning', 'Choose Client First!!', 'warning');
            return ;
        }
        let sub_program_index = $(".single_sub_program").length;
        $.ajax({
            url: "{{route("add_sub_program")}}/"+sub_program_index+"/"+client_id,
            success:function (data) {
                $('#sub_program_holder').append(`
                    <div class="single_sub_program card">
                        ${data.sub_program}
                    </div>
                `);
                afterGenerateSubProgram();
                question_generation(sub_program_index)
            },
            error:function (data) {
                new swal('error', 'Something Went Wrong', 'error');
            }
        });
    })

    function afterGenerateSubProgram() {
        $('.follow_treatment_dates').on('change',function (event) {
            event.preventDefault();
            let isChecked = $(this).prop('checked');
            if(isChecked){
                $(this).parent().parent().parent().find('.dates').hide();
                $(this).parent().parent().parent().find('.dates .started_at').val($("#started_at").val())
                $(this).parent().parent().parent().find('.dates .ended_at').val($("#ended_at").val())
            } else {
                $(this).parent().parent().parent().find('.dates').show();

            }
        })

        $('.has_calls').on('change',function (event) {
            event.preventDefault();
            let isChecked = $(this).prop('checked');
            if(!isChecked){
                $(this).parent().parent().parent().find('.has_calls_holder').hide();
                $(this).parent().parent().parent().find('.has_calls_holder input').val(0)
            } else {
                $(this).parent().parent().parent().find('.has_calls_holder').show();

            }
        })

        $('.has_visits').on('change',function (event) {
            event.preventDefault();
            let isChecked = $(this).prop('checked');
            if(!isChecked){
                $(this).parent().parent().parent().find('.has_visits_holder').hide();
                $(this).parent().parent().parent().find('.has_visits_holder input').val(0)
            } else {
                $(this).parent().parent().parent().find('.has_visits_holder').show();

            }
        })

        $('.remove_sub_program_btn').on('click',function (event) {
            event.preventDefault();
            let $this = $(this)
            new swal({
                icon:"warning",
                title: 'Do you want to remove this sub program?',
                showCancelButton: true,
                confirmButtonText: 'Remove',
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    $this.parent().parent().parent().remove();
                    new swal('Removed!', '', 'success')
                }
            })
        })
    }

    function question_generation(sub_program_index) {
        $('#add_question_btn_'+sub_program_index).on('click',function (event) {
            event.preventDefault();
            let questions_counter = $('#questions_holder_'+sub_program_index+" .single_question").length;
            $.ajax({
                url: "{{route("add_question")}}/"+sub_program_index+"/"+questions_counter,
                success:function (data) {
                    $('#questions_holder_'+sub_program_index).append(`
                        <div class="single_question col-md-12 card">
                            ${data.question}
                        </div>
                    `);
                    choices_generation();
                    afterGenerateQuestion();
                },
                error:function (data) {
                    new swal('error', 'Something Went Wrong', 'error');
                }
            });
        })
    }

    function afterGenerateQuestion() {
        $('.close_question_btn').on('click',function (event) {
            event.preventDefault();
            let $this = $(this);
            new swal({
                icon:"warning",
                title: 'Do you want to remove this question?',
                showCancelButton: true,
                confirmButtonText: 'Remove',
            }).then((result) => {
                if (result.isConfirmed) {
                    $this.parent().parent().parent().remove();
                    new swal('Removed!', '', 'success')
                }
            })
        })

        $('.question_type').on('change',function (event) {
            event.preventDefault();
            let $this = $(this);
            let selectedType = $this.find('option:selected').data('type_name');
            if(selectedType === "{{\App\Enums\QuestionTypeEnum::SELECT_ONE}}" || selectedType === "{{\App\Enums\QuestionTypeEnum::SELECT_MANY}}"){
                $this.parent().parent().find('.add_choice_section').show();
            } else {
                $this.parent().parent().find('.add_choice_section').hide();
                $this.parent().parent().find('.choices_holder ').empty();
            }
        })
    }

    function choices_generation() {
        $('.add_choice').unbind( "click" );
        $('.add_choice').on('click',function (event) {
            event.preventDefault();
            let $this = $(this);
            let sub_program_index = $this.data('sub_program_index')
            let question_index = $this.data('question_index')
            let choices_index = $this.parent().parent().parent().find('.choices_holder .single_choice').length;
            $this.parent().parent().parent().find('.choices_holder').append(`
                <div class="single_choice row align-items-center">
                    <div class="form-group col-md-11">
                        <label for="sub_program_${sub_program_index}_question_${question_index}_choice_${choices_index}" class="label-required">Choice</label>
                        <input
                            name="sub_program[${sub_program_index}][questions][${question_index}][choices][${choices_index}]"
                            id="sub_program_${sub_program_index}_question_${question_index}_choice_${choices_index}"
                            type="text"
                            class="form-control"
                        >
                    </div>
                    <div class="form-group col-md-1">
                        <button type="button" class="close_choice text-sm btn btn-sm btn-danger">X</button>
                    </div>
                </div>
            `)
            $('.close_choice').on('click',function (event) {
                event.preventDefault();
                $(this).parent().parent().remove();
            })
        })
    }
</script>
