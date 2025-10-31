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
                    <label class="required-label" for="sub_program_id_filter">Sub program</label>
                    <select class="form-control select2" id="sub_program_id_filter" name="sub_program_id_filter">
                        @foreach($sub_programs as $single)
                            <option value="{{$single->id}}" @if(old('sub_program_id_filter') == $single->id) selected @endif>{{$single->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-3">
                    <label class="required-label" for="sub_program_patient_id_filter">Patients</label>
                    <select class="form-control select2" id="sub_program_patient_id_filter" name="sub_program_patient_id_filter">
                        @foreach($sub_program_patients as $single)
                            <option value="{{$single->id}}" @if(old('sub_program_patient_id_filter') == $single->id) selected @endif>{{$single->patient->user->first_name}} {{$single->patient->user->last_name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-3">
                    <label class="required-label" for="site_notified_filter">Is Done</label>
                    <select class="form-control select2" id="site_notified_filter" name="site_notified_filter">
                        @foreach(['Yes', 'NULL']  as $single)
                            <option value="{{$single}}" @if(old('site_notified_filter') == $single) selected @endif>{{$single == "NULL" ? "No":"Yes" }}</option>
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
            </div>
            <div class="m-2 text-right">
                <button class="edit btn btn-danger" id="resetFilter">Reset</button>
            </div>
        </div>
    <div style="min-height: 300px;">

        <table class="table table-bordered table-striped" id="foc_visits_table">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Patient</th>
                    <th>Coordinator</th>
                    <th>Sub Program</th>
                    <th>Site notified</th>
                    <th>Notification method</th>
                    <th>Collected from pharmacy</th>
                    <th>Warehouse call</th>
                    <th>Reminder date</th>
                    <th>Date</th>
                    <th>Actions</th>

                </tr>
            </thead>
        </table>
    </div>
</div>
@include('dashboard.foc.form',['sub_program_patients'=>$sub_program_patients,'coordinators'=>$coordinators])

@stop
@push('scripts')
<script>

    $(document).ready(function() {
        $select2 = $('.select2').each(function() {
            $(this).select2({
                dropdownParent: $(this).parent(),
                width: '100%',
            })
        });
        $select2.val([]).trigger('change');
    });
    $(function () {
        var table = $('#foc_visits_table').DataTable({
            processing: true,
            serverSide: false,
            ajax: {
                url: "{{ route('foc.index') }}",
                data: function (d) {
                    d.start_date = $("#start_date").val();
                    d.finish_date = $("#finish_date").val();
                    d.sub_program_patient_id = $("#sub_program_patient_id_filter").val();
                    d.sub_program_id = $("#sub_program_id_filter").val();
                    d.site_notified = $("#site_notified_filter").val();
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
                {"data": "site_notified"},
                {"data": "notification_method"},
                {"data": "collected_from_pharmacy"},
                {"data": "warehouse_call"},

                {"data": "reminder_at"},
                {"data": "start_at"},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            columnDefs: [
                { "visible": false, "targets": 4 },
                { "visible": false, "targets": 5},
                { "visible": false, "targets": 6},
                { "visible": false, "targets": 7},
            ],
        });

        table.on('click', '.delete', function () {
            $tr = $(this).closest('tr');
            if ($($tr).hasClass('child')) {
                $tr = $tr.prev('.parent')
            }
            var data = table.row($tr).data();
            deleteOpr(data.id, `dashboard/foc/` + data.id, table);
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
        $("#start_date, #finish_date, #sub_program_patient_id_filter , #sub_program_id_filter , #site_notified_filter").change( function () {
            table.ajax.reload();
        });
        $("#resetFilter").click(function() {
            $("#start_date").val("");
            $("#finish_date").val("");
            $("#sub_program_patient_id_filter").val("");
            $("#sub_program_id_filter").val("");
            $("#site_notified_filter").val("");
            table.ajax.reload();
        });
    });

    openAdd = () => {
        let $modal = $('#add-modal');
        let $foc = $('#FOC');
        let $form = $('.add-form');
        clearForm($form)
        $form[0].reset();
        $form.attr('action', `dashboard/foc`);
        $('.add-form input[name="_method"]').remove();
        $modal.find('.modal-title').text(`Add FOC`);
        clearErrors($modal);
        if($('#site_notified').val() === "Yes")
            $('#attachment_div').show();
        else
            $('#attachment_div').hide();

        $modal.modal('show');
        $modal.removeClass('out');
        $modal.addClass('in');
    };

    openEdit = (data) => {
        let $modal = $('#add-modal');
        let $form = $('.add-form');
        let $foc = $('.FOC');
        let $questions = $('#questions');
        $form[0].reset();
        $form.append('<input type="hidden" name="_method" value="PUT">');
        $form.attr('action', `dashboard/foc/` + data.id);
        $modal.find('.modal-title').text(`Edit FOC`);
        $modal.find('#afm_btnSaveIt').text(`Update`);
        clearErrors($modal);
        var select2 = $('.select2').each(function() {
            $(this).select2({
                dropdownParent: $(this).parent(),
                width: '100%',
            })
        });
        select2.val([]).trigger('change');
        _fill($foc, data);

        if($('#site_notified').val() === "Yes")
            $('#attachment_div').show();
        else
            $('#attachment_div').hide();


        $('#attachment').empty();
        $('#attachment').append(`${data.attachment ? `<a target="_blank" href="{{ Storage::url('${data.attachment}') }}">File</a>` : ''}`);

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
