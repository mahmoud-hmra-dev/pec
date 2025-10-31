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

                    <div class="row" id="user">
                        <h5 class="col-md-12 mt-3">User Info :</h5>

                        <div class="form-group col-md-6">
                            <label class="required-label">First Name</label>
                            <input type="text" name="first_name" class="form-control">
                        </div>

                        <div class="form-group col-md-6">
                            <label class="required-label">Last Name</label>
                            <input type="text" name="last_name" class="form-control">
                        </div>

                        <div class="form-group col-md-6">
                            <label class="required-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>

                        <div class="form-group col-md-6">
                            <label class="required-label">Personal Email</label>
                            <input type="email" name="personal_email" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password" class="label-required">Password</label>
                                <input name="password" type="password" class="form-control" value="password" >
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password_confirmation" class="label-required">Confirm Password</label>
                                <input name="password_confirmation" type="password" class="form-control" value="password" >
                            </div>
                        </div>

                        <div class="form-group col-md-6">
                            <label class="required-label">Phone</label>
                            <input type="text" name="phone" class="form-control">
                        </div>

                        <div class="form-group col-md-6">
                            <label class="required-label">Country</label>
                            <select class="form-control select2" id="country_id" name="country_id" >
                                @foreach($countries as $country)
                                    <option value="{{$country->id}}"
                                            data-phone="{{$country->phone_extension}}">{{$country->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="city" class="label-required">City</label>
                                <input name="city" type="text" class="form-control" value="">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="address" class="label-required">Address</label>
                                <input name="address" type="text" class="form-control" value="">
                            </div>
                        </div>
                    </div>


                    <div class="row" id="service-provider">
                        <div class="form-group col-md-6">
                            <label class="required-label" for="contract_type">Contract type</label>
                            <select class="form-control" id="contract_type" name="contract_type">
                                @foreach($contract_types as $contract_type)
                                    <option {{$contract_type == old('contract_type') ? 'selected' : ''}} value="{{$contract_type}}" >{{$contract_type}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6 freelancer">
                            <label class="required-label">Contract rate price</label>
                            <input type="text" value="{{old('contract_rate_price')}}" name="contract_rate_price" class="form-control">
                        </div>
                        <div class="form-group col-md-6 freelancer">
                            <label class="required-label" for="contract_rate_price_per">Contract rate price per</label>
                            <select class="form-control" id="contract_rate_price_per" name="contract_rate_price_per">
                                @foreach(['Session','Hour'] as $contract_rate_price_per)
                                    <option {{$contract_type == old('contract_rate_price_per') ? 'selected' : ''}} value="{{$contract_rate_price_per}}" >{{$contract_rate_price_per}}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    <div class="row">
                        <div class="form-group col-md-12">
                            <label class="required-label">Service Types</label>
                            <select class="form-control w-100 select2" id="service_types" name="service_types[]" multiple>
                                @foreach($service_types as $service_type)
                                    <option {{ (collect(old('service_types'))->contains($service_type->id)) ? 'selected':'' }} value="{{$service_type->id}}">{{$service_type->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="city" class="label-required">Attach contract</label>
                            <div class="custom-file">
                                <input class="custom-file-input" type="file" name="attach_contract"  id="attach_contract" aria-describedby="attach_contract" >
                                <label class="custom-file-label" for="attach_contract">Attach contract</label>
                            </div>
                            <label id="attach_contract_file"></label>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="city" class="label-required">Attach CV</label>
                            <div class="custom-file">
                                <input class="custom-file-input" type="file" name="attach_cv"  id="attach_cv" aria-describedby="attach_cv" >
                                <label class="custom-file-label" for="attach_cv">Attach CV</label>
                            </div>
                            <label id="attach_cv_file"></label>
                        </div>
                        <div class="repeater container" id="certificates_list">
                            <label for="city" class="label-required">Attach certificates</label>
                            <div data-repeater-list="certificates_list">
                                <div data-repeater-item>
                                    <div class="row" >
                                        <div class="form-group col-md-6">
                                            <div class="custom-file">
                                                <input class="custom-file-input" type="file" name="certificate"  id="certificate" aria-describedby="certificate" >
                                                <label class="custom-file-label" for="certificate">Attach certificate</label>
                                            </div>
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
