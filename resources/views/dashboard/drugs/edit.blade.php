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
        <form id="frm_add" class="add-form" action="{{route("drugs.update",$drug->id)}}" method="POST" enctype="multipart/form-data">

            {{ csrf_field() }}
            @method('PUT')

            <div class="row align-items-center">

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name" class="label-required">Name</label>
                        <input name="name" id="name" type="text" class="form-control" value="{{old('name') ?? $drug->name}}">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="api_name" class="label-required">Api Name</label>
                        <input name="api_name" id="api_name" type="text" class="form-control" value="{{old('api_name') ?? $drug->api_name}}">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="drug_id" class="label-required">Drug ID</label>
                        <input name="drug_id" id="drug_id" type="text" class="form-control" value="{{old('drug_id') ?? $drug->drug_id}}">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="drug_initial" class="label-required">Drug Initial</label>
                        <input name="drug_initial" id="drug_initial" type="text" class="form-control" value="{{old('drug_initial') ?? $drug->drug_initial}}">
                    </div>
                </div>


            </div>

            <div class="row justify-content-end">
                <div class="form-group mt-2">
                    <button type="submit" class="edit btn btn-primary px-5" >
                        Save
                    </button>
                </div>
            </div>
        </form>
    </div>


@stop
@push('scripts')
    <script>

    </script>
@endpush
