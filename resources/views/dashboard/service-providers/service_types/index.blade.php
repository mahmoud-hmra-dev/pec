@extends('dashboard.layout.main')
@section('content')
<div class="p-4">
    @can(\App\Enums\PermissionEnum::MANAGE_ServiceProvider)
        <div class="pt-2">
            <button
                class="edit btn btn-primary mb-4"
                href="javascript:" onclick="openAdd()">
                <i class="mdi mdi-plus-circle-outline"></i>
                Add new Service type
            </button>
        </div>
    @endcan


    <div style="min-height: 300px;">
        <table class="table table-bordered table-striped" id="service_types-table">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Code</th>
                    <th>Is one</th>
                    <th>Actions</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

@component('dashboard.service-providers.service_types.form')
@endcomponent

@stop
@push('scripts')
<script>
    $(function () {
        var table = $('#service_types-table').DataTable({
            processing: true,
            serverSide: false,
            ajax: '{!! route('service-types.index') !!}',
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
                {"data": "code"},
                {"data": "is_one"},
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
            deleteOpr(data.id, `dashboard/service-types/`+ data.id, table);
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
        $form.attr('action', `dashboard/service-types`);
        $('.add-form input[name="_method"]').remove();
        $modal.find('.modal-title').text(`Add Service type`);
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
        $form.append('<input type="hidden" name="id" value="'+data.id+'">');
        $form.attr('action', `dashboard/service-types/`+ data.id);
        $modal.find('.modal-title').text(`Edit Service type`);
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
