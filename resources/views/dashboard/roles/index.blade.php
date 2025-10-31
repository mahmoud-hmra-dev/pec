@extends('dashboard.layout.main')
@section('content')
    <div class="p-4">
        @can(\App\Enums\PermissionEnum::MANAGE_USERS)
            <div class="pt-2">
                <button
                    class="edit btn btn-primary mb-4"
                    href="javascript:" onclick="openAdd()">
                    <i class="fa fa-plus"></i>
                    Add new Role
                </button>
            </div>
        @endcan

        <table class="table table-bordered table-striped" id="roles-table">
                <thead>
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Permissions</th>
                    <th>Actions</th>
                </tr>
                </thead>
        </table>
    </div>

    @component('dashboard.roles.form',['permissions'=>$permissions])
    @endcomponent

@stop
@push('scripts')
    <script>
        $(document).ready(function() {
            var permissions = $("#permissions").select2();
        });
        $(function () {
            var table = $('#roles-table').DataTable({
                processing: true,
                serverSide: false,
                ajax: '{!! route('roles.index') !!}',
                dom: 'Blfrtip',
                responsive: true,
                buttons: [
                    'copyHtml5',
                    'csvHtml5',
                    'pdfHtml5'
                ],
                columns: [
                    {"data": "id"},
                    {"data": "name"},
                    {"data": "permissions", 'name': 'permissions.name', 'render': function(data) {
                            var permissionNames = '';
                            $.each(data, function(index, permission) {
                                permissionNames += permission.name + ', ';
                            });
                            return permissionNames.slice(0, -2);
                        }},
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
                deleteOpr(data.id, `dashboard/roles/` + data.id, table);
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
            var permissions = $("#permissions").select2();
            var values = [];
            permissions.val(values).trigger('change');
            $form.attr('action', `dashboard/roles`);
            $('.add-form input[name="_method"]').remove();
            $modal.find('.modal-title').text(`Add Role`);
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
            $form.attr('action', `dashboard/roles/` + data.id);
            $modal.find('.modal-title').text(`Edit Role`);
            $modal.find('#afm_btnSaveIt').text(`Update`);
            clearErrors($modal);
            _fill($modal, data);

            var permissions = $("#permissions").select2();
            var values = [];
            $.each(data.permissions, function (index, item) {
                values.push(item.name);
            });
            permissions.val(values).trigger('change');

            $modal.modal('show');
            $modal.removeClass('out');
            $modal.addClass('in');
        };

        $('#add-modal').on('hidden.bs.modal', function (event) {
            $(this).removeClass('in');
            $(this).addClass('out');
        })
    </script>
@endpush
