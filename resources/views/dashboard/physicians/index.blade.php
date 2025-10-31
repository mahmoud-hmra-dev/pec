@extends('dashboard.layout.main')
@section('content')
    <div class="p-4">
        <div class="pt-2">
            <button
                class="edit btn btn-primary mb-4"
                href="javascript:" onclick="openAdd()">
                <i class="fa fa-plus"></i>
                Add new Physician
            </button>
        </div>

        <div style="min-height: 300px;">
            <table class="table table-bordered table-striped" id="users-table">
                <thead>
                <tr>
                    <th>Id</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Speciality</th>
                    <th>Email</th>
                    <th>Personal Email</th>
                    <th>Phone</th>
                    <th>Image</th>
                    <th>Country</th>
                    <th>City</th>
                    <th>Address</th>
                    <th>Actions</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>

    @include('dashboard.physicians.form',[$specialties])

@stop
@push('scripts')
    <script>
        $(function () {
            var table = $('#users-table').DataTable({
                processing: true,
                serverSide: false,
                ajax: '{!! route('physicians.index') !!}',
                dom: 'Blfrtip',
                responsive:true,
                scrollX:true,
                lengthMenu : [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                fixedHeader: true,
                buttons: [
                    'copyHtml5',
                    'csvHtml5',
                    'pdfHtml5'
                ],
                columns: [
                    {"data": "id"},
                    {"data": "first_name"},
                    {"data": "last_name"},
                    {"data": "physician.speciality.name","name":"physician.speciality.name",'render':function (data) {
                            if(data) return data;
                            return '';
                        }
                    },
                    {"data": "email"},
                    {"data": "personal_email"},
                    {"data": "phone"},
                    {"data": "full_path",
                        "render":function (data) {
                            if(data != null)
                                return "<a target='_blank' href='" + data + "'><img width='100px' src='" + data + "'></a>"
                            return '';
                        }, orderable: false, searchable: false},
                    {"data": "country.name",'name':"country.name" ,'render':function (data) {
                            if(data){
                                return data;
                            }
                            return '';
                        }
                    },
                    {"data": "city"},
                    {"data": "address"},
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
                deleteOpr(data.id, `dashboard/users/physicians/` + data.id, table);
            });

            table.on('click', '.edit', function () {
                $tr = $(this).closest('tr');
                if ($($tr).hasClass('child')) {
                    $tr = $tr.prev('.parent')
                }
                var data = table.row($tr).data();
                console.log(data)
                openEdit(data);
            });

        });

        openAdd = () => {
            let $modal = $('#add-modal');
            let $form = $('.add-form');
            clearForm($form)
            $form[0].reset();
            $form.find('.select2').change();
            $form.attr('action', `dashboard/users/physicians`);
            $('.add-form input[name="_method"]').remove();
            $modal.find('.modal-title').text(`Add Physician`);
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
            $form.attr('action', `dashboard/users/physicians/` + data.id);
            $modal.find('.modal-title').text(`Edit Physician`);
            $modal.find('#afm_btnSaveIt').text(`Update`);
            clearErrors($modal);
            _fill($modal, data);
            $modal.modal('show');
            $modal.removeClass('out');
            $modal.addClass('in');
        };
        $('#add-modal').on('hidden.bs.modal',function (event) {
            $(this).removeClass('in');
            $(this).addClass('out');
        })
        $('#close-img').on('click',function (event) {
            var id = $(this).attr('data-id');
            deleteOpr(id, `dashboard/users/deleteImage/` + id, table)
            $('#add-modal').modal('toggle');
            $('#add-modal').find('input[type="file"]').show();
        })
    </script>
@endpush
