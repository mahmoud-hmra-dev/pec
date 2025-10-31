@extends('dashboard.layout.main')
@section('content')
<div class="p-4">
    @can(\App\Enums\PermissionEnum::MANAGE_QUESTIONS)
        <div class="pt-2">
            <button
                class="edit btn btn-primary mb-4"
                href="javascript:" onclick="openAdd('{!! $sub_program_id !!}')">
                <i class="mdi mdi-plus-circle-outline"></i>
                Add new question
            </button>
        </div>
    @endcan


    <div style="min-height: 300px;">
        <table class="table table-bordered table-striped" id="questions-table">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Question</th>
                    <th>Question type</th>
                    <th>Category</th>
                    <th>Choices</th>
                    <th>Actions</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

@component('dashboard.programs.sub_programs.questions.form', ['question_types'=>$question_types,'categories'=>$categories])
@endcomponent

@stop
@push('scripts')
<script>
    $(function () {
        var table = $('#questions-table').DataTable({
            processing: true,
            serverSide: false,
            ajax: '{!! route('sub_programs.questions.index',$sub_program_id) !!}',
            dom: 'Blfrtip',
            responsive:true,
            buttons: [
                'copyHtml5',
                'csvHtml5',
                'pdfHtml5'
            ],
            columns: [
                {"data": "id"},
                {"data": "question"},
                {"data": "type.name"},
                {"data": "category.name"},
                {"data": "choices", "render": function (data) {
                        return data.map(function(item) {
                            return item.choice;
                        }).join(" - ");
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
            deleteOpr(data.id, `dashboard/sub_programs/` + data.sub_program_id+ `/questions/`+ data.id, table);
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

    openAdd = (sub_program_id ) => {
        let $modal = $('#add-modal');
        let $form = $('.add-form');
        let $modal_no_choices = $('#no-choices');
        clearForm($form)
        $form[0].reset();
        $form.attr('action', `dashboard/sub_programs/`+ sub_program_id+'/questions');
        $('.add-form input[name="_method"]').remove();
        $modal.find('.modal-title').text(`Add question`);
        clearErrors($modal);
        clearErrors($modal_no_choices);
        $('#choices').find('[data-repeater-item]').slice(1).remove();
        $modal.modal('show');
        $modal.removeClass('out');
        $modal.addClass('in');
    };


    openEdit = (data) => {
        let $modal = $('#add-modal');
        let $form = $('.add-form');
        $form[0].reset();
        $form.append('<input type="hidden" name="_method" value="PUT">');
        $form.attr('action', `dashboard/sub_programs/` + data.sub_program_id+ `/questions/`+ data.id);
        let $modal_no_choices = $('#no-choices');
        $modal.find('.modal-title').text(`Edit question`);
        $modal.find('#afm_btnSaveIt').text(`Update`);
        clearErrors($modal);
        clearErrors($modal_no_choices);
        $('#choices').find('[data-repeater-item]').slice(1).remove();
        _fill($modal_no_choices, data);
        console.log(data.choices);
        $.each(data.choices, function(index, choice) {
            let $newChoice = $(`<div data-repeater-item="" style="">
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <input name="choices[${index+1}][choice]" id="choice" placeholder="Choice" type="text" class="form-control" value="${choice.choice}">
                                            <input name="choices[${index+1}][id]" id="id"  type="hidden" class="form-control" value="${choice.id}">
                                        </div>
                                        <div class="form-group col-sm-2">
                                            <input class="btn btn-danger btn-block" data-repeater-delete="" type="button" value="Delete">
                                        </div>

                                    </div>
                                </div>`)
                .clone();
            $('#choices').find('[data-repeater-item]').eq(0).after($newChoice);
        });
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
