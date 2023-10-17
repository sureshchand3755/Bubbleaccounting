@extends('homeheader')
@section('content')
    <div class="herosection">
      <div class="container-fluid">
        <div class="row align-items-start">
          <div class="col-lg-6 col-md-12 col-sm-12">
            <div class="hero_content bubble_books pe-lg-5">
              <img src="<?php echo URL::to('public/staticpages/img/bubble-icon-color.png'); ?>" width="50" alt="bubble color icon">
              <p>COMING SOON</p>

              <h1>Bubble Books, <span>a modern integrated bookeeping system for small and medium sized
                  enterprises.</span></h1>

              <div class="row mt-5">
                <div class="col-lg-8 col-md-8 mb-4">
                  <input type="text" class="form-control p-3 input-shadow" placeholder="Your Email Address"
                    aria-label="Recipient's username" aria-describedby="button-addon2">
                </div>
                <div class="col-lg-4 col-md-4">
                  <button class="btn btn-primary w-100 p-3" type="button" id="button-addon2">Start free trial</button>
                </div>


              </div>
            </div>

          </div>
          <div class="col-lg-6 col-md-12 col-sm-12">
            <div class="rtimg hide_sm">
              <img class="img-fluid " src="<?php echo URL::to('public/staticpages/img/bubble_books.png'); ?>" alt="slider graphic">
            </div>
          </div>
        </div>
      </div>

    </div>

  </div>
@stop