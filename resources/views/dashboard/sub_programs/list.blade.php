@extends('dashboard.layout.main')
@section('content')
    <div class="p-4">
        <div class="card p-2">
            <div class="row">
                <div class="col-3">
                    <p><strong>Main Project Name : </strong></p>
                </div>
                <div class="col-3">
                    <p>{{$program->name}}</p>
                </div>
                <div class="col-3">
                    <p><strong>Client : </strong></p>
                </div>
                <div class="col-3">
                    <p>{{$program->client->user->first_name}} {{$program->client->user->last_name}}</p>
                </div>
                <div class="col-3">
                    <p><strong>Project Manager : </strong></p>
                </div>
                <div class="col-3">
                    <p>{{$program->manager->first_name}} {{$program->manager->last_name}}</p>
                </div>
                <div class="col-3">
                    <p><strong>Start : </strong></p>
                </div>
                <div class="col-3">
                    <p>{{$program->started_at}}</p>
                </div>
                <div class="col-3">
                    <p><strong>End : </strong></p>
                </div>
                <div class="col-3">
                    <p>{{$program->ended_at}}</p>
                </div>
            </div>
        </div>
        @foreach($sub_programs as $sub_program)
            <div class="card p-2">
                <div class="row">
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
                        <p><strong>Country : </strong></p>
                    </div>
                    <div class="col-3">
                        <p>{{$sub_program->country->name}}</p>
                    </div>
                    <div class="col-3">
                        <p><strong>Drug : </strong></p>
                    </div>
                    <div class="col-3">
                        <p>{{$sub_program->drug->name}}</p>
                    </div>
                    <div class="col-3">
                        <p><strong>Type : </strong></p>
                    </div>
                    <div class="col-3">
                        <p>{{$sub_program->type}}</p>
                    </div>
                    <div class="col-3">
                        <p><strong>Target Number of Patients : </strong></p>
                    </div>
                    <div class="col-3">
                        <p>{{$sub_program->target_number_of_patients}}</p>
                    </div>
                    <div class="col-3">
                        <p><strong>Eligible : </strong></p>
                    </div>
                    <div class="col-3">
                        <p>{{$sub_program->is_eligible ? "Yes" : "No"}}</p>
                    </div>
                    <div class="col-3">
                        <p><strong>Visits : </strong></p>
                    </div>
                    <div class="col-3">
                        <p>{{$sub_program->has_visits ? $sub_program->visit_every_day : "Does't have"}}</p>
                    </div>
                    <div class="col-3">
                        <p><strong>Calls : </strong></p>
                    </div>
                    <div class="col-3">
                        <p>{{$sub_program->has_calls ? $sub_program->call_every_day : "Does't have"}}</p>
                    </div>
                    <div class="col-3">
                        <p><strong>Start : </strong></p>
                    </div>
                    <div class="col-3">
                        <p>{{$sub_program->started_at}}</p>
                    </div>
                    <div class="col-3">
                        <p><strong>End : </strong></p>
                    </div>
                    <div class="col-3">
                        <p>{{$sub_program->ended_at}}</p>
                    </div>
                    <div class="col-3">
                        <p><strong>End : </strong></p>
                    </div>
                    <div class="col-3">
                        <p>{{$sub_program->ended_at}}</p>
                    </div>
                    <div class="col-3">
                        <p><strong>the treatment ended by program end : </strong></p>
                    </div>
                    <div class="col-3">
                        <p>{{$sub_program->is_treatment_duration_ended_by_program ? "Yes" : "No"}}</p>
                    </div>
                    <div class="col-12">
                        <a href="{{route('sub_program_timeline',$sub_program->id)}}" class="btn btn-primary">View Time Line</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>


@stop
@push('scripts')
    <script>

    </script>
@endpush
