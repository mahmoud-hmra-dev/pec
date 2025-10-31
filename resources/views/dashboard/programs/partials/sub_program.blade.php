<div class="row p-4 align-items-center">
    <div class="col-md-12 row justify-content-end ">
        <button type="button" class="remove_sub_program_btn text-sm btn btn-sm btn-danger">X</button>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="name_{{$index}}" class="label-required">Name</label>
            <input name="sub_program[{{$index}}][name]" id="name_{{$index}}" type="text" class="form-control" value="{{old("sub_program[$index][name]")}}">
        </div>
    </div>


    <div class="col-md-6">
        <div class="form-group">
            <label for="program_initial_{{$index}}" class="label-required">Program Initial</label>
            <input name="sub_program[{{$index}}][program_initial]" id="program_initial_{{$index}}" type="text" class="form-control" value="{{old("sub_program[$index][program_initial]")}}">
        </div>
    </div>


    <div class="form-group col-md-6">
        <label class="required-label" for="country_id_{{$index}}">Country</label>
        <select class="form-control countries" id="country_id_{{$index}}" name="sub_program[{{$index}}][country_id]" >
            @foreach($countries as $country)
                <option value="{{$country->id}}" @if(old("sub_program[$index][country_id]") == $country->id) selected @endif>{{$country->name}}</option>
            @endforeach
        </select>
    </div>


    <div class="form-group col-md-6">
        <label class="required-label" for="drug_id_{{$index}}">Drug</label>
        <select class="form-control drugs" id="drug_id_{{$index}}" name="sub_program[{{$index}}][drug_id]" >
            @foreach($drugs as $single)
                <option value="{{$single->id}}" @if(old("sub_program[$index][drug_id]") == $single->id) selected @endif>{{$single->name}}</option>
            @endforeach
        </select>
    </div>


    <div class="form-group col-md-6">
        <label class="required-label" for="type_{{$index}}">Type</label>
        <select class="form-control types" id="type_{{$index}}" name="sub_program[{{$index}}][type]" >
            @foreach(\App\Enums\ProgramTypeEnum::ALL as $single)
                <option value="{{$single}}" @if(old("sub_program[$index][type]") == $single) selected @endif>{{$single}}</option>
            @endforeach
        </select>
    </div>


    <div class="col-md-6">
        <div class="form-group">
            <label for="target_number_of_patients_{{$index}}" class="label-required">Target Number of Patients</label>
            <input
                name="sub_program[{{$index}}][target_number_of_patients]"
                id="target_number_of_patients_{{$index}}"
                type="number"
                step="1"
                min="0"
                class="form-control"
                value="{{old("sub_program[$index][target_number_of_patients]")}}"
            >
        </div>
    </div>


    <div class="form-group col-md-6">
        <div class="custom-control custom-switch">
            <input type="checkbox" class="custom-control-input is_eligible" id="is_eligible_{{$index}}" switch="primary" name="sub_program[{{$index}}][is_eligible]" @if(old("sub_program[$index][is_eligible]")) checked @endif/>
            <label for="is_eligible_{{$index}}" class="custom-control-label">Is Eligible</label>
        </div>
    </div>


    <div class="form-group col-md-6">
        <div class="custom-control custom-switch">
            <input type="checkbox" class="custom-control-input has_calls" id="has_calls_{{$index}}" switch="primary" name="sub_program[{{$index}}][has_calls]" @if(old("sub_program[$index][has_calls]")) checked @endif/>
            <label for="has_calls_{{$index}}" class="custom-control-label">Has Calls</label>
        </div>
    </div>


    <div class="form-group col-md-6">
        <div class="custom-control custom-switch">
            <input type="checkbox" class="custom-control-input has_visits" id="has_visits_{{$index}}" switch="primary" name="sub_program[{{$index}}][has_visits]"  @if(old("sub_program[$index][has_visits]")) checked @endif/>
            <label for="has_visits_{{$index}}" class="custom-control-label">Has Visits</label>
        </div>
    </div>


    <div class="form-group col-md-6">
        <div class="custom-control custom-switch">
            <input type="checkbox" class="custom-control-input follow_treatment_dates" id="is_follow_program_date_{{$index}}" switch="primary" name="sub_program[{{$index}}][is_follow_program_date]" @if(old("sub_program[$index][is_follow_program_date]")) checked @endif/>
            <label for="is_follow_program_date_{{$index}}" class="custom-control-label">Does this sub program follow program dates?</label>
        </div>
    </div>


    <div class="col-md-6 dates">
        <div class="form-group">
            <label for="started_at_{{$index}}" class="label-required">Start</label>
            <input name="sub_program[{{$index}}][started_at]" id="started_at_{{$index}}" type="datetime-local" class="form-control started_at" value="{{old("sub_program[$index][started_at]")}}">
        </div>
    </div>


    <div class="col-md-6 dates">
        <div class="form-group">
            <label for="ended_at_{{$index}}" class="label-required">End</label>
            <input name="sub_program[{{$index}}][ended_at]" id="ended_at_{{$index}}" type="datetime-local" class="form-control ended_at" value="{{old("sub_program[$index][ended_at]")}}">
        </div>
    </div>


    <div class="form-group col-md-6">
        <div class="custom-control custom-switch">
            <input type="checkbox" class="custom-control-input duration_ended_by_program" id="is_treatment_duration_ended_by_program_{{$index}}" switch="primary" name="sub_program[{{$index}}][is_treatment_duration_ended_by_program]" @if(old("sub_program[$index][is_treatment_duration_ended_by_program]")) checked @endif/>
            <label for="is_treatment_duration_ended_by_program_{{$index}}" class="custom-control-label">Is the treatment ended by program end?</label>
        </div>
    </div>

    <div class="col-md-6 treatment_duration_days">
        <div class="form-group">
            <label for="treatment_duration_days_{{$index}}" class="label-required">Treatment Duration <small>in Days</small></label>
            <input
                name="sub_program[{{$index}}][treatment_duration_days]"
                id="treatment_duration_days_{{$index}}"
                type="number"
                step="1"
                min="0"
                max="31"
                class="form-control"
                value="{{old("sub_program[$index][treatment_duration_days]")}}"
            >
        </div>
    </div>


    <div class="col-md-6 has_visits_holder" style="display: none">
        <div class="form-group">
            <label for="visit_every_day_{{$index}}" class="label-required">Visit Every <small>in Days</small></label>
            <input
                name="sub_program[{{$index}}][visit_every_day]"
                id="visit_every_day_{{$index}}"
                type="number"
                step="1"
                min="0"
                class="form-control"
                value="{{old("sub_program[$index][visit_every_day]")}}"
            >
        </div>
    </div>


    <div class="col-md-6 has_calls_holder" style="display: none">
        <div class="form-group">
            <label for="call_every_day_{{$index}}" class="label-required">Call Every <small>in Days</small></label>
            <input
                name="sub_program[{{$index}}][call_every_day]"
                id="call_every_day_{{$index}}"
                type="number"
                step="1"
                min="0"
                class="form-control"
                value="{{old("sub_program[$index][call_every_day]")}}"
            >
        </div>
    </div>

    <div class="col-md-12 row justify-content-start mx-2">
        <div class="form-group mt-2">
            <button id="add_question_btn_{{$index}}" type="button" class="btn btn-success add_question px-3" >
                Add Question
            </button>
        </div>
    </div>

    <div class="col-md-12 questions_holder row" id="questions_holder_{{$index}}" data-sub_program_index="{{$index}}">

    </div>
</div>
