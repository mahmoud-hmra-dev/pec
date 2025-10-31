@extends('dashboard.layout.main')
@section('content')
    <div class="p-4">
        @can(\App\Enums\PermissionEnum::MANAGE_PROGRAMS)
            <div class="pt-2">
                <button class="edit btn btn-primary mb-4" onclick="openAdd()">
                    <i class="mdi mdi-plus-circle-outline"></i>
                    Add custom field
                </button>
            </div>
        @endcan

        <div style="min-height: 300px;">
            <table class="table table-bordered table-striped" id="program-fields-table">
                <thead>
                <tr>
                    <th>Label</th>
                    <th>Key</th>
                    <th>Type</th>
                    <th>Required</th>
                    <th>Order</th>
                    <th>Actions</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>

    @component('dashboard.programs.form-fields.modal', ['program' => $program])
    @endcomponent
@endsection

@push('scripts')
    <script>
        const PROGRAM_ID = {{ $program->id }};
        $(function () {
            const table = $('#program-fields-table').DataTable({
                processing: true,
                serverSide: false,
                ajax: '{!! route('programs.form-fields.index', $program->id) !!}',
                dom: 'Blfrtip',
                responsive: true,
                buttons: ['copyHtml5', 'csvHtml5', 'pdfHtml5'],
                columns: [
                    {data: 'label'},
                    {data: 'field_key'},
                    {data: 'field_type'},
                    {
                        data: 'is_required',
                        render: function (data) {
                            return data ? 'Yes' : 'No';
                        }
                    },
                    {data: 'display_order'},
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
                deleteOpr(data.id, `dashboard/programs/${PROGRAM_ID}/form-fields/${data.id}`, table);
            });
        });

        openAdd = () => {
            const $modal = $('#field-modal');
            const $form = $modal.find('form');
            clearForm($form);
            $form[0].reset();
            $form.attr('action', `dashboard/programs/${PROGRAM_ID}/form-fields`);
            $form.find('input[name="_method"]').remove();
            toggleOptions();
            $('#options').val('');
            $('#options_hidden').val('');
            $modal.modal('show');
            $modal.removeClass('out').addClass('in');
        };

        openEdit = (data) => {
            const $modal = $('#field-modal');
            const $form = $modal.find('form');
            $form[0].reset();
            $form.find('input[name="_method"]').remove();
            $form.append('<input type="hidden" name="_method" value="PUT">');
            $form.attr('action', `dashboard/programs/${PROGRAM_ID}/form-fields/${data.id}`);
            clearErrors($modal);
            _fill($modal, data);
            if (Array.isArray(data.options)) {
                $('#options').val(data.options.join('\n'));
                $('#options_hidden').val(JSON.stringify(data.options));
            } else {
                $('#options').val('');
                $('#options_hidden').val('');
            }
            toggleOptions();
            $modal.modal('show');
            $modal.removeClass('out').addClass('in');
        };

        const toggleOptions = () => {
            const fieldType = $('#field_type').val();
            const $optionsWrapper = $('#field-options-wrapper');
            if (['select', 'checkbox'].includes(fieldType)) {
                $optionsWrapper.removeClass('d-none');
            } else {
                $optionsWrapper.addClass('d-none');
            }
        };

        const refreshOptions = () => {
            const values = ($('#options').val() || '').split('\n').map(item => item.trim()).filter(Boolean);
            $('#options_hidden').val(JSON.stringify(values));
        };

        $('#field_type').on('change', function () {
            toggleOptions();
        });

        $('#options').on('change keyup', function () {
            refreshOptions();
        });
    </script>
@endpush
