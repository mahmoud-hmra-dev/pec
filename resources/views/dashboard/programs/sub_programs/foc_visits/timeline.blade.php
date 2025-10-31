@extends('dashboard.layout.main')
@section('content')
    <div class="p-4">
        <div class="card p-2">
            <div class="row">
                <div class="col-12"><h4>Sub Program Details</h4></div>
                <div class="col-3">
                    <p><strong>Name : </strong></p>
                </div>
                <div class="col-3">
                    <p>{{$sub_program->name}}</p>
                </div>
                <div class="col-3">
                    <p><strong>Program Initial : </strong></p>
                </div>
                <div class="col-3">
                    <p>{{$sub_program->program_initial}}</p>
                </div>
                <div class="col-3">
                    <label class="required-label" for="sub_program_patient_id">Patients</label>
                    <select class="form-control" id="sub_program_patient_id" name="sub_program_patient_id">
                        <option value="">Your option</option>
                        @foreach($sub_program_patients as $single)
                            <option value="{{$single->id}}" @if(old('sub_program_patient_id') == $single->id) selected @endif>{{$single->patient->user->first_name}} {{$single->patient->user->last_name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label for="start_date_filter" class="label-required">Start at</label>
                        <input name="start_date_filter" id="start_date" type="datetime-local" class="form-control" value="{{old('start_date_filter',$sub_program->start_date)}}">
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label for="finish_date_filter" class="label-required">End at</label>
                        <input name="finish_date_filter" id="finish_date" type="datetime-local" class="form-control" value="{{old('finish_date_filter',$sub_program->finish_date)}}">
                    </div>
                </div>
            </div>
            <div class="m-2 text-right">
                <button class="edit btn btn-xs btn-danger" id="resetFilter">Reset</button>
            </div>
        </div>

        <div class="card p-2" id="timeline_holder" >
            <div id="timeline">

            </div>
        </div>
        <div class="card p-2" id="item_details" style="display: none">

        </div>
    </div>
    @include('dashboard.programs.sub_programs.foc_visits.form',['sub_program_patients'=>$sub_program_patients,'sub_programs'=>$sub_programs,'coordinators'=>$coordinators])
@stop
@push('extra_styles')
    <link rel="stylesheet" href="{{asset('css/vis.min.css')}}">
@endpush
@push('scripts')
    <script type="text/javascript" src="{{asset("js/vis.min.js")}}"></script>
    <script>
        $(function () {
            const start_date = $("#start_date_filter").val();
            const finish_date = $("#finish_date_filter").val();
            const sub_program_patient_id = '';
            loadVisits(start_date,finish_date,sub_program_patient_id);
        });
        function updateVisitList() {
            const start_date = $("#start_date_filter").val();
            const finish_date = $("#finish_date_filter").val();
            const sub_program_patient_id = $("#sub_program_patient_id").val();
            loadVisits(start_date,finish_date,sub_program_patient_id);
        }
        $("#start_date_filter, #finish_date_filter, #sub_program_patient_id").change(updateVisitList);


        $("#resetFilter").click(function() {
            $("#start_date_filter").val("{!! $sub_program->start_date !!}");
            $("#finish_date_filter").val("{!! $sub_program->finish_date !!}");
            $("#sub_program_patient_id").val("");
            updateVisitList();
        });

        $('.add-form').on('submit', function (event) {
            SaveItemTimeLine(event, this, $(this).attr('action'));
            $('.overlay').remove();
        });


        const openEdit = (data) => {
            let $modal = $('#add-modal');
            let $form = $('.add-form');
            $form[0].reset();
            $form.append('<input type="hidden" name="_method" value="PUT">');
            $form.attr('action', `dashboard/sub_programs/`+data.sub_program_id+`/foc/` + data.model_id);
            $modal.find('.modal-title').text(`Edit FOC visit`);
            $modal.find('#afm_btnSaveIt').text(`Update`);
            clearErrors($modal);
            clearErrors($modal);
            var select2 = $('.select2').each(function() {
                $(this).select2({
                    dropdownParent: $(this).parent(),
                    width: '100%',
                })
            });
            select2.val([]).trigger('change');
            _fill($modal, data);
            $modal.modal('show').removeClass('out').addClass('in');
        };

        function loadVisits(start_date, finish_date,sub_program_patient_id) {
            const url = `{{ route('sub_programs.foc_time_line.index', $sub_program->id) }}`;
            const data = {
                start_date,
                finish_date,
                sub_program_patient_id,
            };

            $.get(url, data)
                .done(function (data) {
                    const items = new vis.DataSet([...data.foc_visits]);
                    const container = document.getElementById('timeline');
                    container.innerHTML = "";
                    const options = {};
                    const timeline = new vis.Timeline(container, items, options);
                    timeline.on("click", function (properties) {
                        if(properties.item){
                            const item = items.get(properties.item);
                            const itemCard = $("#item_details");
                            itemCard.empty().show().append(`
            <div class="row">
              <div class="col-md-12"><h4>Details</h4></div>

              <div class="col-md-2"><strong>Date : </strong></div>
              <div class="col-md-2"><p>${item.start_at}</p></div>
            <div class="col-md-2"><strong>Reminder date : </strong></div>
              <div class="col-md-2"><p>${item.reminder_at}</p></div>
              <div class="col-md-3"><a class="edit btn btn-xs btn-primary mb-4" href="javascript:" onclick="openEdit(${JSON.stringify(item).replace(/"/g, '&quot;')})"><i class="mdi mdi-plus-circle-outline"></i>Edit</a></div>
            </div>
          `);
                        }
                    });
                })
                .fail(function (xhr) {
                    console.log(xhr.responseText);
                });
        }

        function SaveItemTimeLine(e, $this, url) {
            e.preventDefault();
            clearErrors($($this));
            var $data = new FormData($this);
            $($this).find('.summernote').each(function () {
                $data.append($(this).attr('name'),$(this).summernote('code'))
            })
            let $url = BASE_URL + url;
            jQuery.ajax({
                type: 'POST',
                url: $url,
                data: $data,
                contentType: false,
                processData: false,
                beforeSend() {
                    jQuery('body').append('<div class="overlay"><i class="fa fa-spinner fa-5x fa-spin mt-5"></i></div>');
                },
                success(xhr) {
                    if (xhr && xhr.message) {
                        new swal(
                            'success',
                            xhr.message,
                            'success'
                        );
                    } else {
                        new swal(
                            'success',
                            'Coupon added successfully',
                            'success'
                        );
                    }
                    $('.modal').modal('hide');
                    clearForm($($this));
                    if (xhr && xhr.redirect) {
                        window.location.replace(xhr.redirect);
                    }

                },
                error: HandleJsonErrors.bind($this),
                complete() {
                    jQuery('body .overlay').remove();
                    updateVisitList();
                }
            });
        }
    </script>
@endpush
