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
    @can(\App\Enums\PermissionEnum::MANAGE_PROGRAMS)
            <div class="pt-2">
                <a
                    class="edit btn btn-primary mb-4"
                    href="javascript:" onclick="openAdd()">
                    <i class="mdi mdi-plus-circle-outline"></i>
                    Add new Program
                </a>
            </div>
        @endcan


    <div style="min-height: 300px;">
        <table class="table table-bordered table-striped" id="programs-table">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Program No</th>
                    <th>Client</th>
                    <th>MAP ID</th>
                    <th>Project Manager</th>
                    <th>Drugs</th>
                    <th>Countries</th>
                    <th>Start</th>
                    <th>End</th>
                    <th>Actions</th>

                </tr>
            </thead>
        </table>
    </div>
</div>

@include('dashboard.programs.form',['managers'=>$managers,'clients'=>$clients])
@include('dashboard.programs.view',['managers'=>$managers,'clients'=>$clients])
@stop

@push('scripts')
<script>
    $(function () {
        var table = $('#programs-table').DataTable({
            processing: true,
            serverSide: false,
            ajax: '{!! route('programs.index') !!}',
            responsive: true,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            fixedHeader: true,
            buttons: [
                'copyHtml5',
                'csvHtml5',
                'pdfHtml5'
            ],
            columns: [
                {"data": "id"},
                {"data": "name"},
                {"data": "program_no"},
                {"data": "client",'name':'client.user.first_name',"render":function (data) {
                        if(data)
                            return data.user.first_name + " " + data.user.last_name
                        return "";
                    }
                },
                {"data": "map_id"},
                {"data": "service_provider_type.service_provider.user","render":function (data) {if(data)
                    return data.first_name + " " + data.last_name
                        return "";
                }},
                {"data": "drugs"},
                {"data": "countries"},
                {"data": "started_at"},
                {"data": "ended_at"},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            columnDefs: [
                { "visible": false, "targets": 3 },
                { "visible": false, "targets": 5 },
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
            deleteOpr(data.id, `dashboard/programs/` + data.id, table);
        });

        table.on('click', '.edit', function () {
            $tr = $(this).closest('tr');
            if ($($tr).hasClass('child')) {
                $tr = $tr.prev('.parent')
            }
            var data = table.row($tr).data();
            openEdit(data);
        });
        table.on('click', '.view', function () {
            $tr = $(this).closest('tr');
            if ($($tr).hasClass('child')) {
                $tr = $tr.prev('.parent')
            }
            var data = table.row($tr).data();
            openView(data);
        });
        $('.add-form').on('submit', function (event) {
            SaveItem(event, this, $(this).attr('action'), table);
        });

    });

    openAdd = () => {
        let $modal = $('#add-modal');
        let $form = $('.add-form');
        clearForm($form)
        $form[0].reset();
        $form.attr('action', `dashboard/programs`);
        $('.add-form input[name="_method"]').remove();
        $modal.find('input[type="hidden"][name="id"]').val(null);
        $modal.find('.modal-title').text(`Add program`);
        clearErrors($modal);
        $modal.modal('show');
        $modal.removeClass('out');
        $modal.addClass('in');
    };

    openEdit = (data) => {
        let $modal = $('#add-modal');
        let $form = $('.add-form');
        $form[0].reset();
        $form.append('<input type="hidden" name="_method" value="PUT">');
        $form.attr('action', `dashboard/programs/` + data.id);
        $modal.find('.modal-title').text(`Edit program`);
        $modal.find('#afm_btnSaveIt').text(`Update`);
        clearErrors($modal);
        _fill($modal, data);

        var program_countries_values = [];
        console.log(data.program_countries);

        data.program_countries.forEach(function (single) {
            program_countries_values.push(single.country_id);
        });
        $("#program_countries").val(program_countries_values).trigger('change');

        $.ajax({
            url: "{{ route('programs.drugs') }}/" + data.client_id,
            type: "GET",
            dataType: "json",
            success: function (response) {
                var drug = $("#drugs").select2({
                    width: '100%',
                });
                drug.empty();
                var drugs = response.drugs;
                drugs.forEach(function (single) {
                    var option = new Option(single.name, single.id);
                    drug.append(option);
                });
                drug.select2();
                var values = [];
                data.program_drugs.forEach(function (single) {
                    values.push(single.drug_id);
                });
                drug.val(values).trigger('change');
            }
        });
        $modal.modal('show');
        $modal.removeClass('out');
        $modal.addClass('in');
    };
    openView = (data) => {
        const $modal = $('#view-modal');
        const $data = $('#data');

        const fields = [
            {label: 'Name', value: data.name},
            {label: 'Program No', value: data.program_no},
            {label: 'Client', value: data.client.user.first_name + " " + data.client.user.last_name},
            {label: 'MAP ID', value: data.map_id},
            {label: 'Start', value: data.started_at},
            {label: 'End', value: data.ended_at},
            {label: 'Countries', value: data.countries},
        ];

        const drugTableHtml = `
        <table class="table table-bordered table-striped w-100">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Api Name</th>
                     <th>Drug ID</th>
                     <th>Drug Initial</th>
                    <th>Creation Date</th>
                </tr>
            </thead>
            <tbody>
                ${data.program_drugs.map(function(item) {
            return `
                        <tr>
                            <td>${item.drug.name}</td>
<td>${item.drug.api_name}</td>
<td>${item.drug.drug_id}</td>
<td>${item.drug.drug_initial}</td>
                            <td>${item.created_at}</td>
                        </tr>
                    `;
        }).join('')}
            </tbody>
        </table>
    `;

        fields.push(
            {label: 'Drug table', value: drugTableHtml}
        );


        const fieldHtml = fields.map(field => {
            if(field.label === 'Drug table' || field.label == 'Countries') {
                return `
                <div class="form-group col-md-12">
                    <label class="required-label">${field.label}: </label>
                    <span>${field.value}</span>
                </div>
            `;
            } else {
                return `
                <div class="form-group col-md-6">
                    <label class="required-label">${field.label}: </label>
                    <span>${field.value}</span>
                </div>
            `;
            }
        }).join('');

        $data.html(fieldHtml);

        clearErrors($modal);
        $modal.modal('show');
        $modal.removeClass('out');
        $modal.addClass('in');
    };
    $('#add-modal').on('hidden.bs.modal',function (event) {
        $(this).removeClass('in');
        $(this).addClass('out');
    })

   $('#client_id').on('change',function (event) {
       event.preventDefault();
       let client_id = $(this).val();
       let program_id = $('#program_id').val();

       $.ajax({
           url: "{{ route('programs.drugs') }}/" + client_id,
           type: "GET",
           dataType: "json",
           success: function (response) {
               var drug = $("#drugs").select2({
                   width: '100%',
               });
               drug.empty();
               var drugs = response.drugs;
               drugs.forEach(function (single) {
                   var option = new Option(single.name, single.id);
                   drug.append(option);
               });
               drug.select2();
               drug.val([]).trigger('change');
               $.ajax({
                   url: "{{ route('programs.program_drugs') }}/" + program_id,
                   type: "GET",
                   dataType: "json",
                   success: function (response) {
                       var values = [];
                       response.program_drugs.forEach(function (single) {
                           values.push(single.drug_id);
                       });
                       drug.val(values).trigger('change');
                   }
               });
           }
       });
   });
</script>
@endpush
