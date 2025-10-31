@extends('dashboard.layout.main')
@section('content')
<div class="p-4">
    @can(\App\Enums\PermissionEnum::MANAGE_PHARMACIES)
        <div class="pt-2">
            <button
                class="edit btn btn-primary mb-4"
                href="javascript:" onclick="openAdd()">
                <i class="mdi mdi-plus-circle-outline"></i>
                Add new Pharmacy
            </button>
        </div>
    @endcan


    <div style="min-height: 300px;">
        <table class="table table-bordered table-striped" id="pharmacies-table">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Country</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Actions</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

@component('dashboard.pharmacies.form')
@endcomponent

@stop
@push('scripts')
<script>
    $(function () {
        var table = $('#pharmacies-table').DataTable({
            processing: true,
            serverSide: false,
            ajax: '{!! route('pharmacies.index') !!}',
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
                {"data": "country.name",'name':"country.name" , "render" : function(data){
                    if(data)
                        return data;
                    return  "";
                    }
                },
                {"data": "phone"},
                {"data": "address"},
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
            deleteOpr(data.id, `dashboard/pharmacies/` + data.id, table);
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
        $form.attr('action', `dashboard/pharmacies`);
        $('.add-form input[name="_method"]').remove();
        $modal.find('.modal-title').text(`Add Pharmacy`);
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
        $form.attr('action', `dashboard/pharmacies/` + data.id);
        $modal.find('.modal-title').text(`Edit Pharmacy`);
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
