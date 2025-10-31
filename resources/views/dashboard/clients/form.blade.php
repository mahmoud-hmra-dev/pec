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
                        <input type="hidden" name="id">
                        {{--<div class="form-group col-md-6">
                            <label class="required-label">First Name</label>
                            <input type="text" name="first_name" class="form-control">
                        </div>

                        <div class="form-group col-md-6">
                            <label class="required-label">Last Name</label>
                            <input type="text" name="last_name" class="form-control">
                        </div>--}}

                        <div class="form-group col-md-6">
                            <label class="required-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>

                        {{--<div class="form-group col-md-6">
                            <label class="required-label">Personal Email</label>
                            <input type="email" name="personal_email" class="form-control">
                        </div>--}}
                        <input name="password" type="hidden" class="form-control" value="password">

                        <div class="form-group col-md-6">
                            <label class="required-label">Phone</label>
                            <input type="text" name="phone" class="form-control">
                        </div>

                        <div class="form-group col-md-6">
                            <label class="required-label">Country</label>
                            <select class="form-control select2 w-100"  id="country_id" name="country_id" >
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

                        {{--<div class="col-md-6">
                            <div class="form-group">
                                <label for="address" class="label-required">Address</label>
                                <input name="address" type="text" class="form-control" value="">
                            </div>
                        </div>--}}
                    </div>
                    <div class="row" id="client">
                        <div class="form-group col-md-6">
                            <label class="required-label">Client Name</label>
                            <input type="text" name="client_name" class="form-control">
                        </div>
                        <div class="form-group col-md-6">
                            <label class="required-label">Client Address</label>
                            <input type="text" name="client_address" class="form-control">
                        </div>

                    </div>
                    <div class="row">
                        <div class="form-group col-md-6 " id="safety_report_document">
                            <label for="safety_report_document" class="label-required">Attach Safety report document</label>
                            <div class="custom-file">
                                <input class="custom-file-input" type="file" name="safety_report_document"   aria-describedby="safety_report_document" >
                                <label class="custom-file-label" for="safety_report_document">Attach Safety report document</label>
                            </div>
                            <label id="safety_report_document_file"></label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="repeater container" id="documents">
                            <label for="documents" class="label-required">Documents</label>
                            <div data-repeater-list="documents">
                                <div data-repeater-item>
                                    <div class="row" >
                                        <div class="form-group col-sm-4">
                                            <label class="required-label">Name</label>
                                            <div class="custom-file">
                                                <input class="custom-file-input" type="file" name="name"  id="name" aria-describedby="name" >
                                                <label class="custom-file-label" for="name">Attach Document</label>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <label class="required-label">Type</label>
                                            <select class="form-control" id="document_type_id" name="document_type_id" >
                                                @foreach($types as $type)
                                                    <option value="{{$type->id}}" {{$type->id == old('document_type_id') ? 'selected' : ''}}>{{$type->name}}</option>
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
