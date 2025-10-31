<div id="loginFrm" tabindex="-1" role="dialog" aria-labelledby="LoginFrm" aria-hidden="true" class="modal fade text-left">
    <div role="document" class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header row d-flex justify-content-between mx-1 mx-sm-3 mb-0 pb-0 border-0">
                <h4 class="modal-title">Login Form</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="loginForm" action="{{route('login')}}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="email" class="label-required">Email</label>
                        <input type="email" class="form-control"  name="email" required>
                    </div>
                    @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror

                    <div class="form-group">
                        <label for="password" class="label-required">Password</label>
                        <input type="password" class="form-control"  name="password" required>
                    </div>
                    @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                    <a id="forget_passwordLnk" href="{{route('password.request')}}"><small>forget your password?</small></a>

                    <button class="btn btn-primary float-right">
                        Login
                    </button>

                </form>
            </div>
        </div>
    </div>
</div>
