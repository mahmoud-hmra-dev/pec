@extends('dashboard.layout.main')
@section('content')
    <div class="p-4">
        <div class="card p-2">
            <div class="row">
                <div class="col-12"><h4>Sub Program Details</h4></div>
                <div class="col-3">
                    <p><strong>Name : </strong></p>
                </div>
                <div class="col-3">
                    <p>{{$sub_program->name}}</p>
                </div>
                <div class="col-3">
                    <p><strong>Program Initial : </strong></p>
                </div>
                <div class="col-3">
                    <p>{{$sub_program->program_initial}}</p>
                </div>
                <div class="col-3">
                    <label class="required-label" for="sub_program_patient_id">Patients</label>
                    <select class="form-control" id="sub_program_patient_id" name="sub_program_patient_id">
                        <option value="">Your option</option>
                        @foreach($sub_program_patients as $single)
                            <option value="{{$single->id}}" @if(old('sub_program_patient_id') == $single->id) selected @endif>{{$single->patient->user->first_name}} {{$single->patient->user->last_name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label for="start_date_filter" class="label-required">Start at</label>
                        <input name="start_date_filter" id="start_date_filter" type="datetime-local" class="form-control" value="{{old('start_date_filter',$sub_program->start_date)}}">
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label for="finish_date_filter" class="label-required">End at</label>
                        <input name="finish_date_filter" id="finish_date" type="datetime-local" class="form-control" value="{{old('finish_date_filter',$sub_program->finish_date_filter)}}">
                    </div>
                </div>
                <div class="form-group col-3">
                    <label class="d-block">Start at</label>
                    <select id="start_at_filter" name="start_at_filter" class="form-control custom-select">
                        <option value="">All</option>
                        <option value="1" @if(old('start_at_filter') == '1') selected @endif>Yes</option>
                        <option value="0" @if(old('start_at_filter') == '0') selected @endif>No</option>
                    </select>
                </div>
            </div>
            <div class="m-2 text-right">
                <button class="edit btn btn-danger" id="resetFilter">Reset</button>
            </div>
        </div>

        <div class="card p-2" id="timeline_holder" >
            <div id="timeline">

            </div>
        </div>
        <div class="card p-2" id="item_details" style="display: none">

        </div>
    </div>
    @include('dashboard.sub_programs.form',['activity_types'=>$activity_types,'sub_program_patients'=>$sub_program_patients,'sub_programs'=>$sub_programs,'nurses'=>$nurses,'questions'=>$questions])
@stop
@push('extra_styles')
    <link rel="stylesheet" href="{{asset('css/vis.min.css')}}">
@endpush
@push('scripts')
    <script type="text/javascript" src="{{asset("js/vis.min.js")}}"></script>
    <script>
        $(function () {
            const start_date = $("#start_date_filter").val();
            const finish_date = $("#finish_date_filter").val();
            const start_at = $("#start_at_filter").val();
            const sub_program_patient_id = '';
            loadVisits(start_at,start_date,finish_date,sub_program_patient_id);
        });
        function updateVisitList() {
            const start_date = $("#start_date_filter").val();
            const finish_date = $("#finish_date_filter").val();
            const sub_program_patient_id = $("#sub_program_patient_id").val();
            const start_at = $("#start_at_filter").val();
            loadVisits(start_at,start_date,finish_date,sub_program_patient_id);
        }
        $("#start_date_filter, #finish_date_filter, #sub_program_patient_id , #start_at_filter").change(updateVisitList);


        $("#resetFilter").click(function() {
            $("#start_date_filter").val("{!! $sub_program->start_date !!}");
            $("#finish_date_filter").val("{!! $sub_program->finish_date !!}");
            $("#sub_program_patient_id").val("");
            $("#start_at_filter").val("");
            updateVisitList();
        });

        $('.add-form').on('submit', function (event) {
            SaveItemTimeLine(event, this, $(this).attr('action'));
            $('.overlay').remove();
            $('#item_details').hide();
        });


        const openEdit = (data) => {
            let $modal = $('#add-modal');
            let $visit = $('#visit');
            let $questions = $('#questions');
            let $form = $('.add-form');
            $form[0].reset();
            $form.append('<input type="hidden" name="_method" value="PUT">');
            $form.attr('action', `dashboard/patients/${data.patient_id}/visits/${data.model_id}`);
            $modal.find('.modal-title').text(`Edit visit`);
            $modal.find('#afm_btnSaveIt').text(`Update`);
            clearErrors($modal);
            clearErrors($visit);
            clearErrors($questions);
            _fill($visit, data);
            console.log(data);
            $questions.empty();
            let Items = '';
            const questions_items = @json($questions);
            questions_items.forEach((item, index) => {
                let type = item.type.name;
                let questionData = data.question_data.find(item1 => item1.question_id === item.id);
                Items += `<div class="form-group col-md-12">
                <label class="d-block">${item.question}</label>`;
                switch(type) {
                    case 'Select Many':
                        item.choices.forEach((option, optionIndex) => {
                            Items += `<input type="checkbox" id="questions[${index}][content]_${optionIndex}" name="questions[${index}][content][]"
                      value="${option.choice}" ${questionData && questionData.content.includes(option.choice) ? 'checked' : ''} />
                  <label for="outsource_template"> ${option.choice} </label>`;
                        });
                        break;
                    case 'Select One':
                        item.choices.forEach((option, optionIndex) => {
                            Items += `<input type="radio" id="questions[${index}][content]" name="questions[${index}][content]" value="${option.choice}"
                      ${questionData && questionData.content === option.choice ? 'checked' : ''} />
                  <label for="outsource_template"> ${option.choice} </label>`;
                        });
                        break;
                    case 'Yes/No':
                        Items += `<input type="radio" id="questions[${index}][content]" name="questions[${index}][content]" value="Yes"
                    ${questionData && questionData.content === 'Yes' ? 'checked' : ''} />
                <label for="outsource_template"> Yes </label>
                <input type="radio" id="questions[${index}][content]" name="questions[${index}][content]" value="No"
                    ${questionData && questionData.content === 'No' ? 'checked' : ''} />
                <label for="outsource_template"> No </label>`;
                        break;
                    case 'Free Text':
                        Items += `<textarea name="questions[${index}][content]" id="questions[${index}][content]" class="form-control">${questionData ? questionData.content : ''}</textarea>`;
                        break;
                }
                Items += `<input type="hidden" name="questions[${index}][id]" value="${questionData ? questionData.id : '' }"><input type="hidden" name="questions[${index}][question_id]" value="${item.id}"></div>`;
            });
            $("#meeting_display").hide();
            const type_visit = data.type_visit;
            if (type_visit === "{{\App\Enums\VisitTypeEnum::Online}}") {
                $("#meeting_display").show();
                console.log(data.meeting);
                //$modal.find('input[type="text"][name="meeting"]').val(data.meeting);
            } else {
                $("#meeting_display").hide();
                $modal.find('input[type="text"][name="meeting"]').val(null);
            }
            $questions.append(Items);
            $modal.modal('show').removeClass('out').addClass('in');
        };

        function loadVisits(start_at,start_date, finish_date,sub_program_patient_id) {
            const url = `{{ route('sub_programs.timeline.index', $sub_program->id) }}`;
            const data = {
                start_at,
                start_date,
                finish_date,
                sub_program_patient_id,
            };

            $.get(url, data)
                .done(function (data) {
                    const items = new vis.DataSet([...data.visits, ...data.calls]);
                    const container = document.getElementById('timeline');
                    container.innerHTML = "";
                    const options = {};
                    const timeline = new vis.Timeline(container, items, options);
                    timeline.on("click", function (properties) {
                        if(properties.item){
                            const item = items.get(properties.item);
                            const itemCard = $("#item_details");
                            itemCard.empty().show().append(`
            <div class="row">
              <div class="col-md-12"><h4>Details</h4></div>

              <div class="col-md-2"><strong>Type : </strong></div>
              <div class="col-md-2"><p>${item.group}</p></div>

              <div class="col-md-2"><strong>Should started at : </strong></div>
              <div class="col-md-2"><p>${item.start}</p></div>
            <div class="col-md-2"><strong>Is Started : </strong></div>
              <div class="col-md-2"><p>${item.start_at ? item.start_at : 'Not yet!'}</p></div>
              <div class="col-md-3"><a class="edit btn btn-xs btn-primary mb-4" href="javascript:" onclick="openEdit(${JSON.stringify(item).replace(/"/g, '&quot;')})"><i class="mdi mdi-plus-circle-outline"></i>Edit</a></div>
            </div>
          `);
                        }
                    });
                })
                .fail(function (xhr) {
                    console.log(xhr.responseText);
                });
        }

        function SaveItemTimeLine(e, $this, url) {
            e.preventDefault();
            clearErrors($($this));
            var $data = new FormData($this);
            $($this).find('.summernote').each(function () {
                $data.append($(this).attr('name'),$(this).summernote('code'))
            })
            let $url = BASE_URL + url;
            jQuery.ajax({
                type: 'POST',
                url: $url,
                data: $data,
                contentType: false,
                processData: false,
                beforeSend() {
                    jQuery('body').append('<div class="overlay"><i class="fa fa-spinner fa-5x fa-spin mt-5"></i></div>');
                },
                success(xhr) {
                    if (xhr && xhr.message) {
                        new swal(
                            'success',
                            xhr.message,
                            'success'
                        );
                    } else {
                        new swal(
                            'success',
                            'Coupon added successfully',
                            'success'
                        );
                    }
                    $('.modal').modal('hide');
                    clearForm($($this));
                    if (xhr && xhr.redirect) {
                        window.location.replace(xhr.redirect);
                    }

                },
                error: HandleJsonErrors.bind($this),
                complete() {
                    jQuery('body .overlay').remove();
                    updateVisitList();
                }
            });
        }
    </script>
@endpush
