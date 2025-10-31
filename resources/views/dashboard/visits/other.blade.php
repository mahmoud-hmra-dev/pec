<div class="tab-wrap">
    {{-- tabs titles --}}
    <input type="radio" id="tab_${visit_history_index}_patient" name="tabGroup${visit_history_index}" class="tab" checked>
    <label for="tab_${visit_history_index}_patient">Patient Data</label>

    <input type="radio" id="tab_${visit_history_index}_physician" name="tabGroup${visit_history_index}" class="tab">
    <label for="tab_${visit_history_index}_physician">Physician Data</label>

    <input type="radio" id="tab_${visit_history_index}_weight" name="tabGroup${visit_history_index}" class="tab">
    <label for="tab_${visit_history_index}_weight">Weight Data</label>

    {{-- tabs content --}}
    <div class="tab__content row">
        <h3 class="col-12">Patient Data : </h3>
        <div class="form-group col-md-6">
            <label>Age</label>
            <input type="number" min="0" step="1" name="visit_history[${visit_history_index}][patient_data][age]" class="form-control">
        </div>
        <div class="form-group col-md-6">
            <label>Weight</label>
            <input type="number" min="0" step="0.1" name="visit_history[${visit_history_index}][patient_data][weight]" class="form-control">
        </div>
        <div class="form-group col-md-6">
            <label>Height</label>
            <input type="number" min="0" step="0.1" name="visit_history[${visit_history_index}][patient_data][height]" class="form-control">
        </div>
        <div class="form-group col-md-6">
            <label>BMI</label>
            <input type="number" min="0" step="0.1" name="visit_history[${visit_history_index}][patient_data][BMI]" class="form-control">
        </div>
    </div>

    <div class="tab__content row">
        <h3 class="col-12">Physician Data : </h3>
        <div class="form-group col-md-12">
            <label class="required-label">Physicians</label>
            <select class="form-control" name="visit_history[${visit_history_index}][physician_data][physician_id]" >
                <option>Select a physician</option>
                @foreach($physicians as $single)
                    <option value="{{$single->id}}">{{$single->user->first_name.' '.$single->user->last_name}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-6">
            <label>Notes</label>
            <textarea name="visit_history[${visit_history_index}][physician_data][notes]" cols="30" rows="10" class="form-control"></textarea>
        </div>
        <div class="form-group col-md-6">
            <label>Other Medications</label>
            <textarea name="visit_history[${visit_history_index}][physician_data][other_medications]" cols="30" rows="10" class="form-control"></textarea>
        </div>
    </div>

    <div class="tab__content row">
        <h3 class="col-12">Weight Data : </h3>
        <div class="form-group col-md-2">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="is_diet_${visit_history_index}" switch="primary" name="visit_history[${visit_history_index}][weight_data][is_diet]" />
                <label for="is_diet_${visit_history_index}" class="custom-control-label">Diet</label>
            </div>
        </div>

        <div class="col-md-5">
            <div class="form-group">
                <label class="label-required">Any type of Anti-Obesity</label>
                <input name="visit_history[${visit_history_index}][weight_data][any_anti_obesity]" type="text" class="form-control">
            </div>
        </div>

        <div class="col-md-5">
            <div class="form-group">
                <label class="label-required">Surgery</label>
                <input name="visit_history[${visit_history_index}][weight_data][surgery]" type="text" class="form-control">
            </div>
        </div>
    </div>

</div>
