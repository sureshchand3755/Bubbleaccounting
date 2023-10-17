@extends('homeheader')
@section('content')
    <div class="herosection fullBg">
      <div class="container-fluid ">
        <div class="row align-items-start">
          <div class="col-lg-5 col-md-12 col-sm-12">
            <div class="rtimg">
              <img class="d-sm-none d-lg-block img-fluid" src="<?php echo URL::to('public/staticpages/img/contact_bubble.png'); ?>" alt="Bubble ie login">
            </div>
          </div>
          <div class="col-lg-7 col-md-12 col-sm-12">
            <div class="ctBg">
              <form class="form p-4">
                <div class="formHeading">
                  <!-- <p>Message Form</p> -->
                  <p>Schedule a Call</p>
                </div>
                <div class="mb-3">
                  <label for="name" class="form-label">Name</label>
                  <input type="email" class="form-control" id="name" placeholder="Your Name">
                </div>
                <div class="mb-3">
                  <label for="email" class="form-label">Email</label>
                  <input type="email" class="form-control" id="email" placeholder="Enter your email">
                </div>
                <div class="mb-3">
                  <label for="phone_no" class="form-label">Phone Number</label>
                  <input type="text" class="form-control" id="phone_no" placeholder="Phone Number">
                </div>
                <p>Choose your desired date and time :</p>
                <div class="mb-3">
                  <input type="text" placeholder="Choose Date" class="form-control" />
                </div>
                <div class="mb-3">
                  <input type="text" placeholder="Choose Time" class="form-control" />
                </div>
                <div class="mb-3">
                  <label for="tmessage" class="form-label">Your Message</label>
                  <textarea type="text" class="form-control" id="tmessage" placeholder="Your Message"></textarea>
                </div>
                <div class="form-group d-flex ml-0 mb-3">
                  <label for="verfifycode" class="col-sm-3 col-form-label bg-dark-subtle text-center">12345</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" id="verfifycode"
                      placeholder="Type verification code in the image" />
                  </div>
                </div>
                <button type="submit" class="btn btn-primary mt-3">Schedule a Call</button>

              </form>
              <div class="row ctSection">
                <h2>Contact Us</h2>
                <div class="col-lg-4">
                  <h4>Visit </h4>
                  <a href="#">Bubble.ie</a>
                  <p>Bubble Tech</p>
                </div>
                <div class="col-lg-4">
                  <h4>Our Address</h4>
                  <p>Unit 3E Deerpark business Centre Oranmore</p>
                  <p>Co Galway, Ireland H91 R8X7</p>
                </div>
                <div class="col-lg-4">
                  <h4>Our Contact</h4>
                  <a href="tel:++ 353 91 447178">++ 353 91 447178</a>
                  <a href="tel:++ 353 87 9188907">++ 353 87 9188907 </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>

  </div>
@stop