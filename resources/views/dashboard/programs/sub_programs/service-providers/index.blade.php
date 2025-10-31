@extends('dashboard.layout.main')
@section('content')
<div class="p-4">
    @can(\App\Enums\PermissionEnum::MANAGE_ServiceProvider)
        <div class="pt-2">
            <a
                class="edit btn btn-primary mb-4"
                href="javascript:" onclick="openAdd({!! $sub_program_id !!})">
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
                    <th>Service provider Name</th>
                    <th>Service provider Type</th>
                    <th>Actions</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

@include('dashboard.programs.sub_programs.service-providers.form',['sub_program_id'=>$sub_program_id,'service_providers'=>$service_providers])
@include('dashboard.programs.sub_programs.service-providers.delete_and_replace',['sub_program_id'=>$sub_program_id,'service_providers'=>$service_providers])

@stop
@push('scripts')
<script>
    $(function () {
        var table = $('#service-providers-table').DataTable({
            processing: true,
            serverSide: false,
            ajax: '{!! route('sub_programs.service-providers.index',$sub_program_id) !!}',
            dom: 'Blfrtip',
            responsive:true,
            buttons: [
                'copyHtml5',
                'csvHtml5',
                'pdfHtml5'
            ],
            columns: [
                {"data": "id"},
                {"data": "service_provider_type.service_provider.user","render":function (data) {
                        if(data) return data.first_name+" "+data.last_name
                        return "";
                    }
                },
                {"data": "service_provider_type.service_type","render":function (data) {
                        if(data) return data.name
                        return "";
                    }
                },
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],
        });

        table.on('click', '.destroy_and_replace', function () {
            $tr = $(this).closest('tr');
            if ($($tr).hasClass('child')) {
                $tr = $tr.prev('.parent')
            }
            var data = table.row($tr).data();
            console.log(data);
            openDestroyAndReplace(data);
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
            deleteOpr(data.id, `dashboard/sub_programs/` + data.sub_program_id+ `/service-providers/`+ data.id, table);
        });
    });
    openAdd = (sub_program_id) => {
        let $form = $('.add-form');
        let $modal = $('#add-modal');
        clearForm($form);
        $form[0].reset();
        $form.attr('action', `dashboard/sub_programs/`+ sub_program_id+'/service-providers');
        $('.add-form input[name="_method"]').remove();
        $modal.find('.modal-title').text(`Add Service Provider`);
        $modal.find('#alert').empty();
        $("#service_provider_type_id").empty();
        const serviceProviders = {!! json_encode($service_providers) !!};
        const selectedServiceProviderId = "{{ old('service_provider_type_id') }}";
        serviceProviders.forEach(function(option) {
                const fullName = option.service_provider.user.first_name + ' ' + option.service_provider.user.last_name;
                const serviceName = option.service_type.name;
                const optionText = `${fullName} - ${serviceName}`;
                const selected = (option.id === selectedServiceProviderId) ? 'selected' : '';
                $("#service_provider_type_id").append(`<option value="${option.id}" ${selected}>${optionText}</option>`);
        });
        clearErrors($modal);
        $modal.modal('show');
        $modal.removeClass('out');
        $modal.addClass('in');
    };

    openDestroyAndReplace = (data) => {
        let $modal = $('#add-modal');
        let $form = $('.add-form');
        $form[0].reset();
        $form.attr('action', `dashboard/sub_programs/` + data.sub_program_id+ `/service-providers/`+ data.id+ `/destroy-and-replace`);
        $form.append('<input type="hidden" name="_method" value="PUT">');
        $modal.find('.modal-title').text(`Edit service provider`);
        $modal.find('#alert').empty();
        $modal.find('#alert').append(`<div class="d-flex align-items-center">
                                <div class="border border-warning p-3">
                                    Enter the information of the service provider that will replace the current service provider.
                                    Note that the type of service must be the same.
                                </div>
                            </div>`);
        $("#service_provider_type_id").empty();
        const serviceProviders = {!! json_encode($service_providers) !!};
        const selectedServiceProviderId = "{{ old('service_provider_type_id') }}";
        serviceProviders.forEach(function(option) {
            if (option.service_type.id === data.service_provider_type.service_type.id) {
                const fullName = option.service_provider.user.first_name + ' ' + option.service_provider.user.last_name;
                const serviceName = option.service_type.name;
                const optionText = `${fullName} - ${serviceName}`;
                const selected = (option.id === selectedServiceProviderId) ? 'selected' : '';
                $("#service_provider_type_id").append(`<option value="${option.id}" ${selected}>${optionText}</option>`);
            }
        });
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
