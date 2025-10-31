{{-- ADD MODEL START --}}
<div id="add-modal" class="modal fade-scale" role="dialog" tabindex="-1" aria-hidden="true" aria-labelledby="ModelLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="ModelLabel">Add New Hospital</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="frm_add" class="add-form" method="POST">

                    {{ csrf_field() }}

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class="required-label" for="sub_program_id">Sub program</label>
                            <select class="form-control select2" id="sub_program_id" name="sub_program_id" >
                                @foreach($sub_programs as $single)
                                    <option value="{{$single->id}}">{{$single->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <input type="hidden" name="id" class="safety-reports">
                        <div class="form-group col-md-6 safety-reports">
                            <label class="required-label" for="sub_program_patient_id">Patient</label>
                            <select class="form-control select2" id="sub_program_patient_id" name="sub_program_patient_id" >

                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="required-label">Name</label>
                            <div class="custom-file">
                                <input class="custom-file-input" type="file" name="name"  id="name" aria-describedby="name" >
                                <label class="custom-file-label" for="name">Attach document</label>
                            </div>
                            <label id="name_file"></label>
                        </div>
                        <div class="form-group col-sm-6 safety-reports">
                            <label class="required-label">Title</label>
                            <input name="title" id="title" type="text" class="form-control" value="{{old("title")}}">
                        </div>
                        <div class="form-group col-sm-12 safety-reports">
                            <label for="description" class="label-required">Description</label>
                            <textarea name="description" id="description" type="text" class="form-control">{{old("description")}}</textarea>
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
