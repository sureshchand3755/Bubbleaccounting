@extends('homeheader')
@section('content')
    <div class="herosection">
      <div class="container-fluid">
        <div class="row align-items-start">
          <div class="col-lg-6 col-md-12 col-sm-12">
            <div class="hero_content bubble_books pe-lg-5">
              <img src="<?php echo URL::to('public/staticpages/img/bubble-icon-color.png'); ?>" width="50" alt="bubble color icon">

              <h1>View a demo of Bubble.ie <span>Modern Accountancy Practice Management </span>Platform</h1>

              <div class="ol-lg-12 my-5">
                <button class="btn btn-primary">View Demo <i class="fas fa-play"></i></button>
              </div>
            </div>

          </div>
          <div class="col-lg-6 col-md-12 col-sm-12">
            <div class="rtimg">
              <img class="d-sm-none d-lg-block img-fluid" src="<?php echo URL::to('public/staticpages/img/view_demo.png'); ?>" alt="view demo">
            </div>
          </div>
        </div>
      </div>

    </div>

  </div>
  @stop