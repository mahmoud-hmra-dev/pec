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
                        <div class="form-group col-md-12 FOC">
                            <label class="required-label" for="service_provider_type_id">Coordinator</label>
                            <select class="form-control select2" id="service_provider_type_id" name="service_provider_type_id" readonly disabled>
                                @foreach($coordinators as $single)
                                    <option value="{{$single->id}}" @if(old('service_provider_type_id') == $single->id) selected @endif>{{$single->service_provider->user->first_name ." ". $single->service_provider->user->last_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6 FOC">
                            <label class="required-label" for="site_notified">Site notified</label>
                            <select class="form-control select2" id="site_notified" name="site_notified">
                                @foreach(['Yes', 'No']  as $single)
                                    <option value="{{$single}}" @if(old('site_notified') == $single) selected @endif>{{$single}}</option>
                                @endforeach
                            </select>
                        </div>


                        <div class="form-group col-md-6 FOC">
                            <label class="required-label" for="notification_method">Notification method</label>
                            <select class="form-control select2" id="notification_method" name="notification_method">
                                @foreach(['Email', 'Call']  as $single)
                                    <option value="{{$single}}" @if(old('notification_method') == $single) selected @endif>{{$single}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6 FOC">
                            <label class="required-label" for="collected_from_pharmacy">Collected from pharmacy</label>
                            <select class="form-control select2" id="collected_from_pharmacy" name="collected_from_pharmacy">
                                @foreach(['Yes', 'No']  as $single)
                                    <option value="{{$single}}" @if(old('collected_from_pharmacy') == $single) selected @endif>{{$single}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6 FOC">
                            <label class="required-label" for="warehouse_call">Warehouse call</label>
                            <select class="form-control select2" id="warehouse_call" name="warehouse_call">
                                @foreach(['Yes', 'No']  as $single)
                                    <option value="{{$single}}" @if(old('warehouse_call') == $single) selected @endif>{{$single}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-12" id="attachment_div">
                            <label for="city" class="label-required" >Attachment</label>
                            <div class="custom-file">
                                <input class="custom-file-input" type="file" name="attachment"   aria-describedby="image" >
                                <label class="custom-file-label" for="attachment">Attach attachment</label>
                            </div>
                            <label id="attachment"></label>
                        </div>
                        <div class="col-md-6 FOC">
                            <div class="form-group">
                                <label for="reminder_at" class="label-required">Reminder date</label>
                                <input name="reminder_at" id="reminder_at" type="datetime-local" class="form-control" readonly value="{{old('reminder_at')}}">
                            </div>
                        </div>
                        <div class="col-md-6 FOC">
                            <div class="form-group">
                                <label for="start_at" class="label-required">Date</label>
                                <input name="start_at" id="start_at" type="datetime-local" class="form-control" readonly value="{{old('start_at')}}">
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
        $('#attachment_div').hide();
        if($('#site_notified').val() === "Yes")
            $('#attachment_div').show();
        else
            $('#attachment_div').hide();

        $('#site_notified').on('change',function (event) {
            if($(this).val() === "Yes")
                $('#attachment_div').show();
            else
                $('#attachment_div').hide();
        });
    </script>
@endpush

