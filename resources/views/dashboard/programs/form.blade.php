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

                    <div class="row">
                        <input type="hidden" name="id" id="program_id">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="name" class="label-required">Name</label>
                                <input name="name" id="name" type="text" class="form-control" value="{{old('name')}}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="program_no" class="label-required">Program No</label>
                                <input name="program_no" id="program_no" type="text" class="form-control" value="{{old('program_no')}}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="map_id" class="label-required">MAP ID</label>
                                <input name="map_id" id="map_id" type="text" class="form-control" value="{{old('map_id')}}">
                            </div>
                        </div>

                        <div class="form-group col-md-6">
                            <label class="required-label" for="client_id">Client</label>
                            <select class="form-control select2" id="client_id" name="client_id">
                                <option value="no_client" >Choose Client</option>
                                @foreach($clients as $single)
                                    <option value="{{$single->id}}" @if(old('client_id') == $single->id) selected @endif>{{$single->client_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6 " id="view_drugs">
                            <label class="required-label" for="drugs">Drugs</label>
                            <select class="form-control select2" id="drugs" name="drugs[]" multiple>
                            </select>
                        </div>
                        <div class="form-group col-md-6 " >
                            <label class="required-label" for="program_countries">Countries</label>
                            <select class="form-control select2" id="program_countries" name="program_countries[]" multiple>
                                @foreach($countries as $single)
                                    <option value="{{$single->id}}" @if(old('program_countries') == $single->id) selected @endif {{ collect(old('program_countries'))->contains($single->id) ? 'selected':'' }}>{{$single->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-md-6">
                            <label class="required-label" for="service_provider_type_id">Project Manager</label>
                            <select class="form-control select2" id="service_provider_type_id" name="service_provider_type_id">
                                <option value="no_manager">Choose Project Manager</option>
                                @foreach($managers as $single)
                                    <option value="{{$single->id}}" @if(old('service_provider_type_id') == $single->id) selected @endif>{{$single->service_provider->user->first_name}} {{$single->service_provider->user->last_name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="started_at" class="label-required">Start</label>
                                <input name="started_at" id="started_at" type="datetime-local" class="form-control" value="{{old('started_at')}}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="ended_at" class="label-required">End</label>
                                <input name="ended_at" id="ended_at" type="datetime-local" class="form-control" value="{{old('ended_at')}}">
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
