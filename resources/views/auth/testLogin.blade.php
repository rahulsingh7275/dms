@extends('layouts.blank')

@section('title', 'Test Login')

@section('content')

<div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="login-box mt-2 min-Loginheight">
                        <div class="row">
                            <div class="col-sm-12 col-md-7 position-relative min-Loginheight">
                                <div class="uplogo">
                                    <img src="https://dsmnruerp.in/assets/admin/img/login-logo.png" />
                                </div>
                                <div class="yogiimg">
                                     <img src="https://dsmnruerp.in/assets/admin/img/loginbg.png"   /> 
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="loginArea"> 
                                    <form method="POST" action="https://dsmnruerp.in/admin/login" id="myform">
                                        <input type="hidden" name="_token" value="fLrpHQcpPTBAkpJ1UtgdJzu92gP4MdJlIsJfRLDb">                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="loginTabs">
                                                    
                                                    <div class="tab-content" id="myTabContent">
                                                        <div class="tab-pane fade show active" id="organization" role="tabpanel" aria-labelledby="org-tab">

                                                            <h5 class="mb-3 text-success">Welcome to
                                                                                                                         University
                                                                                                                           Portal</h5>

                                                            <h5 class="mb-5 "><i class="fa fa-sign-in"></i> Enter your Credentials</h5>
															 
                                                            <div class="row my-4">
                                                                <div class="col-lg-12">
                                                                    <div class="form-group">
                                                                        <label for="user_name">Login ID<span class="text-danger">*</span></label>
                                                                        <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp" placeholder="Enter your username">
                                                                        
                                                                    </div>
                                                                </div>

                                                                <div class="col-lg-12">
                                                                    <div class="form-group">
                                                                        <label for="password">Password<span class="text-danger">*</span></label>
                                                                        <input type="password" class="form-control position-relative" id="password" name="password" placeholder="Enter your password" maxlength="15">
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

                        <div class="form-group">
                            <div class="col-md-6">
                                <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response-6a2fa28c9deb8"><script>
  grecaptcha.ready(function() {
      grecaptcha.execute('6Lc_WLUfAAAAALifvZW6ZwDhGBRysm0n1pptXY4g', {action: 'adminportal'}).then(function(token) {
         document.getElementById('g-recaptcha-response-6a2fa28c9deb8').value = token;
      });
  });
  </script>
                                                            </div>
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
                                                        <p class="f-12 mb-0">For any query/issue, please write to <a href="mailto:dsmnru.help@gmail.com" class="text-orange">dsmnru.help@gmail.com</a></p>
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
                    <div class="rightreserved f-12 my-2">Powered by Staqo World Pvt Ltd.</div>
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

@endsection