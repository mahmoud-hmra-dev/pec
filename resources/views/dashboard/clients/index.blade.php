@extends('dashboard.layout.main')
@section('content')
    <div class="p-4">
        @can(\App\Enums\PermissionEnum::MANAGE_CLIENTS)
            <div class="pt-2">
                <a
                    class="edit btn  btn-primary mb-4"
                    href="javascript:" onclick="openAdd()">
                    <i class="mdi mdi-plus-circle-outline"></i>
                    Add new Client
                </a>
            </div>
        @endcan


        <div style="min-height: 300px;">
            <table class="table table-bordered table-striped" id="clients-table">
                <thead>
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Address</th>
                    <th>Actions</th>

                </tr>
                </thead>
            </table>
        </div>
    </div>

    @component('dashboard.clients.form')
    @endcomponent
    @component('dashboard.clients.view')
    @endcomponent

@stop
@push('scripts')

    <script>

        $(function () {
            var table = $('#clients-table').DataTable({
                processing: true,
                serverSide: false,
                ajax: '{!! route('clients.index') !!}',
                dom: 'Blfrtip',
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
                    {"data": "client_name"},
                    {"data": "client_address"},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
            });


            table.on('click', '.view', function () {
                $tr = $(this).closest('tr');
                if ($($tr).hasClass('child')) {
                    $tr = $tr.prev('.parent')
                }
                var data = table.row($tr).data();
                openView(data);
            });

            table.on('click', '.edit', function () {
                $tr = $(this).closest('tr');
                if ($($tr).hasClass('child')) {
                    $tr = $tr.prev('.parent')
                }
                var data = table.row($tr).data();
                openEdit(data);
            });
            table.on('click', '.delete', function () {
                $tr = $(this).closest('tr');
                if ($($tr).hasClass('child')) {
                    $tr = $tr.prev('.parent')
                }
                var data = table.row($tr).data();
                deleteOpr(data.id, `dashboard/users/clients/` + data.id, table);
            });
            $('.add-form').on('submit', function (event) {
                SaveItem(event, this, $(this).attr('action'), table);
            });
        });
        openAdd = () => {
            let $form = $('.add-form');
            let $modal = $('#add-modal');
            let $user = $('#user');
            let $client = $('#client');
            clearForm($form);
            $form[0].reset();
            $form.attr('action', `dashboard/users/clients`);
            $('.add-form input[name="_method"]').remove();
            $modal.find('.modal-title').text(`Add client`);
            $modal.find('input[type="hidden"][name="id"]').val(null);
            clearErrors($user);
            clearErrors($client);
            clearErrors($modal);
            $('#documents').find('[data-repeater-item]').slice(0).remove();
            let $newItem = $(`<div data-repeater-item>
                                    <div class="row" >
                                        <div class="form-group col-sm-4">
                                            <label class="required-label">Name</label>
                                            <div class="custom-file">
                                                <input class="custom-file-input" type="file" name="documents[0][name]"  id="name" aria-describedby="name" >
                                                <label class="custom-file-label" for="name">Attach Document</label>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <label class="required-label">Type</label>
                                            <select class="form-control" id="document_type_id" name="documents[0][document_type_id]" >
                                                @foreach($types as $type)<option value="{{$type->id}}">{{$type->name}}</option>
                                                @endforeach
            </select>
        </div>
        <div class="form-group col-sm-4">
            <label for="description" class="label-required">Description</label>
            <textarea   name="documents[0][description]" id="description" type="text" class="form-control"></textarea>
                                            </div>

                                        </div>
                                    </div>`)
                .clone();
            $('#documents [data-repeater-list]').append($newItem);


            $modal.modal('show');
            $modal.removeClass('out');
            $modal.addClass('in');
        };

        openEdit = (data) => {
            let $modal = $('#add-modal');
            let $user = $('#user');
            let $client = $('#client');
            let $form = $('.add-form');
            $form[0].reset();
            $form.attr('action', `dashboard/users/clients/` + data.id);
            $form.append('<input type="hidden" name="_method" value="PUT">');
            $modal.find('.modal-title').text(`Edit client`);
            clearErrors($user);
            clearErrors($client);
            clearErrors($modal);

            $('#safety_report_document_file').empty();
            $('#safety_report_document_file').append(`${data.safety_report_document ? `<a target="_blank" href="{{ Storage::url('${data.safety_report_document}') }}">File</a>` : ''}`);

            $('#documents').find('[data-repeater-item]').slice(0).remove();
            _fill($user, data.user);
            _fill($client, data);
            $.each(data.documents, function (index, item) {
                let $newItem = $(`<div data-repeater-item>
                                    <div class="row" >
                                        <div class="form-group col-sm-4">
                                            <label class="required-label">Name</label>
                                            <div class="custom-file">
                                                <input class="custom-file-input" type="file" name="name"  id="name" aria-describedby="name" >
                                                <label class="custom-file-label" for="name">Attach Document</label>
                                            </div>
                                            ${item.name ? `<a target="_blank" href="{{ Storage::url('${item.name}') }}">File</a>` : ''}
                                            <input name="documents[${index + 1}][id]" id="id"  type="hidden" class="form-control" value="${item.id}">
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <label class="required-label">Type</label>
                                            <select class="form-control" id="document_type_id" name="documents[${index + 1}][document_type_id]" >
                                                @foreach($types as $type)<option value="{{$type->id}}">{{$type->name}}</option>
                                                @endforeach
                </select>
            </div>
            <div class="form-group col-sm-4">
                <label for="description" class="label-required">Description</label>
                <textarea   name="documents[${index + 1}][description]" id="description" type="text" class="form-control">${item.description}</textarea>
                                        </div>
                                        <div class="form-group col-sm-2">
                                            <input class="btn btn-danger btn-block" data-repeater-delete
                                                   type="button" value="Delete" />
                                        </div>
                                    </div>
                                </div>`)
                    .clone();
                $('#documents [data-repeater-list]').append($newItem);
                $('select[name="documents[' + index + '][document_type_id]"] option[value="' + item.document_type_id + '"]').attr('selected', true);
            });

            $modal.modal('show');
            $modal.removeClass('out');
            $modal.addClass('in');
        };

        openView = (data) => {
            const $modal = $('#view-modal');
            const $data = $('#data');
            const fields = [
                {label: 'First name', value: data.user.first_name},
                {label: 'Last name', value: data.user.last_name},
                {label: 'Phone', value: data.user.phone},
                {label: 'Email', value: data.user.email},
                {label: 'Country', value: data.user.country.name},
                {label: 'City', value: data.user.city},
                {label: 'Client Name', value: data.client_name},
                {label: 'Client address', value: data.client_address},
                {label: 'Safety report document', value: data.safety_report_document ? `<a target="_blank" href="{{ Storage::url('${data.safety_report_document}') }}">File</a>` : ''},
            ];

            const documentsHtml = data.documents.map(document => ` <div class="form-group col-md-4"> &#10070; <label class="required-label">Name </label>
      <span>${document.name ? `<a target="_blank" href="{{ Storage::url('${document.name}') }}">File</a>` : ''}</span>
    </div>
    <div class="form-group col-md-4">
      <label class="required-label">Type: </label>
      <span>${document.type.name}</span>
    </div>
    <div class="form-group col-md-4">
      <label class="required-label">Description: </label>
      <span>${document.description}</span>
    </div>
  `);
            const fieldHtml = fields.map(field => `<div class="form-group col-md-6"><label class="required-label">${field.label}: </label><span>  ${field.value}</span></div>`).join('');
            const allHtml = ` ${fieldHtml}  <div class="form-group col-md-12"><label class="required-label">Clinent documents</label></div> ${documentsHtml.join('')}`;
            $data.html(allHtml);
            clearErrors($modal);
            $modal.modal('show');
            $modal.removeClass('out');
            $modal.addClass('in');
        };
        $('#add-modal').on('hidden.bs.modal', function (event) {
            $(this).removeClass('in');
            $(this).addClass('out');
        });

    </script>
@endpush
