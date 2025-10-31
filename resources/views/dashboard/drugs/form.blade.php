{{-- ADD MODEL START --}}
<div id="add-modal" class="modal fade-scale" role="dialog" tabindex="-1" aria-hidden="true" aria-labelledby="ModelLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="ModelLabel">Add New question</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="frm_add" class="add-form" method="POST">

                    {{ csrf_field() }}
                    <div class="row" >
                        <input type="hidden" name="id">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name" class="label-required">Name</label>
                                <input name="name" id="name" type="text" class="form-control" value="{{old('name')}}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="api_name" class="label-required">API Name</label>
                                <input name="api_name" id="api_name" type="text" class="form-control" value="{{old('api_name')}}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="drug_id" class="label-required">Drug ID</label>
                                <input name="drug_id" id="drug_id" type="text" class="form-control" value="{{old('drug_id')}}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="drug_initial" class="label-required">Drug Initial</label>
                                <input name="drug_initial" id="drug_initial" type="text" class="form-control" value="{{old('drug_initial')}}">
                            </div>
                        </div>

                        <div class="form-group col-md-6">
                            <label class="required-label" for="client_id">Client</label>
                            <select class="form-control select2" id="client_id" name="client_id">
                                <option value="no_client" >Choose Client</option>
                                @foreach($clients as $single)
                                    <option value="{{$single->id}}" @if(old('client_id') == $single->id) selected @endif>{{$single->user->first_name}} {{$single->user->last_name}}</option>
                                @endforeach
                            </select>
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
