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
                        <div class="col-md-12 alert alert-default-light" id="alert">

                        </div>
                        <div class="form-group col-md-6">
                            <select class="form-control select2" id="service_provider_type_id" name="service_provider_type_id">
                                @foreach($service_providers as $service_provider)
                                    <option {{$service_provider->id == old('service_provider_type_id') ? 'selected' : ''}} value="{{$service_provider->id}}" >{{$service_provider->service_provider->user->first_name.' '.$service_provider->service_provider->user->last_name .' - '.$service_provider->service_type->name}}</option>
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
