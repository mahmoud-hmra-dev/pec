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
                            <input type="email" name="email" class="form-control">
                        </div>

                        <input name="password" type="hidden" class="form-control" value="password" required>
                        {{--<div class="form-group col-md-6">
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
                        </div>--}}

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
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="patient_no" class="label-required">Patient no </label>
                                <input name="patient_no" id="patient_no" type="text"   class="form-control patient_no" value="{{old('patient_no')}}" readonly>
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
                                <input name="height" id="height" type="number" step="0.1" min="0" max="300" class="form-control" value="{{old('height')}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="weight" class="label-required">Weight <small>(kg)</small></label>
                                <input name="weight" id="weight" type="number" step="0.1" min="0" max="300" class="form-control" value="{{old('weight')}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="BMI" class="label-required">BMI</label>
                                <input name="BMI" id="BMI" type="number" readonly step="0.01" min="0" max="300" class="form-control" value="{{old('BMI')}}">
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="BMI" class="label-required"> </label>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="is_over_weight" switch="primary" name="is_over_weight" @if(old('is_over_weight')) checked @endif/>
                                <label for="is_over_weight" class="custom-control-label">Over Weight</label>
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
                            <label class="required-label">Pregnant</label>
                            <select class="form-control select2" id="pregnant" name="pregnant" >

                                @foreach([\App\Enums\PregnantEnum::YES, \App\Enums\PregnantEnum::NO,\App\Enums\PregnantEnum::POSSIBILITY] as $single)
                                    <option value="{{$single}}">{{$single}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-md-6 ">
                            <label class="required-label" for="hospital">Hospital</label>
                            <select class="form-control select2" id="hospital" name="hospital_id">
                                <option value="" >None</option>
                            @foreach($hospitals as $single)
                                    <option value="{{$single->id}}" @if(old('hospital_id') == $single->id) selected @endif>{{$single->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6 ">
                            <label class="required-label" for="pharmacy_id">Pharmacy</label>
                            <select class="form-control select2" id="pharmacy_id" name="pharmacy_id">
                                <option value="" >None</option>

                            @foreach($pharmacies as $single)
                                    <option value="{{$single->id}}" @if(old('pharmacy_id') == $single->id) selected @endif>{{$single->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 ">
                            <div class="form-group">
                                <label for="reporter_name" class="label-required">Reporter Name</label>
                                <input name="reporter_name" id="reporter_name" type="text" class="form-control" value="{{old('reporter_name')}}">
                            </div>
                        </div>
                        <div class="col-md-6 ">
                            <div class="form-group">
                                <label for="discuss_by" class="label-required">Discuss by</label>
                                <input name="discuss_by" id="discuss_by" type="text" class="form-control" value="{{old('discuss_by')}}">
                            </div>
                        </div>

                        <div class="col-md-6 ">
                            <div class="form-group">
                                <label for="mc_chronic_diseases" class="label-required">Medical conditions Chronic diseases</label>
                                <input name="mc_chronic_diseases" id="mc_chronic_diseases" type="text" class="form-control" value="{{old('mc_chronic_diseases')}}">
                            </div>
                        </div>
                        <div class="col-md-6 ">
                            <div class="form-group">
                                <label for="mc_medications" class="label-required">Medical conditions Medications</label>
                                <input name="mc_medications" id="mc_medications" type="text" class="form-control" value="{{old('mc_medications')}}">
                            </div>
                        </div>
                        <div class="col-md-6 ">
                            <div class="form-group">
                                <label for="mc_surgeries" class="label-required">Medical conditions Surgeries</label>
                                <input name="mc_surgeries" id="mc_surgeries" type="text" class="form-control" value="{{old('mc_surgeries')}}">
                            </div>
                        </div>
                        <div class="col-md-6 ">
                            <div class="form-group">
                                <label for="fmc_chronic_diseases" class="label-required">Family medical conditions chronic diseases</label>
                                <input name="fmc_chronic_diseases" id="fmc_chronic_diseases" type="text" class="form-control" value="{{old('fmc_chronic_diseases')}}">
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="required-label" for=""> </label>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="is_eligible" switch="primary" name="is_eligible" @if(old('is_eligible')) checked @endif/>
                                <label for="is_eligible" class="custom-control-label">Eligible</label>
                            </div>
                        </div>

                        <div class="form-group col-md-12 is_not_eligible">
                            <label class="required-label" for="is_not_eligible">Why is not eligible ?</label>
                            <textarea name="is_not_eligible" id="is_not_eligible" class="form-control">{{old("is_not_eligible")}}</textarea>
                        </div>
                        <div class="row is_eligible col-md-12">
                            <div class="form-group col-md-6 ">
                                <label for="city" class="label-required">Attach document</label>
                                <div class="custom-file">
                                    <input class="custom-file-input" type="file" name="is_eligible_document"   aria-describedby="is_eligible_document" >
                                    <label class="custom-file-label" for="is_eligible_document">Attach document</label>
                                </div>
                                <label id="is_eligible_document"></label>
                            </div>

                            <input type="hidden" name="has_calls" >
                            <input type="hidden" name="call_every_day" >
                            <input type="hidden" name="has_visits" >
                            <input type="hidden" name="visit_every_day" >
                            <div class="form-group col-md-6 " id="nurse_visits">
                                <label class="required-label" for="nurse">Nurse</label>
                                <select class="form-control select2" id="nurse" name="nurse">
                                    <option value="" >Select an option</option>

                                    {{--
                                   @foreach($nurses as $nurse)
                                        <option value="{{$nurse->id}}" @if(old('nurse') == $nurse->id) selected @endif>{{$nurse->service_provider_type->service_provider->user->first_name .' '.$nurse->service_provider_type->service_provider->user->last_name}}</option>
                                    @endforeach--}}
                                </select>
                            </div>
                            <div class="form-group col-md-6 " id="coordinator_calls">
                                <label class="required-label" for="coordinator">Coordinator</label>
                                <select class="form-control select2" id="coordinator" name="coordinator">
                                    <option value="" >None</option>

                                    {{--@foreach($coordinators as $coordinator)
                                      <option value="{{$coordinator->id}}" @if(old('physician') == $coordinator->id) selected @endif>{{$coordinator->service_provider_type->service_provider->user->first_name .' '.$coordinator->service_provider_type->service_provider->user->last_name}}</option>
                                  @endforeach--}}
                                </select>
                            </div>
                            <div class="form-group col-md-6 ">
                                <label class="required-label">Doctor</label>
                                <select class="form-control select2" id="doctor_id" name="doctor_id" >
                                    <option value="" >None</option>

                                @foreach($doctors as $single)
                                        <option value="{{$single->id}}">{{$single->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-12">
                                <label class="required-label" for=""> </label>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="is_consents" switch="primary" name="is_consents" @if(old('is_consents')) checked @endif/>
                                    <label for="is_consents" class="custom-control-label">Is consent</label>
                                </div>
                            </div>
                            <div class="form-group col-md-6 " id="consent_document">
                                <label for="city" class="label-required">Attach e-Consent document</label>
                                <div class="custom-file">
                                    <input class="custom-file-input" type="file" name="consent_document"   aria-describedby="consent_document" >
                                    <label class="custom-file-label" for="consent_document">Attach document</label>
                                </div>
                                <label id="consent_document"></label>
                            </div>

                        </div>

                     </div>
                    <div class="row is_eligible">
                        <div class="repeater container col-md-12" id="documents">
                            <label for="city" class="label-required">Attach documents</label>
                            <div data-repeater-list="documents">
                                <div data-repeater-item>
                                    <div class="row" >
                                        <div class="form-group col-md-4">
                                            <label class="required-label">Name</label>
                                            <div class="custom-file">
                                                <input class="custom-file-input" type="file" name="name"  id="name" aria-describedby="name" >
                                                <label class="custom-file-label" for="name">Attach document</label>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <label class="required-label">Type</label>
                                            <select class="form-control" id="type" name="type" >
                                                @foreach($types as $type)
                                                    <option value="{{$type->name}}" {{$type->name == old('type') ? 'selected' : ''}}>{{$type->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <label for="description" class="label-required">Description</label>
                                            <textarea name="description" id="description" type="text" class="form-control">{{old("description")}}</textarea>
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
                        <div class="form-group col-md-12">
                            <label class="required-label" for=""> </label>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="is_their_safety_report" switch="primary" name="is_their_safety_report" @if(old('is_their_safety_report')) checked @endif/>
                                <label for="is_their_safety_report" class="custom-control-label">Is there safety report</label>
                            </div>
                        </div>
                        <div class="repeater container col-md-12" id="safety_reports">
                            <label for="city" class="label-required">Attach Safety reports</label>
                            <label id="safety_report_document_file"></label>
                            <div data-repeater-list="safety_reports">
                                <div data-repeater-item>
                                    <div class="row" >
                                        <div class="form-group col-md-4">
                                            <label class="required-label">Name</label>
                                            <div class="custom-file">
                                                <input class="custom-file-input" type="file" name="name"  id="name" aria-describedby="name" >
                                                <label class="custom-file-label" for="name">Attach document</label>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <label class="required-label">Title</label>
                                            <input name="title" id="title" type="text" class="form-control" value="{{old("title")}}">
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <label for="description" class="label-required">Description</label>
                                            <textarea name="description" id="description" type="text" class="form-control">{{old("description")}}</textarea>
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
                         <button type="submit" class="edit btn btn-xs btn-primary mr-2" >Save
                         </button>
                         <button type="button" class="btn btn-xs btn-dark" data-dismiss="modal">
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

            $("#weight, #height").on('input', function() {
                let weight = $('#weight').val();
                let height = $('#height').val();
                if (weight != "" && height != "") {
                    height = height / 100;
                    let BMI = weight / (height * height);
                    BMI = parseFloat(BMI).toFixed(2);
                    $("#BMI").val(BMI);
                    if (BMI > 27) {
                        $("#is_over_weight").prop("checked", true);
                    } else {
                        $("#is_over_weight").prop("checked", false);
                    }
                }
            });

        });
        $('#is_pregnant_container').hide();
        $('#gender').on('change',function (event) {
            if($(this).val() === "{{\App\Enums\GenderEnum::FEMALE}}")
                $('#is_pregnant_container').show();
            else
                $('#is_pregnant_container').hide();
        });

        $('.is_eligible').hide();
        $('.is_not_eligible').show();

        $('#is_eligible').change(function() {
            if ($(this).is(':checked')) {
                // Show additional fields
                $('.is_eligible').show();
                $('.is_not_eligible').hide();
            } else {
                // Hide additional fields
                $('.is_eligible').hide();
                $('.is_not_eligible').show();
            }
        });

        $('#safety_reports').hide();
        $('#is_their_safety_report').change(function() {
            if ($(this).is(':checked')) {
                $('#safety_reports').show();
            } else {
                $('#safety_reports').hide();
            }
        });

        $('#consent_document').hide();
        $('#is_consents').change(function() {
            if ($(this).is(':checked')) {
                $('#consent_document').show();
            } else {
                $('#consent_document').hide();
            }
        });


    </script>
@endpush
