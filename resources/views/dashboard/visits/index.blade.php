@extends('dashboard.layout.main')
@section('content')
<div class="p-4">
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{$message}}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    {{--
    <div class="pt-2">
        <a
            class="edit btn btn-xs btn-primary mb-4"
            href="javascript:" onclick="openAdd()">
            <i class="mdi mdi-plus-circle-outline"></i>
            Add new Visit
        </a>
    </div>
    --}}
        <div class="card p-2">
            <div id="search" class="row">
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
                <label for="start_date" class="label-required">Start at</label>
                <input name="start_date" id="start_date" type="datetime-local" class="form-control" value="{{old('start_date')}}">
            </div>
        </div>
        <div class="col-3">
            <div class="form-group">
                <label for="finish_date" class="label-required">End at</label>
                <input name="finish_date" id="finish_date" type="datetime-local" class="form-control" value="{{old('finish_date')}}">
            </div>
        </div>
        <div class="form-group col-3">
            <label class="d-block">Start at</label>
            <select id="start_at" name="start_at" class="form-control custom-select">
                <option value="">All</option>
                <option value="1" @if(old('start_at') == '1') selected @endif>Yes</option>
                <option value="0" @if(old('start_at') == '0') selected @endif>No</option>
            </select>
        </div>
            </div>
            <div class="m-2 text-right">
                <button class="edit btn btn-danger" id="resetFilter">Reset</button>
            </div>
        </div>
    <div style="min-height: 300px;">

        <table class="table table-bordered table-striped" id="visit_schedules-table">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Patient</th>
                    <th>Nurse</th>
                    <th>Program</th>
                    <th>Start Date</th>
                    <th>Should Start Date</th>
                    <th>Actions</th>

                </tr>
            </thead>
        </table>
    </div>
</div>
@include('dashboard.visits.form',['activity_types'=>$activity_types,'sub_program_patients'=>$sub_program_patients,'nurses'=>$nurses,'questions',$questions])

@stop
@push('scripts')
<script>
    $(function () {
        var table = $('#visit_schedules-table').DataTable({
            processing: true,
            serverSide: false,
            ajax: {
                url: "{{ route('sub_programs.visits.index',$sub_program->id) }}",
                data: function (d) {
                    d.start_date = $("#start_date").val();
                    d.finish_date = $("#finish_date").val();
                    d.sub_program_patient_id = $("#sub_program_patient_id").val();
                    d.start_at = $("#start_at").val();
                },
                method: "GET",
            },
            dom: 'Blfrtip',
            responsive:true,
            buttons: [
                'copyHtml5',
                'csvHtml5',
                'pdfHtml5'
            ],
            columns: [
                {"data": "id"},
                {"data": "sub_program_patient.patient.user","render":function (data) {
                        if(data)
                            return data.first_name + " " + data.last_name
                        return "";
                    }
                },
                {"data": "service_provider_type.service_provider.user","render":function (data) {if(data)
                        return data.first_name + " " + data.last_name
                        return "";
                    }},
                {"data": "sub_program",'name':"sub_program.name","render":function (data) {
                        if(data)
                            return data.name
                        return "";
                    }
                },
                {"data": "start_at"},
                {"data": "should_start_at"},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],
        });

        table.on('click', '.delete', function () {
            $tr = $(this).closest('tr');
            if ($($tr).hasClass('child')) {
                $tr = $tr.prev('.parent')
            }
            var data = table.row($tr).data();
            deleteOpr(data.id, `dashboard/sub_programs/`+data.sub_program_id+`/visits/` + data.id, table);
        });

        table.on('click', '.edit', function () {
            $tr = $(this).closest('tr');
            if ($($tr).hasClass('child')) {
                $tr = $tr.prev('.parent')
            }
            var data = table.row($tr).data();
            openEdit(data);
        });
        $('.add-form').on('submit', function (event) {
            SaveItem(event, this, $(this).attr('action'), table);
        });
        $("#start_date, #finish_date, #sub_program_patient_id , #start_at").change( function () {
            table.ajax.reload();
        });
        $("#resetFilter").click(function() {
            $("#start_date").val("");
            $("#finish_date").val("");
            $("#sub_program_patient_id").val("");
            $("#start_at").val("");
            table.ajax.reload();
        });
    });

    openAdd = () => {
        let $modal = $('#add-modal');
        let $form = $('.add-form');
        clearForm($form)
        $form[0].reset();
        $form.attr('action', `dashboard/visits`);
        $('.add-form input[name="_method"]').remove();
        $modal.find('.modal-title').text(`Add visit`);
        clearErrors($modal);
        $modal.modal('show');
        $modal.removeClass('out');
        $modal.addClass('in');
    };

    openEdit = (data) => {
        let $modal = $('#add-modal');
        let $form = $('.add-form');
        let $questions = $('#questions');
        $form[0].reset();
        $form.append('<input type="hidden" name="_method" value="PUT">');
        $form.attr('action', `dashboard/sub_programs/`+data.sub_program_id+`/visits/` + data.id);
        $modal.find('.modal-title').text(`Edit visit`);
        $modal.find('#afm_btnSaveIt').text(`Update`);
        clearErrors($modal);
        _fill($modal, data);
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
        $questions.append(Items);

        $("#meeting_display").hide();
        const type_visit = data.type_visit;
        if (type_visit === "{{\App\Enums\VisitTypeEnum::Online}}") {
            $("#meeting_display").show();
            $modal.find('input[type="text"][name="meeting"]').val(data.meeting);
        } else {
            $("#meeting_display").hide();
            $modal.find('input[type="text"][name="meeting"]').val(null);
        }

        $modal.modal('show');
        $modal.removeClass('out');
        $modal.addClass('in');
    };
    $('#add-modal').on('hidden.bs.modal',function (event) {
        $(this).removeClass('in');
        $(this).addClass('out');
    })
</script>
@endpush
