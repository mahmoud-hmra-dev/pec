<div id="contact-modal" class="modal fade-scale" role="dialog" tabindex="-1" aria-hidden="true" aria-labelledby="contactModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="contactModalLabel">Program Contact</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form class="add-form" method="POST">
                    {{ csrf_field() }}
                    <div class="row" id="data-form">
                        <div class="form-group col-md-6">
                            <label class="required-label" for="name">Name</label>
                            <input type="text" name="name" id="name" class="form-control" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="required-label" for="contact_role">Role</label>
                            <select class="form-control" name="contact_role" id="contact_role">
                                <option value="global_study_manager">Global Study Manager</option>
                                <option value="assistant_manager">Assistant Manager</option>
                                <option value="finance_responsible">Finance Responsible</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" class="form-control">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="phone">Phone</label>
                            <input type="text" name="phone" id="phone" class="form-control">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="title">Title</label>
                            <select name="title" id="title" class="form-control">
                                <option value="">Select title</option>
                                <option value="Mr">Mr</option>
                                <option value="Mrs">Mrs</option>
                                <option value="Dr">Dr</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6 d-none" id="custom_title_group">
                            <label class="required-label" for="custom_title">Custom title</label>
                            <input type="text" name="custom_title" id="custom_title" class="form-control">
                        </div>
                    </div>
                    <div class="form-group m-2 float-right">
                        <button type="submit" class="edit btn btn-primary mr-2">Save</button>
                        <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
