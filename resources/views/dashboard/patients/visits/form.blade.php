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

                    <div class="row" id="visit">
                        <div class="form-group col-md-6">
                            <label class="required-label" for="sub_program_id">Sub Program</label>
                            <select class="form-control" id="sub_program_id" name="sub_program_id" readonly disabled>
                                @foreach($sub_programs as $single)
                                    <option value="{{$single->id}}" @if(old('sub_program_id') == $single->id) selected @endif>{{$single->name .' - ' . ($single->drug ? $single->drug->name : '')}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="required-label" for="service_provider_type_id">Service provider</label>
                            <select class="form-control" id="service_provider_type_id" name="service_provider_type_id" readonly disabled>
                                @foreach($service_providers as $single)
                                    <option value="{{$single->id}}" @if(old('service_provider_type_id') == $single->id) selected @endif>{{ $single->service_provider && $single->service_provider->user ? $single->service_provider->user->first_name ." ". $single->service_provider->user->last_name : ''}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="required-label" for="activity_type_id">Activity type</label>
                            <select class="form-control" id="activity_type_id" name="activity_type_id" readonly disabled>
                                @foreach($activity_types as $single)
                                    <option value="{{$single->id}}" @if(old('activity_type_id') == $single->id) selected @endif>{{$single->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="should_start_at" class="label-required">Should start at</label>
                                <input name="should_start_at" id="should_start_at" type="datetime-local" class="form-control" readonly value="{{old('should_start_at')}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="start_at" class="label-required">Start</label>
                                <input name="start_at" id="start_at" type="datetime-local" class="form-control" value="{{old('start_at')}}">
                            </div>
                        </div>
                    </div>
                    <div class="row" id="questions">
                    </div>
                    <div class="row">
                        <div class="repeater container" id="documents">
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
                    </div>

                    <div class="row">
                        <div class="form-group col-md-12 edit_sub_program_patient">
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
        });
        $('#safety_reports').hide();
        $('#is_their_safety_report').change(function() {
            if ($(this).is(':checked')) {
                $('#safety_reports').show();
            } else {
                $('#safety_reports').hide();
            }
        });
    </script>
@endpush
