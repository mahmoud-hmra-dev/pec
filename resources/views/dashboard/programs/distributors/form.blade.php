{{-- ADD MODEL START --}}
<div id="add-modal" class="modal fade-scale" role="dialog" tabindex="-1" aria-hidden="true" aria-labelledby="ModelLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="ModelLabel">Add New Distributor</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="frm_add" class="add-form" method="POST">

                    {{ csrf_field() }}

                    <div class="row">


                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name" class="label-required">Name</label>
                            <input name="name" id="name" type="text" class="form-control" value="{{old("name")}}">
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="required-label" for="country_id">Country</label>
                        <select class="form-control countries" id="country_id" name="country_id" >
                            @foreach($countries as $country)
                                <option value="{{$country->id}}" @if(old("country_id") == $country->id) selected @endif>{{$country->name}}</option>
                            @endforeach
                        </select>
                    </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="contract_person" class="label-required">Contract person</label>
                                <input name="contract_person" id="contract_person" type="text" class="form-control" value="{{old("contract_person")}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email" class="label-required">Email</label>
                                <input name="email" id="email" type="email" class="form-control" value="{{old("email")}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone" class="label-required">Phone</label>
                                <input name="phone" id="phone" type="text" class="form-control" value="{{old("phone")}}">
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

