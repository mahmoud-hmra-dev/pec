{{-- ADD MODEL START --}}
<div id="add-modal" class="modal fade-scale" role="dialog" tabindex="-1" aria-hidden="true" aria-labelledby="ModelLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="ModelLabel">Add New Sub program</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="frm_add" class="add-form" method="POST">

                    {{ csrf_field() }}

                    <div class="row">


                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name" class="label-required">Name</label>
                            <input name="name" id="name" type="text" class="form-control" value="{{old("name")}}">
                        </div>
                    </div>

                    <div class="form-group col-md-6">
                        <label class="required-label" for="country_id">Country</label>
                        <select class="form-control countries select2" id="country_id" name="country_id" >
                            <option value="">Choose Country</option>
                            @foreach($program_countries as $country)
                                <option value="{{$country->country_id}}" @if(old("country_id") == $country->country_id) selected @endif>{{$country->country->name}}</option>
                            @endforeach
                        </select>
                    </div>


                    <div class="form-group col-md-6">
                        <label class="required-label" for="drug_id">Drug</label>
                        <select class="form-control drugs select2" id="drug_id" name="drug_id" >
                            <option value="">Choose Drug</option>
                            @foreach($program_drugs as $single)
                                <option value="{{$single->drug_id}}" @if(old("drug_id") == $single->drug_id) selected @endif>{{$single->drug->name}}</option>
                            @endforeach
                        </select>
                    </div>


                    <div class="form-group col-md-6">
                        <label class="required-label" for="type">Type</label>
                        <select class="form-control types select2" id="type" name="type" >
                            @foreach(\App\Enums\ProgramTypeEnum::ALL as $single)
                                <option value="{{$single}}" @if(old("type") == $single) selected @endif>{{$single}}</option>
                            @endforeach
                        </select>
                    </div>


                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="target_number_of_patients" class="label-required">Target Number of Patients</label>
                            <input
                                name="target_number_of_patients"
                                id="target_number_of_patients"
                                type="number"
                                step="1"
                                min="0"
                                class="form-control"
                                value="{{old("target_number_of_patients")}}"
                            >
                        </div>
                    </div>



                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="program_initial" class="label-required">Program Initial</label>
                                <input name="program_initial" id="program_initial" type="text" class="form-control" value="{{old("program_initial")}}">
                            </div>
                        </div>
                        <div class="col-md-6 dates">
                            <div class="form-group">
                                <label for="start_date" class="label-required">Start</label>
                                <input name="start_date" id="start_date" type="datetime-local" class="form-control start_date" value="{{old("start_date")}}">
                            </div>
                        </div>

                        <div class="col-md-6 dates">
                            <div class="form-group">
                                <label for="finish_date" class="label-required">End</label>
                                <input name="finish_date" id="ended_at" type="datetime-local" class="form-control finish_date" value="{{old("finish_date")}}">
                            </div>
                        </div>



                    <div class="form-group col-md-6">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input is_eligible" id="eligible" switch="primary" name="eligible" @if(old("eligible")) checked @endif/>
                            <label for="eligible" class="custom-control-label">Is Eligible</label>
                        </div>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input is_follow_program_date" id="is_follow_program_date" switch="primary" name="is_follow_program_date" @if(old("is_follow_program_date")) checked @endif/>
                            <label for="is_follow_program_date" class="custom-control-label">Is follow program date?</label>
                        </div>
                    </div>
                        <div class="col-md-6 treatment_duration">
                            <div class="form-group">
                                <label for="treatment_duration" class="label-required">Treatment Duration <small>in Days</small></label>
                                <input
                                    name="treatment_duration"
                                    id="treatment_duration"
                                    type="number"
                                    step="1"
                                    min="0"
                                    max="31"
                                    class="form-control"
                                    value="{{old("treatment_duration")}}"
                                >
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input has_calls" id="has_calls" switch="primary" name="has_calls" @if(old("has_calls")) checked @endif/>
                                <label for="has_calls" class="custom-control-label">Has Calls</label>
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input has_visits" id="has_visits" switch="primary" name="has_visits"  @if(old("has_visits")) checked @endif/>
                                <label for="has_visits" class="custom-control-label">Has Visits</label>
                            </div>
                        </div>



                        <div class="col-md-6 has_calls_holder" style="display: none">
                            <div class="form-group">
                                <label for="call_every_day" class="label-required">Call Every <small>in Days</small></label>
                                <input
                                    name="call_every_day"
                                    id="call_every_day"
                                    type="number"
                                    step="1"
                                    min="0"
                                    class="form-control"
                                    value="{{old("call_every_day")}}"
                                >
                            </div>
                        </div>
                    <div class="col-md-6 has_visits_holder" style="display: none">
                        <div class="form-group">
                            <label for="visit_every_day" class="label-required">Visit Every <small>in Days</small></label>
                            <input
                                name="visit_every_day"
                                id="visit_every_day"
                                type="number"
                                step="1"
                                min="0"
                                class="form-control"
                                value="{{old("visit_every_day")}}"
                            >
                        </div>
                    </div>
                        <div class="form-group col-md-12">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input has_FOC" id="has_FOC" switch="primary" name="has_FOC"  @if(old("has_FOC")) checked @endif/>
                                <label for="has_FOC" class="custom-control-label">Has FOC</label>
                            </div>
                        </div>
                        <div class="col-md-4 has_foc_holder" style="display: none">
                            <div class="form-group">
                                <label for="cycle_period" class="label-required">Cycle period <small>in Days</small></label>
                                <input
                                    name="cycle_period"
                                    id="cycle_period"
                                    type="number"
                                    step="1"
                                    min="0"
                                    class="form-control"
                                    value="{{old("cycle_period")}}"
                                >
                            </div>
                        </div>
                        <div class="col-md-4 has_foc_holder" style="display: none">
                            <div class="form-group">
                                <label for="cycle_number" class="label-required">Cycle number <small>in Days</small></label>
                                <input
                                    name="cycle_number"
                                    id="cycle_number"
                                    type="number"
                                    step="1"
                                    min="0"
                                    class="form-control"
                                    value="{{old("cycle_number")}}"
                                >
                            </div>
                        </div>
                        <div class="col-md-4 has_foc_holder" style="display: none">
                            <div class="form-group">
                                <label for="cycle_reminder_at" class="label-required">Cycle reminder <small>in Days</small></label>
                                <input
                                    name="cycle_reminder_at"
                                    id="cycle_reminder_at"
                                    type="number"
                                    step="1"
                                    min="0"
                                    class="form-control"
                                    value="{{old("cycle_reminder_at")}}"
                                >
                            </div>
                        </div>

                    </div>
                    <div class="form-group m-2 float-right">
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
@endpush
