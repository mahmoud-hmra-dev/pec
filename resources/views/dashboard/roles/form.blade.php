{{-- ADD MODEL START --}}
<div id="add-modal" class="modal fade-scale" role="dialog" tabindex="-1" aria-hidden="true" aria-labelledby="ModelLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="ModelLabel">Add New User</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="frm_add" class="add-form" method="POST">

                    {{ csrf_field() }}
                    <div class="row">
                        <input type="hidden" name="id" class="form-control">
                        <div class="form-group col-md-12">
                            <label class="required-label">Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>

                        <div class="form-group col-md-12">
                            <label class="required-label">Permissions</label>
                            <select class="form-control w-100" id="permissions" name="permissions[]" multiple>
                                @foreach($permissions as $item)
                                    <option {{ (collect(old('permissions'))->contains($item->name)) ? 'selected':'' }} value="{{$item->name}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group m-2 float-right">
                        <button type="submit" class="edit btn btn-xs btn-primary mr-2" >
                            Save
                        </button>
                        <button type="button" class="btn  btn-dark" data-dismiss="modal">
                            Close
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
{{-- ADD MODEL END --}}
