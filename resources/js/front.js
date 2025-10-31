window._ = require('lodash');


try{
    window.Popper = require('admin-lte/plugins/popper/popper.min')
    window.$ = window.jQuery = require('admin-lte/plugins/jquery/jquery.min')
    require('bootstrap/js/src/index');
    require('admin-lte/plugins/bootstrap/js/bootstrap.min')
    require('admin-lte/plugins/select2/js/select2.full.min')
    require('admin-lte/plugins/sweetalert2/sweetalert2.all.min')
}catch(e){}

window.axios = require('axios');
require('admin-lte/plugins/toastr/toastr.min')

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

