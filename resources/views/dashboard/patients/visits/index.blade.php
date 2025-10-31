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
    {{---<div class="pt-2">
        <a
            class="edit btn btn-xs btn-primary mb-4"
            href="javascript:" onclick="openAdd()">
            <i class="mdi mdi-plus-circle-outline"></i>
            Add new Visit
        </a>
    </div>--}}
        <div class="card p-2">
            <div id="search" class="row">
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
                    <th>Service provider</th>
                    <th>Program</th>
                    <th>Start Date</th>
                    <th>Should Start Date</th>
                    <th>Actions</th>

                </tr>
            </thead>
        </table>
    </div>
</div>
@include('dashboard.patients.visits.form',['sub_program_patient_id'=>$sub_program_patient_id,'sub_program_id'=>$sub_program_id,'activity_types'=>$activity_types,'patients'=>$patients,'sub_programs'=>$sub_programs,'service_providers'=>$service_providers,'questions'=>$questions])

@stop
@push('scripts')
<script>
    $(function () {
        var table = $('#visit_schedules-table').DataTable({
            processing: true,
            serverSide: false,
            ajax: {
                url: '{!! route('sub_programs.patients.visits.index', ['sub_program_id' => $sub_program_id, 'sub_program_patient_id' => $sub_program_patient_id]) !!}',
                data: function (d) {
                    d.start_date = $("#start_date").val();
                    d.finish_date = $("#finish_date").val();
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
            deleteOpr(data.id, `dashboard/patients/`+ data.sub_program_patient.id+ `/visits/`+ data.id, table);
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
        $("#start_date, #finish_date , #start_at").change( function () {
            table.ajax.reload();
        });
        $("#resetFilter").click(function() {
            $("#start_date").val("");
            $("#finish_date").val("");
            $("#start_at").val("");
            table.ajax.reload();
        });
    });


    openEdit = (data) => {
        let $modal = $('#add-modal');
        let $form = $('.add-form');
        let $visit = $('#visit');
        let $questions = $('#questions');
        $form[0].reset();
        $form.append('<input type="hidden" name="_method" value="PUT">');
        $form.attr('action', `dashboard/patients/`+ data.sub_program_patient.id+ `/visits/`+ data.id);
        $modal.find('.modal-title').text(`Edit visit`);
        $modal.find('#afm_btnSaveIt').text(`Update`);
        clearErrors($modal);
        clearErrors($visit);
        _fill($visit, data);
        $questions.empty();
        let Items = '';
        const questions_items = @json($questions);
        questions_items.forEach((item, index) => {
            let type = item.type.name;
            let questionData = data.question_data ? data.question_data.find(item1 => item1.question_id === item.id) : null;
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

        $('#documents').find('[data-repeater-item]').slice(0).remove();
        $.each(data.visit_documents, function (index, item) {
            let $newItem = $(`<div data-repeater-item>
                                    <div class="row" >
                                        <div class="form-group col-sm-4">
                                            <label class="required-label">Name</label>
                                            <div class="custom-file">
                                                <input class="custom-file-input" type="file" name="name"  id="name" aria-describedby="name" >
                                                <label class="custom-file-label" for="name">Attach Document</label>
                                            </div>
                                            ${item.name ? `<a target="_blank" href="{{ Storage::url('${item.name}') }}">File</a>` : ''}
                                            <input name="documents[${index + 1}][id]" id="id"  type="hidden" class="form-control" value="${item.id}">
                                        </div>

                                        <div class="form-group col-sm-4">
                                            <label class="required-label">Type</label>
                                            <select class="form-control" id="type" name="documents[${index + 1}][type]" >
                                                @foreach($types as $type)<option value="{{$type->name}}">{{$type->name}}</option>
                                                @endforeach
            </select>
        </div>
        <div class="form-group col-sm-4">
            <label for="description" class="label-required">Description</label>
            <textarea   name="documents[${index + 1}][description]" id="description" type="text" class="form-control">${item.description}</textarea>
                                        </div>
                                        <div class="form-group col-sm-2">
                                            <input class="btn btn-danger btn-block" data-repeater-delete
                                                   type="button" value="Delete" />
                                        </div>
                                    </div>
                                </div>`)
                .clone();
            $('#documents [data-repeater-list]').append($newItem);
            $('select[name="documents[' + index + '][document_type_id]"] option[value="' + item.document_type_id + '"]').attr('selected', true);
        });

        $('#safety_reports').find('[data-repeater-item]').slice(0).remove();
        $.each(data.sub_program_patient.saftey_reports, function (index, item) {
            let $newItem = $(`<div data-repeater-item>
                                    <div class="row" >
                                        <div class="form-group col-sm-4">
                                            <label class="required-label">Name</label>
                                            <div class="custom-file">
                                                <input class="custom-file-input" type="file" name="name"  id="name" aria-describedby="name" >
                                                <label class="custom-file-label" for="name">Attach Document</label>
                                            </div>
                                            ${item.name ? `<a target="_blank" href="{{ Storage::url('${item.name}') }}">File</a>` : ''}
                                            <input name="safety_reports[${index + 1}][id]" id="id"  type="hidden" class="form-control" value="${item.id}">
                                        </div>

                                        <div class="form-group col-sm-4">
                                            <label class="required-label">Title</label>
                                            <input name="safety_reports[${index + 1}][title]" id="title"  type="text" class="form-control" value="${item.title}">
        </div>
        <div class="form-group col-sm-4">
            <label for="description" class="label-required">Description</label>
            <textarea   name="safety_reports[${index + 1}][description]" id="description" type="text" class="form-control">${item.description}</textarea>
                                        </div>
                                        <div class="form-group col-sm-2">
                                            <input class="btn btn-danger btn-block" data-repeater-delete
                                                   type="button" value="Delete" />
                                        </div>
                                    </div>
                                </div>`)
                .clone();

            $('#safety_reports [data-repeater-list]').append($newItem);
        });


        $('#safety_report_document_file').empty();
        $('#safety_report_document_file').append(`${data.sub_program.program.client.safety_report_document ? `<a target="_blank" href="{{ Storage::url('${data.sub_program.program.client.safety_report_document}')}}">Download Safety Report Template</a>` : ''}`);

        if (data.sub_program_patient.is_their_safety_report === 1) {
            $modal.find('input[type="checkbox"][name="is_their_safety_report"]').prop('checked', true);
        }
        if ($('#is_their_safety_report').is(':checked')) {
            $('#safety_reports').show();
        } else {
            $('#safety_reports').hide();
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
