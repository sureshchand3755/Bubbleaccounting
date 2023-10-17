@extends('homeheader')
@section('content')
    <div class="herosection">
      <div class="container-fluid">
        <div class="row align-items-start">
          <div class="col-lg-6 col-md-12 col-sm-12">
            <div class="form">
              <ul class="nav nav-tabs">
                <li class="nav-item">
                  <a class="nav-link active" id="login-tab" data-bs-toggle="tab" href="#login-form">Login</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link reg" id="register-tab" data-bs-toggle="tab" href="#register-form">Register</a>
                </li>
              </ul>

              <div class="tab-content">
                <div class="tab-pane fade show active" id="login-form">
                  <form>
                    <div class="formHeading">
                      <img src="<?php echo URL::to('public/staticpages/img/bubble-color-logo.png'); ?>" width="90" alt="bubble logo" />
                      <h4>"Employer secure Login to access your Staff Payroll & Associated Reports"</h4>
                    </div>
                    <div class="mb-3">
                      <label for="employer_no" class="form-label">Employer No</label>
                      <input type="text" class="form-control" id="employer_no" placeholder="Employer No">
                    </div>
                    <div class="mb-3">
                      <label for="login-email" class="form-label">Email</label>
                      <input type="email" class="form-control" id="login-email" placeholder="Enter your email">
                    </div>
                    <div class="mb-3">
                      <label for="login-password" class="form-label">Password</label>
                      <input type="password" class="form-control" id="login-password" placeholder="Enter your password">
                    </div>

                    <button type="submit" class="btn btn-primary">Login</button>
                  </form>
                </div>
                <div class="tab-pane fade" id="register-form">
                  <form>
                    <div class="formHeading">
                      <img src="<?php echo URL::to('public/staticpages/img/bubble-color-logo.png'); ?>" width="90" alt="bubble logo" />
                      <h4>"Employer Register to access your Staff Payroll & Associated Reports"</h4>
                    </div>
                    <div class="mb-3">
                      <label for="register-name" class="form-label">Name</label>
                      <input type="text" class="form-control" id="register-name" placeholder="Enter your name">
                    </div>
                    <div class="mb-3">
                      <label for="register-email" class="form-label">Email</label>
                      <input type="email" class="form-control" id="register-email" placeholder="Enter your email">
                    </div>
                    <div class="mb-3">
                      <label for="register-password" class="form-label">Password</label>
                      <input type="password" class="form-control" id="register-password"
                        placeholder="Enter your password">
                    </div>
                    <button type="submit" class="btn btn-primary">Register</button>
                  </form>
                </div>
              </div>
            </div>

          </div>
          <div class="col-lg-6 col-md-12 col-sm-12">
            <div class="rtimg hide_sm">
              <img class="d-sm-none d-lg-block img-fluid" src="<?php echo URL::to('public/staticpages/img/accesspayroll.png'); ?>" alt="Access Payroll">
            </div>
          </div>
        </div>
      </div>

    </div>

  </div>
@stop