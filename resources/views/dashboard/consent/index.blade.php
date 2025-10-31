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


        <div style="min-height: 300px;">
            <table class="table table-bordered table-striped" id="drugs-table">
                <thead>
                <tr>
                    <th>Id</th>
                    <th>Patient First Name</th>
                    <th>Patient Family Name</th>
                    <th>Program</th>
                    <th>Sponsor</th>
                    <th>Physician</th>
                    <th>Is Consent</th>
                    <th>Actions</th>

                </tr>
                </thead>
            </table>
        </div>
    </div>


@stop
@push('scripts')
    <script>
        $(function () {
            var table = $('#drugs-table').DataTable({
                processing: true,
                serverSide: false,
                ajax: '{!! route('dashboard_consent.index') !!}',
                dom: 'Blfrtip',
                responsive:true,
                buttons: [
                    'copyHtml5',
                    'csvHtml5',
                    'pdfHtml5'
                ],
                columns: [
                    {"data": "id"},
                    {"data": "first_name"},
                    {"data": "family_name"},
                    {"data": "program.name","name":"program.name"},
                    {"data": "client.client_name","name":"client.client_name"},
                    {"data": "physician.user","name":"physician.user.first_name","render":function (data) {
                            if(data) return data.first_name+" "+data.last_name
                            return "";
                        }
                    },
                    {"data": "is_consent","name":"is_consent","render":function (data) {
                            return data === 1 ? "Yes" : "No";
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
                deleteOpr(data.id, `dashboard/dashboard_consent/` + data.id, table);
            });

        });

    </script>
@endpush
