@extends('dashboard.layout.main')
@section('content')
    <div class="p-4">
        @can(\App\Enums\PermissionEnum::MANAGE_PROGRAMS)
            <div class="pt-2">
                <button class="edit btn btn-primary mb-4" onclick="openAdd()">
                    <i class="mdi mdi-plus-circle-outline"></i>
                    Add program contact
                </button>
            </div>
        @endcan

        <div style="min-height: 300px;">
            <table class="table table-bordered table-striped" id="program-contacts-table">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Role</th>
                    <th>Title</th>
                    <th>Actions</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>

    @component('dashboard.programs.contacts.modal', ['program' => $program])
    @endcomponent
@endsection

@push('scripts')
    <script>
        const PROGRAM_ID = {{ $program->id }};
        $(function () {
            const table = $('#program-contacts-table').DataTable({
                processing: true,
                serverSide: false,
                ajax: '{!! route('programs.contacts.index', $program->id) !!}',
                dom: 'Blfrtip',
                responsive: true,
                buttons: ['copyHtml5', 'csvHtml5', 'pdfHtml5'],
                columns: [
                    {data: 'name'},
                    {data: 'email', defaultContent: ''},
                    {data: 'phone', defaultContent: ''},
                    {data: 'contact_role'},
                    {data: 'resolved_title', defaultContent: ''},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
            });

            $('.add-form').on('submit', function (event) {
                SaveItem(event, this, $(this).attr('action'), table);
            });

            table.on('click', '.edit', function () {
                const $tr = $(this).closest('tr');
                const data = table.row($tr).data();
                openEdit(data);
            });

            table.on('click', '.delete', function () {
                const $tr = $(this).closest('tr');
                const data = table.row($tr).data();
                deleteOpr(data.id, `dashboard/programs/${PROGRAM_ID}/contacts/${data.id}`, table);
            });
        });

        openAdd = () => {
            const $modal = $('#contact-modal');
            const $form = $modal.find('form');
            clearForm($form);
            $form[0].reset();
            $form.attr('action', `dashboard/programs/${PROGRAM_ID}/contacts`);
            $form.find('input[name="_method"]').remove();
            $modal.modal('show');
            $modal.removeClass('out').addClass('in');
        };

        openEdit = (data) => {
            const $modal = $('#contact-modal');
            const $form = $modal.find('form');
            $form[0].reset();
            $form.find('input[name="_method"]').remove();
            $form.append('<input type="hidden" name="_method" value="PUT">');
            $form.attr('action', `dashboard/programs/${PROGRAM_ID}/contacts/${data.id}`);
            clearErrors($modal);
            _fill($modal, data);
            toggleCustomTitle();
            $modal.modal('show');
            $modal.removeClass('out').addClass('in');
        };

        const toggleCustomTitle = () => {
            const $select = $('#title');
            const selected = $select.val();
            const $custom = $('#custom_title_group');
            if (selected === 'other') {
                $custom.removeClass('d-none');
            } else {
                $custom.addClass('d-none');
                $('#custom_title').val('');
            }
        };

        $('#title').on('change', toggleCustomTitle);
    </script>
@endpush
