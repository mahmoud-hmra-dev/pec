{{-- ADD MODEL START --}}
<div id="add-modal" class="modal fade-scale" role="dialog" tabindex="-1" aria-hidden="true" aria-labelledby="ModelLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="ModelLabel">Add New Service type</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="frm_add" class="add-form" method="POST">

                    {{ csrf_field() }}
                    <div id="data">
                        <input type="hidden" name="id"/>
                        <div class="form-group col-md-6">
                            <label class="required-label">Service Types</label>
                            <select class="form-control" id="service_type_id" name="service_type_id" >
                                @foreach($service_types as $service_type)
                                    <option {{$service_type->id == old('service_type_id') ? 'selected' : ''}} value="{{$service_type->id}}">{{$service_type->name}}</option>
                                @endforeach
                                <option value="" data-custom="true">Other</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6" id="service_type_name_group" style="display:none;">
                            <label class="required-label">Custom title</label>
                            <input type="text" name="service_type_name" id="service_type_name" class="form-control" value="{{ old('service_type_name') }}">
                        </div>
                    </div>
                    <div class="form-group m-2 float-right">
                        <button type="submit" class="edit btn  btn-primary mr-2" >Save
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
