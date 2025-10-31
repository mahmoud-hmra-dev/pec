@extends('dashboard.layout.main')
@section('content')
    <div class="p-4">
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        <form id="frm_add" class="add-form" action="{{route("visits.update",$user->id)}}" method="POST">

            {{ csrf_field() }}
            @method("PUT")

            <div class="row align-items-center">

            </div>

            <div class="form-group mt-2 float-right">
                <button type="submit" class="edit btn btn-primary px-5" >
                    Save
                </button>
            </div>
        </form>
    </div>


@stop
@push('scripts')
    <script>

    </script>
@endpush
