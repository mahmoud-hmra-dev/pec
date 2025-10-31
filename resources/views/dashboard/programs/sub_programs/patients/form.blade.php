{{-- ADD MODEL START --}}
<div id="add-modal" class="modal fade-scale" role="dialog" tabindex="-1" aria-hidden="true" aria-labelledby="ModelLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="ModelLabel">Add New Patient</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="frm_add" class="add-form" method="POST">

                    {{ csrf_field() }}

                    <div class="row" id="patient">
                        <div class="form-group col-md-6">
                            <label class="required-label" for="patient_id">Patient</label>
                            <select class="form-control select2" id="patient_id"  name="patient_id">
                                @foreach($patients as $patient)
                                    <option value="{{$patient->id}}" @if(old('patient_id') == $patient->id) selected @endif>{{$patient->user->first_name .' '.$patient->user->last_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row" >
                        <div class="form-group col-md-6">
                            <label class="required-label" for="nurse">Nurse</label>
                            <select class="form-control select2" id="nurse" name="nurse">
                                @foreach($nurses as $nurse)
                                    <option value="{{$nurse->id}}" @if(old('nurse') == $nurse->id) selected @endif>{{$nurse->service_provider_type->service_provider->user->first_name .' '.$nurse->service_provider_type->service_provider->user->last_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="required-label" for="coordinator">Coordinator</label>
                            <select class="form-control select2" id="coordinator" name="coordinator">
                                @foreach($coordinators as $coordinator)
                                    <option value="{{$coordinator->id}}" @if(old('coordinator') == $coordinator->id) selected @endif>{{$coordinator->service_provider_type->service_provider->user->first_name .' '.$coordinator->service_provider_type->service_provider->user->last_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mt-4" id="sub_programs">

                    </div>

                    <div class="form-group m-2 float-right">
                        <a href="{{route('patients.index')}}" class="edit btn btn-xs btn-primary mr-2" >If no found patient click her to add
                        </a>
                        <button type="submit" class="edit btn btn-primary mr-2" >Save
                        </button>
                        <button type="button" class="btn btn-dark" data-dismiss="modal">
                            Close
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
{{-- ADD MODEL END --}}

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
        </script>
<script>
    $('#patient_id').on('change', function(event) {
        event.preventDefault();
        let patient_id = $(this).val();
        $.ajax({
            url: "{{ route('sub_programs.patients.get_sub_programs_by_patient_id') }}/" + patient_id,
            type: "GET",
            dataType: "json",
            success: function(data) {
                $("#sub_programs").empty();
                let sub_programs = data.sub_programs;
                if (sub_programs.length > 0){
                    let tableHtml = `<table class="table table-bordered table-striped" id="patients-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Sub program name</th>
                        <th>Sub program start date</th>
                        <th>Sub program finish date</th>
                    </tr>
                </thead>
                <tbody>`;

                    sub_programs.forEach(function(option) {
                        tableHtml += `<tr>
                    <td>${option.id}</td>
                    <td>${option.name}</td>
                    <td>${option.start_date}</td>
                    <td>${option.finish_date}</td>
                </tr>`;
                    });

                    tableHtml += `</tbody></table>`;
                    $("#sub_programs").html(tableHtml);
                }
            },
            error: function() {
                $("#sub_program_id").empty();
            }
        });
    });
</script>
@endpush
