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
                                    <option value="{{$single->id}}" @if(old('sub_program_id') == $single->id) selected @endif>{{$single->name .' - ' .($single->drug ? $single->drug->name : '')}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="required-label" for="service_provider_type_id">Nurse</label>
                            <select class="form-control" id="service_provider_type_id" name="service_provider_type_id" readonly disabled>
                                @foreach($nurses as $single)
                                    <option value="{{$single->id}}" @if(old('service_provider_type_id') == $single->id) selected @endif>{{$single->service_provider && $single->service_provider->user ? $single->service_provider->user->first_name ." ". $single->service_provider->user->last_name : ''}}</option>
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
                        <div class="form-group col-md-6">
                            <label class="required-label" for="type_visit">Type</label>
                            <select class="form-control" id="type_visit" name="type_visit" >
                                @foreach([\App\Enums\VisitTypeEnum::Physical,\App\Enums\VisitTypeEnum::Online] as $single)
                                    <option value="{{$single}}" @if(old('type_visit') == $single) selected @endif>{{$single}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12" id="meeting_display">
                            <div class="form-group">
                                <label for="meeting" class="label-required">Meeting</label>
                                <div class="input-group">
                                    <input name="meeting" id="meeting" type="text" class="form-control" readonly value="{{old('meeting')}}">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="button" id="copyButton">
                                            <i id="copyIcon" class="fas fa-copy"></i> <span id="copyText">Copy link</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="row" id="questions">
                        @foreach($questions as $item)
                            @php
                                $index = $loop->index;
                            @endphp
                            <div class="form-group col-md-12">
                                <label class="d-block">{{$item->question}}</label>
                                @switch($item->type->name)
                                    @case('Select Many')
                                        @foreach($item->choices as $option)
                                            <input type="checkbox" id="questions[{{$index}}][content]_{{$loop->index}}" name="questions[{{$index}}][content][]"
                                                   value="{{$option->choice}}" @if(in_array($option->choice, old("questions.$index.content", []))) checked @endif/>
                                            <label for="outsource_template">{{$option->choice}}</label>
                                        @endforeach
                                        @break
                                    @case('Select One')
                                        @foreach($item->choices as $option)
                                            <input type="radio" id="questions[{{$index}}][content]" name="questions[{{$index}}][content]" value="{{$option->choice}}" @if(old("questions[$index][content]") == $option->choice) checked @endif/>
                                            <label for="outsource_template">{{$option->choice}}</label>
                                        @endforeach
                                        @break
                                    @case('Yes/No')
                                        <input type="radio" id="questions[{{$index}}][content]" name="questions[{{$index}}][content]" value="Yes" @if(old("questions[$index][content]") == 'Yes') checked @endif/>
                                        <label for="outsource_template">Yes</label>
                                        <input type="radio" id="questions[{{$index}}][content]" name="questions[{{$index}}][content]" value="No" @if(old("questions[$index][content]") == 'No') checked @endif/>
                                        <label for="outsource_template">No</label>
                                        @break
                                    @case('Free Text')
                                        <textarea name="questions[{{$index}}][content]" id="questions[{{$index}}][content]" class="form-control">{{old("questions[$index][content]")}}</textarea>
                                        @break
                                @endswitch
                                <input type="hidden" name="questions[{{$index}}][question_id]" value="{{$item->id}}">
                            </div>
                        @endforeach
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
        document.addEventListener('DOMContentLoaded', function() {
            var copyButton = document.getElementById('copyButton');
            var copyIcon = document.getElementById('copyIcon');
            var copyText = document.getElementById('copyText');
            var meetingInput = document.getElementById('meeting');

            copyButton.addEventListener('click', function() {
                meetingInput.select();
                document.execCommand('copy');

                copyIcon.classList.remove('fa-copy');
                copyIcon.classList.add('fa-check');
                copyText.textContent = 'Copied!';

                setTimeout(function() {
                    copyIcon.classList.remove('fa-check');
                    copyIcon.classList.add('fa-copy');
                    copyText.textContent = 'Copy link';
                }, 2000);
            });
        });

        $("#type_visit").change(function () {
            const type_visit = $(this).val();
            if (type_visit === "{{\App\Enums\VisitTypeEnum::Online}}") {
                $.ajax({
                    url: "https://test2.clingroup.net/api/v1/meeting",
                    type: "POST",
                    dataType: "json",
                    headers: {
                        "Authorization": "mirotalk_default_secret"
                    },
                    success: function (data) {
                        $("#meeting_display").show();

                        $('#add-modal').find('input[type="text"][name="meeting"]').val(data.meeting);
                    },
                });
            } else {
                $("#meeting_display").hide();
            }
        });
    </script>
@endpush

