@extends('dashboard.layout.main')
@section('content')
<div class="p-4">
    @can(\App\Enums\PermissionEnum::MANAGE_ServiceProvider)
        <div class="pt-2">
            <button
                class="edit btn btn-primary mb-4"
                href="javascript:" onclick="openAdd('{!! $service_provider_id !!}')">
                <i class="mdi mdi-plus-circle-outline"></i>
                Add new Certificate
            </button>
        </div>
    @endcan

    <div style="min-height: 300px;">
        <table class="table table-bordered table-striped" id="certificates-table">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>URL</th>
                    <th>Actions</th>

                </tr>
            </thead>
        </table>
    </div>
</div>

@component('dashboard.service-providers.certificates.form')
@endcomponent

@stop
@push('scripts')
<script>
    $(function () {
        var table = $('#certificates-table').DataTable({
            processing: true,
            serverSide: false,
            ajax: '{!! route('service-providers.certificates.index',$service_provider_id) !!}',
            dom: 'Blfrtip',
            responsive:true,
            buttons: [
                'copyHtml5',
                'csvHtml5',
                'pdfHtml5'
            ],
            columns: [
                {"data": "id"},
                {"data": "url","render":function (data) {return data ? `<a target="_blank" href="{{ Storage::url('${data}') }}">Certificate</a>` : '';}},
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
            deleteOpr(data.id, `dashboard/service-providers/` + data.service_provider.id+ `/certificates/`+ data.id, table);
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
    openAdd = (serviceProviderId ) => {
        let $modal = $('#add-modal');
        let $form = $('.add-form');
        clearForm($form)
        $form[0].reset();
        $form.attr('action', `dashboard/service-providers/`+ serviceProviderId+'/certificates/');
        $('.add-form input[name="_method"]').remove();
        $modal.find('.modal-title').text(`Add Certificate`);
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
        $form.attr('action', `dashboard/service-providers/` + data.service_provider.id+ `/certificates/`+ data.id);
        $modal.find('.modal-title').text(`Edit Certificate`);
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
