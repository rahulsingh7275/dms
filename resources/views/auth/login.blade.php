@extends('layouts.blank')

@section('title', 'Login')

@section('content')

<div class="container-fluid loginbg">
            <div class="row">
                <div class="col-md-12">
                    <div class="login-box mt-2 min-Loginheight">
                        <div class="row">
                            <div class="col-sm-12 col-md-7 position-relative min-Loginheight">
                                {{-- <div class="uplogo">
                                    <img src="/assets/admin/img/dms-login-logo.png" class="w-50"  />
                                </div> --}}
                                <div class="yogiimg">
                                     <img src="/assets/admin/img/backpage.png"   /> 
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="loginArea"> 
                                    <form method="POST" action="{{ route('login.post') }}" id="myform">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="loginTabs">
                                                    
                                                    <div class="tab-content" id="myTabContent">
                                                        <div class="tab-pane fade show active" id="organization" role="tabpanel" aria-labelledby="org-tab">

                                                            <h5 class="mb-3 text-success">Welcome to
                                                                                                                         DMS
                                                                                                                           Portal</h5>

                                                            <h5 class="mb-5 "><i class="fa fa-sign-in"></i> Enter your Credentials</h5>
															 
                                                            <div class="row my-4">
                                                                <div class="col-lg-12">
                                                                    <div class="form-group">
                                                                       <label class="form-label">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required autofocus>
                    @error('email')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                                                        
                                                                    </div>
                                                                </div>

                                                                <div class="col-lg-12">
                                                                    <div class="form-group">
                                                                         <label class="form-label">Password</label>
                    <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required>
                    @error('password')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                                                        <a onclick="show_password();" class="eyebtn"><img src="https://dsmnruerp.in/assets/admin/img/eye-inactive.svg" /></a>
                                                                                                                                            </div>
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <label class="logCheck">Remember Me
                                                                        <input type="checkbox" id="remember" name="remember">
                                                                        <span class="checkmark"></span>
                                                                    </label>
                                                                </div>
                                                                <div class="col-lg-6 text-right">
                                                                    <a href="https://dsmnruerp.in/admin/forgot-password" class="text-black">Forgot Password?</a>
                                                                </div>

                       
                                                                <div class="col-md-12">
                                                                    <button type="submit" class="btn btn-primary mt-4">Sign In
                                                                        <!--<img src="https://dsmnruerp.in/images/Ajux_loader.gif" />-->
                                                                    </button>
                                                                </div>
                                                                 
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div class="mt-5">
                                                        <p class="f-12 mb-0">For any query/issue, please write to <a href="mailto:dsmnru.help@gmail.com" class="text-orange">dms.help@gmail.com</a></p>
                                                        <p class="f-12 text-muted mb-0">This site is best viewed with latest version of all browsers.</p>
                                                    </div>
                                                </div>
                                            </div>
                                            

                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="rightreserved f-12 py-2">Powered by Staqo World Pvt Ltd.</div>
                </div>
            </div>
        </div>

    </div>



    <!-- Success Alert Modal -->
    <div id="success-alert-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content modal-filled bg-success">
                <div class="modal-body p-4">
                    <div class="text-center">
                        <i class="dripicons-checkmark h1 text-white"></i>
                        <h4 class="mt-2 text-white">Well Done!</h4>
                        <p class="mt-3 text-white">Logged in successfully.</p>
                        <a href="https://dsmnruerp.in/admin" class="btn btn-light my-2">Continue</a>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
<script>
    function show_password() {
        var passwordInput = document.getElementById('password');
        var eyeBtn = document.querySelector('.eyebtn img');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeBtn.src = 'https://dsmnruerp.in/assets/admin/img/eye-active.svg';
        } else {
            passwordInput.type = 'password';
            eyeBtn.src = 'https://dsmnruerp.in/assets/admin/img/eye-inactive.svg';
        }
    }
</script>

@endsection