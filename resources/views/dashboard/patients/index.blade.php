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
        @can(\App\Enums\PermissionEnum::MANAGE_PATIENTS)
            <div class="pt-2">
                <a
                    class="edit btn btn-primary mb-4"
                    href="javascript:" onclick="openAdd()">
                    <i class="mdi mdi-plus-circle-outline"></i>
                    Add new Patient
                </a>
            </div>
        @endcan


    <div style="min-height: 300px;">
        <table class="table table-bordered table-striped" id="patients-table">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Phone</th>
                    <th>Country</th>
                    {{--<th>City</th>
                    <th>Address</th>--}}
                    <th>Hospital</th>
                    <th>Actions</th>

                </tr>
            </thead>
        </table>
    </div>
</div>

@component('dashboard.patients.form',['hospitals'=>$hospitals])
@endcomponent
@component('dashboard.patients.view')
@endcomponent
@stop
@push('scripts')
<script>
    $(function () {
        var table = $('#patients-table').DataTable({
            processing: true,
            serverSide: false,
            ajax: '{!! route('patients.index') !!}',
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
                {"data": "user.first_name","name":"user.first_name"},
                {"data": "user.last_name","name":"user.last_name"},
                {"data": "user.phone","name":"user.phone"},
                {"data": "user.country.name",'name':"user.country.name" ,'render':function (data) {
                        if(data){
                            return data;
                        }
                        return '';
                    }
                },
                {"data": "hospital",'name':"hospital" ,'render':function (data) {
                        if(data){
                            return data.name;
                        }
                        return '';
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
            deleteOpr(data.id, `dashboard/users/patients/` + data.id, table);
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

        $('.add-form').on('submit', function (event) {
            SaveItem(event, this, $(this).attr('action'), table);
        });

    });

    openAdd = () => {
        let $form = $('.add-form');
        let $modal = $('#add-modal');
        clearForm($form);
        $form[0].reset();
        $form.attr('action', `dashboard/users/patients`);
        $('.add-form input[name="_method"]').remove();
        $modal.find('.modal-title').text(`Add Patient`);
        $modal.find('input[type="hidden"][name="id"]').val(null);
        clearErrors($modal);
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
            {label: 'Street', value: data.street},
            {label: 'Patient no', value: data.patient_no},
            {label: 'Birth of date', value: data.birth_of_date},
            {label: 'Height', value: data.height},
            {label: 'Weight', value: data.weight},
            {label: 'BMI', value: data.BMI},
            {label: 'Is over weight', value: data.is_over_weight == 1 ? '<span class="text-success">Yes</span>' : '<span class="text-danger">No</span>'},
            {label: 'Comorbidities', value: data.comorbidities},
            {label: 'Gender', value: data.gender},
            {label: 'Is eligible', value: data.is_eligible == 1 ? '<span class="text-success">Yes</span>' : '<span class="text-danger">No</span>'},
            {label: 'Why is not eligible', value: data.is_not_eligible},
            {label: 'Is pregnant', value: data.is_pregnant == 1 ? '<span class="text-success">Yes</span>' : '<span class="text-danger">No</span>'},
            {label: 'Reporter name', value: data.reporter_name},
            {label: 'Hospital', value: data.hospital.name},
            {label: 'Discuss by', value: data.discuss_by},
        ];

        const fieldHtml = fields.map(field => `
        <div class="form-group col-md-6">
            <label class="required-label">${field.label}: </label>
            <span>${field.value}</span>
        </div>
    `).join('');
        $data.html(fieldHtml);
        clearErrors($modal);
        $modal.modal('show');
        $modal.removeClass('out');
        $modal.addClass('in');
    };

    openEdit = (data) => {
        let $modal = $('#add-modal');
        let $user = $('#user');
        let $patient = $('#patient');
        let $street = $('#street');
        let $form = $('.add-form');
        $form[0].reset();
        $form.attr('action', `dashboard/users/patients/`+ data.id);
        $form.append('<input type="hidden" name="_method" value="PUT">');
        $modal.find('.modal-title').text(`Edit Patient`);
        clearErrors($user);
        clearErrors($patient);
        clearErrors($street);
        clearErrors($modal);
        _fill($user, data.user);
        _fill($patient, data);
        _fill($street, data);
        $modal.modal('show');
        $modal.removeClass('out');
        $modal.addClass('in');
    };

    $('#add-modal').on('hidden.bs.modal',function (event) {
        $(this).removeClass('in');
        $(this).addClass('out');
    });

</script>
<script>
    $('#close-img').on('click',function (event) {
        var id = $(this).attr('data-id');
        deleteOpr(id, `dashboard/users/deleteImage/` + id, table)
    })

    $('#is_pregnant_container').hide();
    $('#gender').on('change',function (event) {
        if($(this).val() === "{{\App\Enums\GenderEnum::FEMALE}}")
            $('#is_pregnant_container').show();
        else
            $('#is_pregnant_container').hide();
    })
    $("#weight, #height").on('input', function() {
        let weight = $('#weight').val();
        let height = $('#height').val();
        if (weight != "" && height != "") {
            height = height / 100;
            let BMI = weight / (height * height);
            BMI = parseFloat(BMI).toFixed(2);
            $("#BMI").val(BMI);
            if (BMI > 27) {
                $("#is_over_weight").prop("checked", true);
            } else {
                $("#is_over_weight").prop("checked", false);
            }
        }
    });



    {{--
    $('#country_id').on('change',function (event) {
        event.preventDefault();
        let country_id = $(this).val();
        let country_code = $(this).find("option:selected").data('country_code');
        $('#country_code').val(country_code);
        $.ajax({
            url:"{{route('sub_programs_get_by_country')}}/"+country_id,
            type:"GET",
            dataType:"json",
            success:function (data) {
                $("#sub_program_id").empty();
                let sub_programs = data.sub_programs;
                let options = '<option selected disabled>Select a program</option>'
                sub_programs.map((single) => {
                    options += `<option value="${single.id}">${single.name} - ${single.drug.name}</option>`
                })
                $("#sub_program_id").append(options);
            } ,
            error:function () {
                $("#sub_program_id").empty();
                $("#sub_program_id").append(`<option selected disabled>Select a country for the patient first</option>`);
            }
        });
        --}}
</script>
@endpush
