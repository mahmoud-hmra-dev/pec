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
    {{--<div class="pt-2">
        <a
            class="edit btn btn-xs btn-primary mb-4"
            href="javascript:" onclick="openAdd('{!! $sub_program_id !!}')">
            <i class="mdi mdi-plus-circle-outline"></i>
            Add new Patient
        </a>
    </div>--}}

    <div style="min-height: 300px;">
        <table class="table table-bordered table-striped" id="patients-table">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Phone</th>
                    <th>Country</th>
                    <th>Hospital</th>
                    <th>Actions</th>

                </tr>
            </thead>
        </table>
    </div>
</div>

@component('dashboard.programs.sub_programs.patients.form',['patients'=>$patients,'sub_program_id'=>$sub_program_id,'physicians'=>$physicians,'nurses'=>$nurses , 'coordinators'=>$coordinators])
@endcomponent
@component('dashboard.programs.sub_programs.patients.edit_form',['patients'=>$patients,'sub_program_id'=>$sub_program_id,'physicians'=>$physicians,'nurses'=>$nurses , 'coordinators'=>$coordinators, 'hospitals'=>$hospitals,'doctors'=>$doctors , 'pharmacies'=>$pharmacies])
@endcomponent
@component('dashboard.programs.sub_programs.patients.view')
@endcomponent
@stop
@push('scripts')
<script>
    $(function () {
        var table = $('#patients-table').DataTable({
            processing: true,
            serverSide: false,
            ajax: '{!! route('sub_programs.patients.index',$sub_program_id) !!}',
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
                {"data": "patient.user.first_name","name":"patient.user.first_name"},
                {"data": "patient.user.last_name","name":"patient.user.last_name"},
                {"data": "patient.user.phone","name":"patient.user.phone"},
                {"data": "patient.user.country",'name':"patient.user.country" ,'render':function (data) {
                        if(data){
                            return data.name;
                        }
                        return '';
                    }
                },
                {"data": "patient.hospital",'name':"patient.hospital" ,'render':function (data) {
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
            deleteOpr(data.id, `dashboard/sub_programs/` + data.sub_program_id+ `/patients/`+ data.id, table);
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

    openAdd = (sub_program_id) => {
        let $form = $('.add-form');
        let $modal = $('#add-modal');
        clearForm($form);
        $form[0].reset();
        $form.attr('action', `dashboard/sub_programs/`+ sub_program_id+'/patients');
        $('.add-form input[name="_method"]').remove();
        $modal.find('.modal-title').text(`Add Patient`);
        clearErrors($modal);
        $("#sub_programs").empty();
        $modal.modal('show');
        $modal.removeClass('out');
        $modal.addClass('in');
    };
    openView = (data) => {
        const $modal = $('#view-modal');
        const $data = $('#data');

        const fields = [
            {label: 'First name', value: data.patient.user.first_name},
            {label: 'Last name', value: data.patient.user.last_name},
            {label: 'Phone', value: data.patient.user.phone},
            {label: 'Email', value: data.patient.user.email},
            {label: 'Personal email', value: data.patient.user.personal_email},
            {label: 'Country', value: data.patient.user.country.name},
            {label: 'City', value: data.patient.user.city},
            {label: 'Address', value: data.patient.user.address},
            {label: 'Street', value: data.patient.street},
            {label: 'Patient no', value: data.patient.patient_no},
            {label: 'Birth of date', value: data.patient.birth_of_date},
            {label: 'Height', value: data.patient.height},
            {label: 'Weight', value: data.patient.weight},
            {label: 'BMI', value: data.patient.BMI},
            {label: 'Is over weight', value: data.patient.is_over_weight == 1 ? '<span class="text-success">Yes</span>' : '<span class="text-danger">No</span>'},
            {label: 'Comorbidities', value: data.patient.comorbidities},
            {label: 'Gender', value: data.patient.gender},
            {label: 'Is eligible', value: data.patient.is_eligible == 1 ? '<span class="text-success">Yes</span>' : '<span class="text-danger">No</span>'},
            {label: 'Is eligible document', value: data.patient.is_eligible_document ? `<a target="_blank" href="{{ Storage::url('${data.patient.is_eligible_document}') }}">File</a>` : ''},
            {label: 'Is pregnant', value: data.patient.pregnant},
            {label: 'Reporter name', value: data.patient.reporter_name},
            {label: 'Hospital', value: data.patient.hospital ? data.patient.hospital.name : ''},
            {label: 'Pharmacy', value: data.patient.pharmacy ? data.patient.pharmacy.name : ''},
            {label: 'Discuss by', value: data.patient.discuss_by},
            {label: 'Medical conditions chronic diseases', value: data.patient.mc_chronic_diseases},
            {label: 'Medical conditions medications', value: data.patient.mc_medications},
            {label: 'Medical conditions surgeries', value: data.patient.mc_surgeries},
            {label: 'Family medical conditions', value: data.patient.fmc_chronic_diseases},
            {label: 'is e-Consent', value: data.is_consents == 1 ? '<span class="text-success">Yes</span>' : '<span class="text-danger">No</span>'},
            {label: 'Is e-Consent document', value: data.consent_document ? `<a target="_blank" href="{{ Storage::url('${data.consent_document}') }}">File</a>` : ''},

            {label: 'is their safety report', value: data.is_their_safety_report == 1 ? '<span class="text-success">Yes</span>' : '<span class="text-danger">No</span>'},
            {label: 'Download Safety Report Template', value: data.sub_program.program.client.safety_report_document ? `<a target="_blank" href="{{ Storage::url('${data.sub_program.program.client.safety_report_document}') }}">File</a>` : ''},

        ];

        const doctorTableHtml = `
        <table class="table table-bordered table-striped w-100">
            <thead>
                <tr>
                    <th>Doctor Name</th>
                    <th>Active Status</th>
                    <th>Creation Date</th>
                </tr>
            </thead>
            <tbody>
                ${data.patient.patient_doctors.map(function(doctor) {
            return `
                        <tr>
                            <td>${doctor.doctor.name}</td>
                            <td>${doctor.isActive ? 'Active' : 'Inactive'}</td>
                            <td>${doctor.created_at}</td>
                        </tr>
                    `;
        }).join('')}
            </tbody>
        </table>
    `;
        const saftey_reports = `
        <table class="table table-bordered table-striped w-100">
            <thead>
                <tr>
                    <th>Document</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Creation Date</th>
                </tr>
            </thead>
            <tbody>
                ${data.saftey_reports.map(function(document) {
            return `
                        <tr>
                            <td>${document.name ? `<a target="_blank" href="{{ Storage::url('${document.name}') }}">File</a>` : ''}</td>
                            <td>${document.title}</td>
                            <td>${document.description}</td>
                            <td>${document.created_at}</td>
                        </tr>
                    `;
        }).join('')}
            </tbody>
        </table>
    `;
        const documents = `
        <table class="table table-bordered table-striped w-100">
            <thead>
                <tr>
                    <th>Document Name</th>
                    <th>Type</th>
                    <th>Description</th>
                    <th>Creation Date</th>
                </tr>
            </thead>
            <tbody>
                ${data.patient.patient_documents.map(function(document) {
            return `
                        <tr>
                            <td>${document.name ? `<a target="_blank" href="{{ Storage::url('${document.name}') }}">File</a>` : ''}</td>
                            <td>${document.type}</td>
                            <td>${document.description}</td>
                            <td>${document.created_at}</td>
                        </tr>
                    `;
        }).join('')}
            </tbody>
        </table>
    `;

        fields.push(
            {label: 'Doctor table', value: doctorTableHtml}
        );
        fields.push(
            {label: 'Documents table', value: documents}
        );
        fields.push(
            {label: 'Safety reports table', value: saftey_reports}
        );

        const fieldHtml = fields.map(field => {
            if(field.label === 'Doctor table' || field.label === 'Documents table' || field.label ==="Safety reports table") {
                return `
                <div class="form-group col-md-12">
                    <label class="required-label">${field.label}: </label>
                    <span>${field.value}</span>
                </div>
            `;
            } else {
                return `
                <div class="form-group col-md-6">
                    <label class="required-label">${field.label}: </label>
                    <span>${field.value}</span>
                </div>
            `;
            }
        }).join('');

        $data.html(fieldHtml);

        clearErrors($modal);
        $modal.modal('show');
        $modal.removeClass('out');
        $modal.addClass('in');
    };

    openEdit = (data) => {
        let $edit_modal = $('#edit-modal');
        let $patient = $('#edit_patient');
        let $user = $('#edit_user');
        let $sub_program_patient = $('.edit_sub_program_patient');
        let $form = $('.add-form');
        $form[0].reset();
        $form.attr('action', `dashboard/sub_programs/` + data.sub_program_id+ `/patients/`+ data.id);
        $form.append('<input type="hidden" name="_method" value="PUT">');
        $edit_modal.find('.modal-title').text(`Edit Patient`);

        clearErrors($user);
        clearErrors($patient);
        clearErrors($sub_program_patient);

        _fill($patient, data.patient);
        _fill($user, data.patient.user);
        _fill($sub_program_patient, data);


        $edit_modal.find('input[type="hidden"][name="user_id"]').val(data.patient.user.id);
        $edit_modal.find('input[type="hidden"][name="patient_id"]').val(data.patient.id);
        $edit_modal.find('input[type="text"][name="street"]').val(data.patient.street);


        const patient_country_providers = data.patient_country_providers;

        $('#edit_nurse').val(null).trigger('change');
        $('#edit_coordinator').val(null).trigger('change');

        $("#sub_programs").empty();



        $('#documents').find('[data-repeater-item]').slice(0).remove();
        $.each(data.patient.patient_documents, function (index, item) {
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
                                            <select class="form-control" id="type" name="documents[${index + 1}][type]" >
                                                @foreach($types as $type)<option value="{{$type->name}}">{{$type->name}}</option>
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

        $('#safety_reports').find('[data-repeater-item]').slice(0).remove();
        $.each(data.saftey_reports, function (index, item) {
            let $newItem = $(`<div data-repeater-item>
                                    <div class="row" >
                                        <div class="form-group col-sm-4">
                                            <label class="required-label">Name</label>
                                            <div class="custom-file">
                                                <input class="custom-file-input" type="file" name="name"  id="name" aria-describedby="name" >
                                                <label class="custom-file-label" for="name">Attach Document</label>
                                            </div>
                                            ${item.name ? `<a target="_blank" href="{{ Storage::url('${item.name}') }}">File</a>` : ''}
                                            <input name="safety_reports[${index + 1}][id]" id="id"  type="hidden" class="form-control" value="${item.id}">
                                        </div>

                                        <div class="form-group col-sm-4">
                                            <label class="required-label">Title</label>
                                            <input name="safety_reports[${index + 1}][title]" id="title"  type="text" class="form-control" value="${item.title}">
        </div>
        <div class="form-group col-sm-4">
            <label for="description" class="label-required">Description</label>
            <textarea   name="safety_reports[${index + 1}][description]" id="description" type="text" class="form-control">${item.description}</textarea>
                                        </div>
                                        <div class="form-group col-sm-2">
                                            <input class="btn btn-danger btn-block" data-repeater-delete
                                                   type="button" value="Delete" />
                                        </div>
                                    </div>
                                </div>`)
                .clone();

            $('#safety_reports [data-repeater-list]').append($newItem);
        });

        $('#is_eligible_document').empty();
        $('#is_eligible_document').append(`${data.patient.is_eligible_document ? `<a target="_blank" href="{{ Storage::url('${data.patient.is_eligible_document}') }}">File</a>` : ''}`);

        $('#consent_document_file').empty();
        $('#consent_document_file').append(`${data.consent_document ? `<a target="_blank" href="{{ Storage::url('${data.consent_document}') }}">File</a>` : ''}`);

        $('#safety_report_document_file').empty();
        $('#safety_report_document_file').append(`${data.sub_program.program.client.safety_report_document ? `<a target="_blank" href="{{ Storage::url('${data.sub_program.program.client.safety_report_document}') }}">Download Safety Report Template</a>` : ''}`);

        var doctor = $("#doctor_id").select2({
            width: '100%',
        });
        data.patient.patient_doctors.forEach(function (single) {
            if(single.isActive === 1){
                doctor.val(single.doctor_id).trigger('change');
            }
        });

        if(data.sub_program.has_visits && data.sub_program.visit_every_day > 0){
            $('#nurse_visits').show();
            $.ajax({
                url: "{{ route('enroll_patient.nurses') }}/" + data.sub_program.id,
                type: "GET",
                dataType: "json",
                success: function (response) {
                    var nurse = $("#edit_nurse").select2({
                        width: '100%',
                    });
                    nurse.empty();
                    var nurses = response.nurses;
                    nurses.forEach(function (single) {
                        var option = new Option(single.service_provider_type.service_provider.user.first_name + ' ' + single.service_provider_type.service_provider.user.last_name, single.id);
                        nurse.append(option);
                    });

                    nurse.select2();
                    nurse.val([]).trigger('change');
                    patient_country_providers.forEach(function(option) {
                        if(option.country_service_provider.service_provider_type.service_type.name === 'Nurse'){
                            nurse.val(option.country_service_provider_id).trigger('change');
                        }
                    });
                }
            });
        } else  {
            $('#nurse_visits').hide();
        }

        $edit_modal.find('input[type="hidden"][name="has_visits"]').val(data.sub_program.has_visits);
        $edit_modal.find('input[type="hidden"][name="visit_every_day"]').val(data.sub_program.visit_every_day);

        $edit_modal.find('input[type="hidden"][name="has_calls"]').val(data.sub_program.has_calls);
        $edit_modal.find('input[type="hidden"][name="call_every_day"]').val(data.sub_program.call_every_day);

        if(data.sub_program.has_calls && data.sub_program.call_every_day > 0){
            $('#coordinator_calls').show();
            $.ajax({
                url: "{{ route('enroll_patient.coordinators') }}/" + data.sub_program.id,
                type: "GET",
                dataType: "json",
                success: function (response) {
                    var coordinator = $("#edit_coordinator").select2();

                    coordinator.empty();

                    var coordinators = response.coordinators;
                    coordinators.forEach(function (single) {
                        var option = new Option(single.service_provider_type.service_provider.user.first_name + ' ' + single.service_provider_type.service_provider.user.last_name, single.id);
                        coordinator.append(option);
                    });

                    coordinator.select2();
                    coordinator.val([]).trigger('change');
                    patient_country_providers.forEach(function(option) {
                        if(option.country_service_provider.service_provider_type.service_type.name === 'Program Coordinator'){
                            coordinator.val(option.country_service_provider_id).trigger('change');
                        }
                    });

                }
            });
        } else {
            $('#coordinator_calls').hide();
        }
        $('#country_id').on('change', function (event) {
            event.preventDefault();
            let country_id = $(this).val();
            let program_initial = data.sub_program.program_initial;
            let map_id =  data.sub_program.program.map_id;
            let patient_id  = data.patient_id;
            console.log(program_initial);
            $.ajax({
                url: "{{ route('enroll_patient.generate_patient_no') }}/" + country_id,
                type: "GET",
                dataType: "json",
                data: {
                    program_initial: program_initial,
                    map_id: map_id,
                    patient_id: patient_id,
                },
                success: function (data) {
                    let patient_no = data.patient_no;
                    $edit_modal.find('input[type="text"][name="patient_no"]').val(patient_no);
                },
            });
        });


        if ($('#is_their_safety_report').is(':checked')) {
            $('#safety_reports').show();
        } else {
            $('#safety_reports').hide();
        }
        if ($('#is_eligible').is(':checked')) {
            // Show additional fields
            $('.is_eligible').show();
            $('.is_not_eligible').hide();
        } else {
            // Hide additional fields
            $('.is_eligible').hide();
            $('.is_not_eligible').show();
        }
        if($('#gender').val() === "{{\App\Enums\GenderEnum::FEMALE}}")
            $('#is_pregnant_container').show();
        else
            $('#is_pregnant_container').hide();

        if ( $('#is_consents').is(':checked')) {
            $('#consent_document').show();
        } else {
            $('#consent_document').hide();
        }

        $edit_modal.modal('show');
        $edit_modal.removeClass('out');
        $edit_modal.addClass('in');
    };



    $('#add-modal').on('hidden.bs.modal',function (event) {
        $(this).removeClass('in');
        $(this).addClass('out');
    });

</script>
<script>

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

    {{--$('#country_id').on('change',function (event) {
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
    });--}}
</script>
@endpush
