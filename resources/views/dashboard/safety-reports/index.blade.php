@extends('dashboard.layout.main')
@section('content')
<div class="p-4">
    @can(\App\Enums\PermissionEnum::MANAGE_SafetyReport)
        <div class="pt-2">
            <button
                class="edit btn btn-primary mb-4"
                href="javascript:" onclick="openAdd()">
                <i class="mdi mdi-plus-circle-outline"></i>
                Add new safety report
            </button>
        </div>
    @endcan
        <div class="card p-2">
            <div id="search" class="row">
                <div class="col-3">
                    <label class="required-label" for="filter_program_id">Program</label>
                    <select class="form-control select2" id="filter_program_id" name="filter_program_id">
                        @foreach($programs as $single)
                            <option value="{{$single->id}}" @if(old('filter_program_id') == $single->id) selected @endif>{{$single->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-3">
                    <label class="required-label" for="filter_sub_program_id">Sub program</label>
                    <select class="form-control select2" id="filter_sub_program_id" name="filter_sub_program_id">
                        @foreach($sub_programs as $single)
                            <option value="{{$single->id}}" @if(old('filter_sub_program_id') == $single->id) selected @endif>{{$single->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-3">
                    <label class="required-label" for="filter_sub_program_patient_id">Patients</label>
                    <select class="form-control select2" id="filter_sub_program_patient_id" name="filter_sub_program_patient_id">
                        @foreach($sub_program_patients as $single)
                            <option value="{{$single->id}}" @if(old('filter_sub_program_patient_id') == $single->id) selected @endif>{{$single->patient->user->first_name}} {{$single->patient->user->last_name}}</option>
                        @endforeach
                    </select>
                </div>

            </div>
            <div class="m-2 text-right">
                <button class="edit btn btn-danger" id="resetFilter">Reset</button>
            </div>
        </div>

    <div style="min-height: 300px;">
        <table class="table table-bordered table-striped" id="safety-reports-table">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Patient</th>
                    <th>Program</th>
                    <th>Sub Program</th>
                    <th>Name</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

@component('dashboard.safety-reports.form',['programs'=>$programs,'sub_program_patients'=>$sub_program_patients,'sub_programs'=>$sub_programs])
@endcomponent

@stop
@push('scripts')
<script>
    $(document).ready(function() {

        $('.select2').each(function() {
            $(this).select2({
                width: '100%',
            })
        })
    });
    $(function () {

        var table = $('#safety-reports-table').DataTable({
            processing: true,
            serverSide: false,
            ajax: {
                url: "{!! route('safety-reports.index') !!}",
                data: function (d) {
                    d.sub_program_patient_id = $("#filter_sub_program_patient_id").val();
                    d.sub_program_id = $("#filter_sub_program_id").val();
                    d.program_id = $("#filter_program_id").val();

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
                {"data": "sub_program_patient.patient.user", "render" : function(data){
                        if(data)
                            return data.first_name + ' ' + data.last_name;
                        return  "";
                    }
                },
                {"data": "sub_program_patient.sub_program" , "render" : function(data){
                        if(data)
                            return data.program ? data.program.name : "";
                        return  "";
                    }
                },
                {"data": "sub_program_patient.sub_program" , "render" : function(data){
                        if(data)
                            return data.name;
                        return  "";
                    }
                },
                {"data": "name" ,'render':function (data) { return data ? `<a target="_blank" href="{{ Storage::url('${data}') }}">File</a>` : '';}},
                {"data": "title"},
                {"data": "description"},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],
        });

        $('.add-form').on('submit', function (event) {
            SaveItem(event, this, $(this).attr('action'), table);
        });
        $("#filter_program_id,#filter_sub_program_patient_id,#filter_sub_program_id").change( function () {
            table.ajax.reload();
        });
        $("#filter_sub_program_patient_id").val([]).trigger('change');
        $("#filter_sub_program_id").val([]).trigger('change');
        $("#filter_program_id").val([]).trigger('change');
        $("#resetFilter").click(function() {
            $("#filter_sub_program_patient_id").val([]).trigger('change');
            $("#filter_sub_program_id").val([]).trigger('change');
            $("#filter_program_id").val([]).trigger('change');
            table.ajax.reload();
        });
        table.on('click', '.delete', function () {
            $tr = $(this).closest('tr');
            if ($($tr).hasClass('child')) {
                $tr = $tr.prev('.parent')
            }
            var data = table.row($tr).data();
            deleteOpr(data.id, `dashboard/safety-reports/` + data.id, table);
        });

        table.on('click', '.edit', function () {
            $tr = $(this).closest('tr');
            if ($($tr).hasClass('child')) {
                $tr = $tr.prev('.parent')
            }
            var data = table.row($tr).data();
            openEdit(data);
        });

    });

    openAdd = () => {
        let $modal = $('#add-modal');
        let $form = $('.add-form');
        clearForm($form)
        $form[0].reset();
        $form.find('.select2').change();
        $form.attr('action', `dashboard/safety-reports`);
        $('.add-form input[name="_method"]').remove();
        $modal.find('.modal-title').text(`Add safety report`);
        clearErrors($modal);
        var sub_program_id = $("#sub_program_id").select2({
            width: '100%',
        });
        sub_program_id.val([]).trigger('change');

        var sub_program_patient = $("#sub_program_patient_id").select2({
            width: '100%',
        });
        sub_program_patient.val([]).trigger('change');
        $modal.find('input[type="hidden"][name="id"]').val(null);
        $('#name_file').empty();
        $modal.modal('show');
        $modal.removeClass('out');
        $modal.addClass('in');
    };

    openEdit = (data) => {
        let $modal = $('#add-modal');
        let $safety_reports = $('.safety-reports');
        let $form = $('.add-form');
        $form[0].reset();
        $form.append('<input type="hidden" name="_method" value="PUT">');
        $form.attr('action', `dashboard/safety-reports/` + data.id);
        $modal.find('.modal-title').text(`Edit safety report`);
        $modal.find('#afm_btnSaveIt').text(`Update`);
        clearErrors($modal);

        var sub_program_patient = $("#sub_program_patient_id").select2({
            width: '100%',
        });
        sub_program_patient.val([]).trigger('change');

        let sub_program_id = data.sub_program_patient.sub_program_id;
        var sub_program = $("#sub_program_id").select2({
            width: '100%',
        });
        sub_program.val(sub_program_id).trigger('change');
        $.ajax({
            url: "{{ route('safety-reports.sub_program_patients') }}/" + sub_program_id,
            type: "GET",
            dataType: "json",
            success: function (response) {
                var sub_program_patient = $("#sub_program_patient_id").select2({
                    width: '100%',
                });
                sub_program_patient.empty();
                var sub_program_patients = response.sub_program_patients;
                sub_program_patients.forEach(function (single) {
                    var option = new Option(single.patient.user.first_name + ' ' + single.patient.user.last_name, single.id);
                    sub_program_patient.append(option);
                });
            }
        });
        sub_program_patient.val(data.sub_program_patient_id).trigger('change');
        _fill($safety_reports, data);

        $('#name_file').empty();
        $('#name_file').append(`${data.name ? `<a target="_blank" href="{{ Storage::url('${data.name}') }}">File</a>` : ''}`);
        $modal.find('input[type="hidden"][name="id"]').val(data.id);
        $modal.modal('show');
        $modal.removeClass('out');
        $modal.addClass('in');
    };
    $('#add-modal').on('hidden.bs.modal',function (event) {
        $(this).removeClass('in');
        $(this).addClass('out');
    })


    $('#sub_program_id').on('change',function (event) {
        event.preventDefault();
        let sub_program_id = $(this).val();
        $.ajax({
            url: "{{ route('safety-reports.sub_program_patients') }}/" + sub_program_id,
            type: "GET",
            dataType: "json",
            success: function (response) {
                var sub_program_patient = $("#sub_program_patient_id").select2({
                    width: '100%',
                });
                sub_program_patient.empty();
                var sub_program_patients = response.sub_program_patients;
                sub_program_patients.forEach(function (single) {
                    var option = new Option(single.patient.user.first_name + ' ' + single.patient.user.last_name, single.id);
                    sub_program_patient.append(option);
                });
                sub_program_patient.select2();
                sub_program_patient.val([]).trigger('change');
            }
        });
    });
</script>
@endpush
