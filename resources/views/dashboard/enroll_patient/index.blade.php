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


    <div style="min-height: 300px;">
        <div class="row" id="sub_program">
            <div class="form-group col-md-4">
                <label class="required-label" for="program_id">Program</label>
                <select class="form-control select2" id="program_id" name="program_id">
                    @foreach($programs as $single)
                        <option value="{{$single->id}}" @if(old('program_id') == $single->id) selected @endif>{{$single->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-4">
                <label class="required-label" for="drug_id">Drug</label>
                <select class="form-control select2" id="drug_id" name="drug_id">
                    @foreach($sub_programs_drugs as $single)
                        <option value="{{$single->id}}" @if(old('drug_id') == $single->id) selected @endif>{{$single->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-4">
                <label class="required-label" for="sub_program_country_id">Country</label>
                <select class="form-control select2" id="sub_program_country_id" name="sub_program_country_id">
                    @foreach($sub_programs_countries as $single)
                        <option value="{{$single->id}}" @if(old('sub_program_country_id') == $single->id) selected @endif>{{$single->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-4">
                <button class="btn btn-xs btn-danger" id="resetFilter">Reset</button>
            </div>
        </div>
        <table class="table table-bordered table-striped" id="sub_programs">
            <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Program</th>
                <th>Country</th>
                <th>Drug</th>
                <th>Start date</th>
                <th>Finish date</th>
                <th>Actions</th>
            </tr>
            </thead>
        </table>
    </div>
</div>

@component('dashboard.enroll_patient.form',['doctors'=>$doctors,'hospitals'=>$hospitals,'drugs'=>$drugs,'programs'=>$programs,'sub_programs_countries'=>$sub_programs_countries,'pharmacies'=>$pharmacies])
@endcomponent

@stop
@push('scripts')
<script>

    $(document).ready(function() {
        var program_id = $("#program_id").select2();
        var drug_id = $("#drug_id").select2();
        var sub_program_country_id = $("#sub_program_country_id").select2();

        program_id.val([]).trigger('change');
        drug_id.val([]).trigger('change');
        sub_program_country_id.val([]).trigger('change');


    });

    $(function () {

        var sub_programs = $('#sub_programs').DataTable({
            processing: true,
            serverSide: false,
            dom: 'Blfrtip',
            responsive: true,
            ajax: {
                url: '{!! route('enroll_patient.index') !!}',
                data: function (d) {
                    d.program_id = $("#program_id").val();
                    d.drug_id = $("#drug_id").val();
                    d.sub_program_country_id = $("#sub_program_country_id").val();
                },
                method: "GET",
            },
            lengthMenu: [[5, 10, 25, 100, -1], [5, 10, 25, 100, "All"]],
            buttons: [
            ],
            columns: [
                {"data": "id"},
                {"data": "name"},
                {"data": "program.name"},
                {"data": "country.name"},
                {"data": "drug",'name':"drug.name","render":function (data) {
                        if(data)
                            return data.name
                        return "";
                    }
                },
                {"data": "start_date"},
                {"data": "finish_date"},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],
        });

        sub_programs.on('click', '.enroll', function () {
            $tr = $(this).closest('tr');
            if ($($tr).hasClass('child')) {
                $tr = $tr.prev('.parent')
            }
            var data = sub_programs.row($tr).data();
            openEnroll(data);
        });

        $('.add-form').on('submit', function (event) {
            SaveItem(event, this, $(this).attr('action'), sub_programs);
        });
        $("#program_id, #drug_id, #sub_program_country_id").change( function () {
            sub_programs.ajax.reload();
        });


        $("#resetFilter").click(function() {
            $("#program_id").val([]).trigger('change');
            $("#drug_id").val([]).trigger('change');
            $("#sub_program_country_id").val([]).trigger('change');
            sub_programs.ajax.reload();
        });
    });
    openEnroll = (data) => {
        let $modal = $('#add-modal');
        let $form = $('.add-form');
        $form[0].reset();
        $form.attr('action', `dashboard/enroll_patient/`+ data.id);
        $form.append('<input type="hidden" name="_method" value="PUT">');
        $modal.find('.modal-title').text(`Edit Patient`);
        clearErrors($modal);


        $('.is_eligible').show();

        $modal.find('input[type="hidden"][name="has_calls"]').val(data.has_calls);
        $modal.find('input[type="hidden"][name="call_every_day"]').val(data.call_every_day);
        if(data.has_calls && data.call_every_day > 0){
            $('#coordinator_calls').show();
            $.ajax({
                url: "{{ route('enroll_patient.coordinators') }}/" + data.id,
                type: "GET",
                dataType: "json",
                success: function (response) {
                    var coordinator = $("#coordinator").select2();

                    coordinator.empty();

                    var coordinators = response.coordinators;

                    coordinators.forEach(function (single) {
                        var option = new Option(single.service_provider_type.service_provider.user.first_name + ' ' + single.service_provider_type.service_provider.user.last_name, single.id);
                        coordinator.append(option);
                    });

                    coordinator.select2();
                    coordinator.val([]).trigger('change');
                }
            });
        } else {
            $('#coordinator_calls').hide();
        }
        $modal.find('input[type="hidden"][name="has_visits"]').val(data.has_visits);
        $modal.find('input[type="hidden"][name="visit_every_day"]').val(data.visit_every_day);

        if(data.has_visits && data.visit_every_day > 0){
            $('#nurse_visits').show();
            $.ajax({
                url: "{{ route('enroll_patient.nurses') }}/" + data.id,
                type: "GET",
                dataType: "json",
                success: function (response) {
                    var nurse = $("#nurse").select2({
                        width: '100%',
                    });
                    nurse.empty();
                    var nurses = response.nurses;
                    nurses.forEach(function (single) {
                        var option = new Option(single.service_provider_type.service_provider.user.first_name + ' ' + single.service_provider_type.service_provider.user.last_name, single.id);
                        nurse.append(option);
                    });

                    nurse.select2();
                    nurse.val([]).trigger('change');
                    $('.is_eligible').hide();
                }
            });
        } else  {
            $('#nurse_visits').hide();
        }

        $('#documents').find('[data-repeater-item]').slice(0).remove();
        $.each(data.patient_documents, function (index, item) {
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
        $.each(data.safety_reports, function (index, item) {
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
        $('#safety_report_document_file').append(`${data.program.client.safety_report_document ? `<a target="_blank" href="{{ Storage::url('${data.program.client.safety_report_document}') }}">Download Safety Report Template</a>` : ''}`);

        var $select2 = $('.select2').each(function() {
            $(this).select2({
                dropdownParent: $(this).parent(),
                width: '100%',
            })
        })
        $select2.val([]).trigger('change');

        $('#country_id').on('change', function (event) {
            event.preventDefault();
            let country_id = $(this).val();
            let program_initial = data.program_initial;
            let map_id =  data.program.map_id;

            $.ajax({
                url: "{{ route('enroll_patient.generate_patient_no') }}/" + country_id,
                type: "GET",
                dataType: "json",
                data: {
                    program_initial: program_initial,
                    map_id: map_id
                },
                success: function (data) {
                    let patient_no = data.patient_no;
                    console.log(patient_no);
                    $modal.find('input[type="text"][name="patient_no"]').val(patient_no);
                },
            });
        });

        $modal.modal('show');
        $modal.removeClass('out');
        $modal.addClass('in');
    };

    $('#add-modal').on('hidden.bs.modal',function (event) {
        $(this).removeClass('in');
        $(this).addClass('out');
    });
</script>
<script>
    $('#close-img').on('click',function (event) {
        var id = $(this).attr('data-id');
        deleteOpr(id, `dashboard/users/deleteImage/` + id, table)
    })





</script>
@endpush
