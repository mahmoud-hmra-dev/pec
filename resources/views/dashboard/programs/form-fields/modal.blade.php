<div id="field-modal" class="modal fade-scale" role="dialog" tabindex="-1" aria-hidden="true" aria-labelledby="fieldModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="fieldModalLabel">Custom Field</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form class="add-form" method="POST" onsubmit="prepareOptions(event)">
                    {{ csrf_field() }}
                    <div class="row" id="data-form">
                        <div class="form-group col-md-6">
                            <label class="required-label" for="label">Label</label>
                            <input type="text" name="label" id="label" class="form-control" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="required-label" for="field_key">Field key</label>
                            <input type="text" name="field_key" id="field_key" class="form-control" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="required-label" for="field_type">Field type</label>
                            <select name="field_type" id="field_type" class="form-control">
                                <option value="text">Text</option>
                                <option value="textarea">Textarea</option>
                                <option value="number">Number</option>
                                <option value="date">Date</option>
                                <option value="select">Dropdown</option>
                                <option value="checkbox">Checkbox</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="is_required">Required</label>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="is_required" name="is_required">
                                <label class="custom-control-label" for="is_required">Yes</label>
                            </div>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="display_order">Order</label>
                            <input type="number" name="display_order" id="display_order" class="form-control" min="0" value="0">
                        </div>
                        <div class="form-group col-md-12 d-none" id="field-options-wrapper">
                            <label class="required-label" for="options">Options (one per line)</label>
                            <textarea name="options_text" id="options" class="form-control" rows="3"></textarea>
                            <input type="hidden" name="options" id="options_hidden">
                        </div>
                        <div class="form-group col-md-12">
                            <label for="help_text">Help text</label>
                            <textarea name="help_text" id="help_text" class="form-control" rows="2"></textarea>
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
<script>
    function prepareOptions(event) {
        const $form = $(event.target);
        const fieldType = $form.find('#field_type').val();
        if (['select', 'checkbox'].includes(fieldType)) {
            const values = ($form.find('#options').val() || '').split('\n').map(item => item.trim()).filter(Boolean);
            $form.find('#options_hidden').val(JSON.stringify(values));
        } else {
            $form.find('#options_hidden').val(null);
        }
    }
</script>
