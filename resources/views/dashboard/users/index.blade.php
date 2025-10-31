@extends('dashboard.layout.main')
@section('content')
    <div class="p-4">
        @can(\App\Enums\PermissionEnum::MANAGE_USERS)
            <div class="pt-2">
                <button
                    class="edit btn btn-primary mb-4"
                    href="javascript:" onclick="openAdd()">
                    <i class="fa fa-plus"></i>
                    Add new User
                </button>
            </div>
        @endcan

        <table class="table table-bordered table-striped" id="users-table">
                <thead>
                <tr>
                    <th>Id</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Roles</th>
                    <th>Actions</th>
                </tr>
                </thead>
        </table>
    </div>

    @component('dashboard.users.form')
    @endcomponent
    @component('dashboard.users.view')
    @endcomponent

@stop
@push('scripts')
    <script>
        $(function () {
            var table = $('#users-table').DataTable({
                processing: true,
                serverSide: false,
                ajax: '{!! route('users.index') !!}',
                dom: 'Blfrtip',
                responsive: true,
                buttons: [
                    'copyHtml5',
                    'csvHtml5',
                    'pdfHtml5'
                ],
                columns: [
                    {"data": "id"},
                    {"data": "first_name"},
                    {"data": "last_name"},
                    {"data": "email"},
                    {"data": "roleNames"},
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
                deleteOpr(data.id, `dashboard/users/user-management/` + data.id, table);
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

        openAdd = () => {
            let $modal = $('#add-modal');
            let $data_form = $('#data-form');
            let $form = $('.add-form');
            clearForm($form)
            $form[0].reset();
            $form.find('.select2').change();
            $form.attr('action', `dashboard/users/user-management`);
            $('.add-form input[name="_method"]').remove();
            $modal.find('.modal-title').text(`Add User`);
            clearErrors($modal);
            $modal.modal('show');
            $modal.removeClass('out');
            $modal.addClass('in');
        };

        openEdit = (data) => {
            let $modal = $('#add-modal');
            let $data_form = $('#data-form');
            let $form = $('.add-form');
            $form[0].reset();
            $form.append('<input type="hidden" name="_method" value="PUT">');
            $form.attr('action', `dashboard/users/user-management/` + data.id);
            $modal.find('.modal-title').text(`Edit User`);
            $modal.find('#afm_btnSaveIt').text(`Update`);
            clearErrors($modal);
            data['role'] = data.roleNames;
            _fill($modal, data);
            $modal.modal('show');
            $modal.removeClass('out');
            $modal.addClass('in');
        };

        openView = (data) => {
            const $modal = $('#view-modal');
            const $data = $('#data');
            const fields = [
                {label: 'First name', value: data.first_name},
                {label: 'Last name', value: data.last_name},
                {label: 'Phone', value: data.phone},
                {label: 'Email', value: data.email},
                {label: 'Personal email', value: data.personal_email},
                {label: 'Country', value: data.country ? data.country.name : ''},
                {label: 'City', value: data.city},
                {label: 'Address', value: data.address},
                {label: 'roleNames', value: data.roleNames},
            ];
            const fieldHtml = fields.map(field => `<div class="form-group col-md-6"><label class="required-label"> ${field.label}: </label><span>  ${field.value}</span></div>`).join('');
            $data.html(fieldHtml);
            clearErrors($modal);
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
