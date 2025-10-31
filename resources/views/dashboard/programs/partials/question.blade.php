<div class="row p-4">
    <div class="col-md-12 row justify-content-end">
        <button type="button" class="close_question_btn text-sm btn btn-sm btn-danger">X</button>
    </div>

    <div class="form-group col-md-6">
        <label class="required-label" for="sub_program_{{$sub_program_index}}_question_type_{{$question_index}}">Question Type</label>
        <select class="form-control question_type" id="sub_program_{{$sub_program_index}}_question_type_{{$question_index}}" name="sub_program[{{$sub_program_index}}][questions][{{$question_index}}][question_type_id]" >
            @foreach($question_types as $single)
                <option value="{{$single->id}}" data-type_name="{{$single->name}}" @if(old("sub_program[$sub_program_index][questions][$question_index][question_type_id]") == $single) selected @endif>{{$single->name}}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group col-md-6">
        <label class="required-label" for="sub_program_{{$sub_program_index}}_question_category_{{$question_index}}">Question Category</label>
        <select class="form-control question_category" id="sub_program_{{$sub_program_index}}_question_category_{{$question_index}}" name="sub_program[{{$sub_program_index}}][questions][{{$question_index}}][question_category_id]" >
            @foreach($question_categories as $single)
                <option value="{{$single->id}}"  @if(old("sub_program[$sub_program_index][questions][$question_index][question_category_id]") == $single) selected @endif>{{$single->name}}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-12">
        <div class="form-group">
            <label for="sub_program_{{$sub_program_index}}_question_{{$question_index}}" class="label-required">Question</label>
            <input
                name="sub_program[{{$sub_program_index}}][questions][{{$question_index}}][question]"
                id="sub_program_{{$sub_program_index}}_question_{{$question_index}}"
                type="text"
                class="form-control"
                value="{{old("sub_program[$sub_program_index][questions][$question_index][question]")}}"
            >
        </div>
    </div>

    <div class="col-md-12 row justify-content-start mx-2 add_choice_section" style="display: none">
        <div class="form-group mt-2">
            <button id="add_choice_btn_{{$sub_program_index}}_question_{{$question_index}}" type="button" class="btn btn-warning add_choice px-3" data-sub_program_index="{{$sub_program_index}}" data-question_index="{{$question_index}}">
                Add Choice
            </button>
        </div>
    </div>

    <div class="choices_holder col-md-12" data-sub_program_index="{{$sub_program_index}}" data-question_index="{{$question_index}}">

    </div>

</div>
