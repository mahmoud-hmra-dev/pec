{{-- ADD MODEL START --}}
<div id="add-modal" class="modal fade-scale" role="dialog" tabindex="-1" aria-hidden="true" aria-labelledby="ModelLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="ModelLabel">Add New Patient</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="frm_add" class="add-form" method="POST">

                    {{ csrf_field() }}

                    <div class="row" id="user">
                        <h5 class="col-md-12 mt-3">User Info :</h5>
                        <input name="id" type="hidden">
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
                                <input name="password" type="password" class="form-control" value="" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password_confirmation" class="label-required">Confirm Password</label>
                                <input name="password_confirmation" type="password" class="form-control" value="" required>
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

                        <div class="col-md-6" id="street">
                            <div class="form-group">
                                <label for="street" class="label-required">Street</label>
                                <input name="street" type="text" class="form-control" value="">
                            </div>
                        </div>
                    </div>


                    <div class="row" id="patient">
                        <h3 class="col-md-12 mt-3">Patient Info :</h3>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="patient_no" class="label-required">Patient no </label>
                                <input name="patient_no" id="height" type="patient_no"  min="0" max="10000000" class="form-control patient_no" value="{{old('patient_no')}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="birth_of_date" class="label-required">Birth of date</label>
                                <input name="birth_of_date" id="birth_of_date" type="date" class="form-control" value="{{old('birth_of_date')}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="height" class="label-required">Height <small>(cm)</small></label>
                                <input name="height" id="height" type="number" step="0.1" min="0" max="300" class="form-control calculate_BMI" value="{{old('height')}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="weight" class="label-required">Weight <small>(kg)</small></label>
                                <input name="weight" id="weight" type="number" step="0.1" min="0" max="300" class="form-control calculate_BMI" value="{{old('weight')}}">
                            </div>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="BMI" class="label-required"> </label>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="is_over_weight" switch="primary" name="is_over_weight" @if(old('is_over_weight')) checked @endif/>
                                <label for="is_over_weight" class="custom-control-label">Over Weight</label>
                            </div>
                        </div>
                        <div class="form-group col-md-3">
                            <label class="required-label" for=""> </label>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="is_eligible" switch="primary" name="is_eligible" @if(old('is_eligible')) checked @endif/>
                                <label for="is_eligible" class="custom-control-label">Eligible</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="BMI" class="label-required">BMI</label>
                                <input name="BMI" id="BMI" type="number" readonly step="0.01" min="0" max="300" class="form-control" value="{{old('BMI')}}">
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="required-label" for="comorbidities">Comorbidities</label>
                            <textarea name="comorbidities" id="comorbidities" class="form-control">{{old("comorbidities")}}</textarea>
                        </div>

                        <div class="form-group col-md-6">
                            <label class="required-label" for="gender">Gender</label>
                            <select class="form-control" id="gender" name="gender">
                                @foreach($genders as $gender)
                                    <option value="{{$gender}}" @if(old('gender') == $gender) selected @endif>{{$gender}}</option>
                                @endforeach
                            </select>
                        </div>



                        <div class="form-group col-md-6" id="is_pregnant_container">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="is_pregnant" switch="primary" name="is_pregnant" @if(old('is_pregnant')) checked @endif/>
                                <label for="is_pregnant" class="custom-control-label">Pregnant</label>
                            </div>
                        </div>


                        <div class="form-group col-md-6">
                            <label class="required-label" for="hospital">Hospital</label>
                            <select class="form-control select2" id="hospital" name="hospital_id">
                                @foreach($hospitals as $single)
                                    <option value="{{$single->id}}" @if(old('hospital_id') == $single->id) selected @endif>{{$single->name}}</option>
                                @endforeach
                            </select>
                        </div>


                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="reporter_name" class="label-required">Reporter Name</label>
                                <input name="reporter_name" id="reporter_name" type="text" class="form-control" value="{{old('reporter_name')}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="discuss_by" class="label-required">Discuss by</label>
                                <input name="discuss_by" id="discuss_by" type="text" class="form-control" value="{{old('discuss_by')}}">
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
