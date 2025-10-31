@extends('dashboard.layout.main')
@section('content')
<div class="p-4">
    @can(\App\Enums\PermissionEnum::MANAGE_ServiceProvider)
        <div class="pt-2">
            <button
                class="edit btn btn-primary mb-4"
                href="javascript:" onclick="openAdd('{!! $service_provider_id !!}')">
                <i class="mdi mdi-plus-circle-outline"></i>
                Add new Service provider type
            </button>
        </div>
    @endcan


    <div style="min-height: 300px;">
        <table class="table table-bordered table-striped" id="service_provider_types-table">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Service type</th>
                    <th>Sub programs</th>
                    <th>Actions</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

@component('dashboard.service-providers.service_provider_types.form',['service_provider_id'=>$service_provider_id,'sub_programs'=>$sub_programs])
@endcomponent

@stop
@push('scripts')
<script>
    $(function () {
        const $serviceTypeSelect = $('#service_type_id');
        const $customTypeGroup = $('#service_type_name_group');

        const toggleCustomType = () => {
            const selected = $serviceTypeSelect.find('option:selected');
            const isCustom = selected.data('custom');

            if (isCustom) {
                $customTypeGroup.show();
                $customTypeGroup.find('input').attr('required', true);
            } else {
                $customTypeGroup.hide();
                $customTypeGroup.find('input').removeAttr('required').val('');
            }
        };

        $serviceTypeSelect.on('change', toggleCustomType);
        toggleCustomType();

        window.__serviceProviderTypeSelect = $serviceTypeSelect;
        window.toggleCustomServiceType = toggleCustomType;

        var table = $('#service_provider_types-table').DataTable({
            processing: true,
            serverSide: false,
            ajax: '{!! route('service-providers.service_provider_types.index',$service_provider_id) !!}',
            dom: 'Blfrtip',
            responsive:true,
            buttons: [
                'copyHtml5',
                'csvHtml5',
                'pdfHtml5'
            ],
            columns: [
                {"data": "id"},
                {"data": "service_type.name"},
                {"data": "country_services_provider_text"},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],
        });

        $('.add-form').on('submit', function (event) {
            SaveItem(event, this, $(this).attr('action'), table);
        });

        window.ensureServiceTypeOption = function (data) {
            if (data.service_type && data.service_type.id) {
                if (!$serviceTypeSelect.find('option[value="' + data.service_type.id + '"]').length) {
                    const newOption = new Option(data.service_type.name, data.service_type.id, true, true);
                    $serviceTypeSelect.append(newOption).trigger('change');
                }
            }
        };

        table.on('click', '.delete', function () {
            $tr = $(this).closest('tr');
            if ($($tr).hasClass('child')) {
                $tr = $tr.prev('.parent')
            }
            var data = table.row($tr).data();
            deleteOpr(data.id, `dashboard/service-providers/` + data.service_provider.id+ `/service_provider_types/`+ data.id, table);
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
        const $serviceTypeSelect = window.__serviceProviderTypeSelect;
        $serviceTypeSelect.val($serviceTypeSelect.find('option:first').val()).trigger('change');
        if (typeof window.toggleCustomServiceType === 'function') {
            window.toggleCustomServiceType();
        }
        $form.attr('action', `dashboard/service-providers/`+ serviceProviderId+'/service_provider_types');
        $('.add-form input[name="_method"]').remove();
        $('.add-form input[name="service_provider_id"]').remove();
        $modal.find('.modal-title').text(`Add Service provider type`);
        clearErrors($modal);
        $form.append('<input type="hidden" name="service_provider_id" value="'+serviceProviderId+'">');
        $modal.modal('show');
        $modal.removeClass('out');
        $modal.addClass('in');
    };

    openEdit = (data) => {
        let $modal = $('#add-modal');
        let $form = $('.add-form');
        let $data = $('#data');

        $form[0].reset();
        $('.add-form input[name="_method"]').remove();
        $('.add-form input[name="service_provider_id"]').remove();
        $form.append('<input type="hidden" name="_method" value="PUT">');
        $form.attr('action', `dashboard/service-providers/` + data.service_provider.id+ `/service_provider_types/`+ data.id);
        $modal.find('.modal-title').text(`Edit Service provider type`);
        $modal.find('#afm_btnSaveIt').text(`Update`);
        clearErrors($modal);
        clearErrors($data);

        $form.append('<input type="hidden" name="service_provider_id" value="'+data.service_provider_id+'">');
        const $serviceTypeSelect = window.__serviceProviderTypeSelect;
        ensureServiceTypeOption(data);
        _fill($data, data);
        if (typeof window.toggleCustomServiceType === 'function') {
            window.toggleCustomServiceType();
        }

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
