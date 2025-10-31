@extends('dashboard.layout.main')
@section('content')
<div class="p-4">
    @can(\App\Enums\PermissionEnum::MANAGE_ServiceProvider)
        <div class="pt-2">
            <a
                class="edit btn btn-primary mb-4"
                href="javascript:" onclick="openAdd()">
                <i class="mdi mdi-plus-circle-outline"></i>
                Add new service provider
            </a>
        </div>
    @endcan


    <div style="min-height: 300px;">
        <table class="table table-bordered table-striped" id="service-providers-table">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Contract type</th>
                    <th>Contract rate price</th>
                    <th>Contract rate price per</th>
                    {{--<th>Attach contract</th>
                    <th>Attach cv</th>
                    <th>City</th>--}}
                    <th>Country</th>
                    <th>Actions</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

@include('dashboard.service-providers.form')
@include('dashboard.service-providers.view')
@stop
@push('scripts')

<script>
    $(document).ready(function() {
        $('.select2').each(function() {
            $(this).select2({
                dropdownParent: $(this).parent(),
                width: '100%',
            })
        })
    });
    $(function () {
        var table = $('#service-providers-table').DataTable({
            processing: true,
            serverSide: false,
            ajax: '{!! route('service-providers.index') !!}',
            dom: 'Blfrtip',
            buttons: [
                'copyHtml5',
                'csvHtml5',
                'pdfHtml5'
            ],
            columns: [
                {"data": "id"},
                {"data": "user.first_name"},
                {"data": "user.last_name"},
                {"data": "contract_type"},
                {"data": "contract_rate_price"},
                {"data": "contract_rate_price_per"},
                {{--{"data": "attach_contract","render":function (data) {return data ? `<a target="_blank" href="{{ asset(Storage::url('${data}')) }}">Contract</a>` : '';}},
                {"data": "attach_cv","render":function (data) {return data ? `<a target="_blank" href="{{ asset(Storage::url('${data}')) }}">CV</a>` : '';}},
                {"data": "city"},--}}
                {"data": "country.name"},
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
        $('.add-form').on('submit', function (event) {
            SaveItem(event, this, $(this).attr('action'), table);
        });

        table.on('click', '.delete', function () {
            $tr = $(this).closest('tr');
            if ($($tr).hasClass('child')) {
                $tr = $tr.prev('.parent')
            }
            var data = table.row($tr).data();
            deleteOpr(data.id, `dashboard/service-providers/` + data.id, table);
        });
    });
    openAdd = () => {
        let $form = $('.add-form');
        let $modal = $('#add-modal');
        let $user = $('#user');
        let $client = $('#client');
        clearForm($form);
        $form[0].reset();
        $form.attr('action', `dashboard/service-providers`);
        $('.add-form input[name="_method"]').remove();
        $modal.find('.modal-title').text(`Add service provider`);
        clearErrors($user);
        clearErrors($client);
        clearErrors($modal);
        $('#attach_cv_file').empty();
        $('#attach_contract_file').empty();
        $('#certificates_list').find('[data-repeater-item]').slice(0).remove();
        $('.custom-file-input').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);
        });
        var service_types = $("#service_types").select2({
            width: '100%',
        });
        var values = [];
        service_types.val(values).trigger('change');
        $modal.modal('show');
        $modal.removeClass('out');
        $modal.addClass('in');
    };

    openEdit = (data) => {
        let $modal = $('#add-modal');
        let $user = $('#user');
        let $provider = $('#service-provider');
        let $form = $('.add-form');
        $form[0].reset();
        $form.attr('action', `dashboard/service-providers/` + data.id);
        $form.append('<input type="hidden" name="_method" value="PUT">');
        $modal.find('.modal-title').text(`Edit service provider`);
        clearErrors($user);
        clearErrors($provider);
        clearErrors($modal);
        $('#certificates_list').find('[data-repeater-item]').slice(0).remove();
        _fill($user, data.user);
        _fill($provider, data);
        $('#attach_cv_file').empty();
        $('#attach_cv_file').append(`${data.attach_cv ? `<a target="_blank" href="{{ Storage::url('${data.attach_cv}') }}">File</a>` : ''}`);
        $('#attach_contract_file').empty();
        $('#attach_contract_file').append(`${data.attach_contract ? `<a target="_blank" href="{{ Storage::url('${data.attach_contract}') }}">File</a>` : ''}`);

        $.each(data.certificates, function (index, item) {
            let $newItem = $(`<div data-repeater-item>
                                    <div class="row" >
                                        <div class="form-group col-sm-6">
                                           <div class="custom-file">
                                                <input class="custom-file-input" type="file" name="certificate"  id="certificate" aria-describedby="certificate" >
                                                <label class="custom-file-label" for="certificate">Attach certificate</label>
                                            </div>
                                            ${item.url ? `<a target="_blank" href="{{ Storage::url('${item.url}') }}">File</a>` : ''}
                                            <input name="certificates_list[${index + 1}][id]" id="id"  type="hidden" class="form-control" value="${item.id}">
                                        </div>
                                        <div class="form-group col-sm-2">
                                            <input class="btn btn-danger btn-block" data-repeater-delete
                                                   type="button" value="Delete" />
                                        </div>
                                    </div>
                                </div>`)
                .clone();
            $('#certificates_list [data-repeater-list]').append($newItem);
        });

        var service_types = $("#service_types").select2({
            width: '100%',
        });
        var values = [];
        $.each(data.service_types, function (index, item) {
            values.push(item.id);
        });
        service_types.val(values).trigger('change');
        if($('#contract_type').val() === '{{\App\Enums\ContractTypeEnum::Freelancer}}'){
            $('.freelancer').show();
        } else  {
            $('.freelancer').hide();
        }
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
            {label: 'Personal email', value: data.user.personal_email},
            {label: 'Country', value: data.user.country.name},
            {label: 'City', value: data.user.city},
            {label: 'Address', value: data.user.address},
            {label: 'Contract Type', value: data.contract_type},
            {label: 'Contract Rate Price ', value: data.contract_rate_price},
            {label: 'Contract Rate Price per', value: data.contract_rate_price_per},
            {label: 'Attach Contract', value: data.attach_contract ? `  <a target="_blank" href="{{ Storage::url('${data.attach_contract}') }}">Contract</a>` : ''},
            {label: 'Attach CV', value: data.attach_cv ? `  <a target="_blank" href="{{ Storage::url('${data.attach_cv}') }}">CV</a>` : ''},
        ];
        const certificatesHtml = data.certificates.map((item, index) => `</label><span>${item.url ? `  <a target="_blank" href="{{ Storage::url('${item.url}') }}">Certificate_${index}</a>` : ''} </span>`);

        const service_types = data.service_types.map(item => `  </label><span>${item.name}</span>`);
        const fieldHtml = fields.map(field => `<div class="form-group col-md-6"><label class="required-label">${field.label}: </label><span>  ${field.value}</span></div>`).join('');
        const allHtml = ` ${fieldHtml} <div class="form-group col-md-12"><label class="required-label">Service types: ${service_types.join(' , ')}</label></div>  <div class="form-group col-md-12"><label class="required-label">Certificates : ${certificatesHtml.join(' , ')}</label></div> `;
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

    $('#contract_type').on('change', function (event) {
        event.preventDefault();
        let contract_type = $(this).val();
        if(contract_type === '{{\App\Enums\ContractTypeEnum::Freelancer}}'){
            $('.freelancer').show();
        } else  {
            $('.freelancer').hide();
        }
    });
</script>
@endpush
