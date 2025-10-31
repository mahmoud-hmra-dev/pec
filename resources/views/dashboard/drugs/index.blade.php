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
        @can(\App\Enums\PermissionEnum::MANAGE_DRUGS)
            <div class="pt-2">
                <button
                    class="edit btn btn-primary mb-4"
                    href="javascript:" onclick="openAdd()">
                    <i class="mdi mdi-plus-circle-outline"></i>
                    Add new Drug
                </button>
            </div>
        @endcan


    <div style="min-height: 300px;">
        <table class="table table-bordered table-striped" id="drugs-table">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>API Name</th>
                    <th>Drug ID</th>
                    <th>Drug Initial</th>
                    <th>Client</th>
                    <th>Actions</th>

                </tr>
            </thead>
        </table>
    </div>
</div>
@component('dashboard.drugs.form',['clients'=>$clients])
@endcomponent

@stop
@push('scripts')
<script>
    $(function () {
        var table = $('#drugs-table').DataTable({
            processing: true,
            serverSide: false,
            ajax: '{!! route('drugs.index') !!}',
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
                {"data": "api_name"},
                {"data": "drug_id"},
                {"data": "drug_initial"},
                {"data": "client",'name':'client.user.first_name',"render":function (data) {
                        if(data)
                            return data.user.first_name + " " + data.user.last_name
                        return "";
                    }
                },
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],
        });


        table.on('click', '.delete', function () {
            $tr = $(this).closest('tr');
            if ($($tr).hasClass('child')) {
                $tr = $tr.prev('.parent')
            }
            var data = table.row($tr).data();
            deleteOpr(data.id, `dashboard/drugs/` + data.id, table);
        });
        $('.add-form').on('submit', function (event) {
            SaveItem(event, this, $(this).attr('action'), table);
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
        $form.attr('action', `dashboard/drugs`);
        $('.add-form input[name="_method"]').remove();
        $modal.find('.modal-title').text(`Add Drug`);
        $modal.find('input[type="hidden"][name="id"]').val(null);
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
        $form.attr('action', `dashboard/drugs/` + data.id);
        $modal.find('.modal-title').text(`Edit Drug`);
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
