@extends('dashboard.layout.main')
@section('content')
<div class="p-4">

    <div class="pt-2">
        <button
            class="edit btn btn-primary mb-4"
            href="javascript:" onclick="openAdd('{!! $program_id !!}')">
            <i class="mdi mdi-plus-circle-outline"></i>
            Add new Distributor
        </button>
    </div>

    <div style="min-height: 300px;">
        <table class="table table-bordered table-striped" id="distributors-table">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Program</th>
                    <th>Country</th>
                    <th>Contract person</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Actions</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

@component('dashboard.programs.distributors.form')
@endcomponent

@stop
@push('scripts')
<script>
    $(function () {
        var table = $('#distributors-table').DataTable({
            processing: true,
            serverSide: false,
            ajax: '{!! route('programs.distributors.index',$program_id) !!}',
            dom: 'Blfrtip',
            responsive:true,
            buttons: [
                'copyHtml5',
                'csvHtml5',
                'pdfHtml5'
            ],
            columns: [
                {"data": "id"},
                {"data": "name"},
                {"data": "program.name"},
                {"data": "country.name"},
                {"data": "contract_person"},
                {"data": "email"},
                {"data": "phone"},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],
        });

        $('.add-form').on('submit', function (event) {
            SaveItem(event, this, $(this).attr('action'), table);
        });

        table.on('click', '.delete', function () {
            $tr = $(this).closest('tr');
            if ($($tr).hasClass('child')) {
                $tr = $tr.prev('.parent')
            }
            var data = table.row($tr).data();
            deleteOpr(data.id, `dashboard/programs/` + data.program.id+ `/distributors/`+ data.id, table);
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

    });

    openAdd = (programId ) => {
        let $modal = $('#add-modal');
        let $form = $('.add-form');
        clearForm($form)
        $form[0].reset();
        $form.attr('action', `dashboard/programs/`+ programId+'/distributors');
        $('.add-form input[name="_method"]').remove();
        $modal.find('.modal-title').text(`Add distributor`);
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
        $form.attr('action', `dashboard/programs/` + data.program.id+ `/distributors/`+ data.id);
        $modal.find('.modal-title').text(`Edit distributor`);
        $modal.find('#afm_btnSaveIt').text(`Update`);
        clearErrors($modal);
        _fill($modal, data);
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
