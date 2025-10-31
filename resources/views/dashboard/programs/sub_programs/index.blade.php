@extends('dashboard.layout.main')
@section('content')
<div class="p-4">

    @can(\App\Enums\PermissionEnum::MANAGE_SUBPROGRAMS)
        <div class="pt-2">
            <button
                class="edit btn btn-primary mb-4"
                href="javascript:" onclick="openAdd('{!! $program_id !!}')">
                <i class="mdi mdi-plus-circle-outline"></i>
                Add new sub program
            </button>
        </div>
    @endcan


    <div style="min-height: 300px;">
        <table class="table table-bordered table-striped" id="sub_programs-table">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Program</th>
                    <th>Country</th>
                    <th>Drug</th>
                    <th>Actions</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

@component('dashboard.programs.sub_programs.form',['program_drugs'=>$program_drugs,'program_countries'=>$program_countries])
@endcomponent
@component('dashboard.programs.sub_programs.view')
@endcomponent
@stop
@push('scripts')
<script>
    $(function () {
        var table = $('#sub_programs-table').DataTable({
            processing: true,
            serverSide: false,
            ajax: '{!! route('programs.sub_programs.index',$program_id) !!}',
            dom: 'Blfrtip',
            responsive:true,
            buttons: [
                'copyHtml5',
                'csvHtml5',
                'pdfHtml5'
            ],
            columns: [
                {"data": "id"},
                {"data": "name"},
                {"data": "program.name"},
                {"data": "country.name"},
                {"data": "drug",'name':"drug" ,'render':function (data) {
                        if(data){
                            return data.name;
                        }
                        return '';
                    }
                },
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
            deleteOpr(data.id, `dashboard/programs/` + data.program.id+ `/sub_programs/`+ data.id, table);
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

    openAdd = (programId ) => {
        let $modal = $('#add-modal');
        let $form = $('.add-form');
        clearForm($form)
        $form[0].reset();
        $form.attr('action', `dashboard/programs/`+ programId+'/sub_programs');
        $('.add-form input[name="_method"]').remove();
        $modal.find('.modal-title').text(`Add sub program`);
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
        $form.attr('action', `dashboard/programs/` + data.program.id+ `/sub_programs/`+ data.id);
        $modal.find('.modal-title').text(`Edit sub program`);
        $modal.find('#afm_btnSaveIt').text(`Update`);
        clearErrors($modal);
        _fill($modal, data);
        if($('.has_calls').is(":checked")) {
            $('.has_calls_holder').show();
        } else {
            $('.has_calls_holder').hide();
        }
        if($('.has_visits').is(":checked")) {
            $('.has_visits_holder').show();
        } else {
            $('.has_visits_holder').hide();
        }

        if($('.has_FOC').is(":checked")) {
            $('.has_foc_holder').show();
        } else {
            $('.has_foc_holder').hide();
        }
        $modal.modal('show');
        $modal.removeClass('out');
        $modal.addClass('in');
    };

    openView = (data) => {
        const $modal = $('#view-modal');
        const $data = $('#data');
        const fields = [
            { label: 'Name', value: data.name },
            { label: 'Program', value: data.program.name },
            { label: 'Country', value: data.country.name },
            { label: 'Drug', value: data.drug ? data.drug.name : '' },
            { label: 'Type', value: data.type },
            { label: 'Treatment duration', value: data.treatment_duration },
            { label: 'Target number of patients', value: data.target_number_of_patients },
            { label: 'Eligible', value: data.eligible },
            { label: 'Has calls', value: data.has_calls },
            { label: 'Has visits', value: data.has_visits },
            { label: 'Start date', value: data.start_date },
            { label: 'Finish date', value: data.finish_date },
            { label: 'Program initial', value: data.program_initial },
            { label: 'Is follow program date', value: data.is_follow_program_date },
            { label: 'Visit every day', value: data.visit_every_day },
            { label: 'Call every day', value: data.call_every_day },

            { label: 'Has FOC', value: data.has_FOC },
            { label: 'Cycle period', value: data.cycle_period },
            { label: 'Cycle number', value: data.cycle_number },
            { label: 'Cycle reminder', value: data.cycle_reminder_at },
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
    $('#add-modal').on('hidden.bs.modal',function (event) {
        $(this).removeClass('in');
        $(this).addClass('out');
    })
</script>
@include('dashboard.programs.sub_programs.create_sub_program_scripts')
@endpush
