
<div id="edit-modal" class="modal fade-scale" role="dialog" tabindex="-1" aria-hidden="true" aria-labelledby="ModelLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="ModelLabel">Add New question</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="frm_add" class="edit-form" method="POST">

                    {{ csrf_field() }}

                    <div class="row" id="no-choices">


                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="question" class="label-required">Question</label>
                            <textarea name="question" id="question" type="text" class="form-control">{{old("name")}}</textarea>
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="required-label" for="country_id">Type</label>
                        <select class="form-control question_types" id="question_type_id" name="question_type_id" >
                            @foreach($question_types as $question_type)
                                <option value="{{$question_type->id}}" @if(old("question_type_id") == $question_type->id) selected @endif>{{$question_type->name}}</option>
                            @endforeach
                        </select>
                    </div>


                        <div class="form-group col-md-6">
                            <label class="required-label" for="category_id">Category</label>
                            <select class="form-control categories" id="category_id" name="category_id" >
                                @foreach($categories as $category)
                                    <option value="{{$category->id}}" @if(old("category_id") == $category->id) selected @endif>{{$category->name}}</option>
                                @endforeach
                            </select>
                        </div>


                        <div class="form-group col-md-6">
                            <label class="required-label" for="sub_program_id">Sub program</label>
                            <select class="form-control sub_programs" id="sub_program_id" name="sub_program_id" >
                                @foreach($sub_programs as $sub_program)
                                    <option value="{{$sub_program->id}}" @if(old("sub_program_id") == $sub_program->id) selected @endif>{{$sub_program->name}}</option>
                                @endforeach
                            </select>
                        </div>



                    </div>
                    <div class="row">
                        <div class="repeater container" id="choices-edit">
                            <label for="choices" class="label-required">Choices</label>
                            <div data-repeater-list="choices">
                                <div data-repeater-item>
                                    <div class="row" >
                                        <div class="form-group col-md-6">
                                            <input name="choice" id="choice" placeholder="Choice" type="text" class="form-control" value="{{old("choice")}}">
                                        </div>
                                        <div class="form-group col-sm-2">
                                            <input class="btn btn-danger btn-block" data-repeater-delete
                                                   type="button" value="Delete" />
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="">
                                <div class="form-group pl-0">
                                    <input class="edit btn btn-xs btn-dark" data-repeater-create type="button"  value="Add"/>
                                </div>
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


