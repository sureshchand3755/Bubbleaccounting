@extends('homeheader')
@section('content')
    <div class="herosection">
      <div class="container-fluid">
        <div class="row align-items-center">
          <div class="col-lg-7 col-md-12 col-sm-12">
            <div class="hero_content">
              <img src="<?php echo URL::to('public/staticpages/img/bubble-icon-color.png'); ?>" width="50" alt="bubble color icon">
              <h1 class="font-lg">Modern <span>Accountancy Practice</span> Platform</h1>
              <p class="my-4">Keep your customer account needs safely organized under one roof. Manage tasks, processes,
                and funds quickly, easily, and efficiently.</p>
              <div class="row mb-3">
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
          <div class="col-lg-5 col-md-12 col-sm-12">
            <div class="rtimg">
              <img class="d-sm-none d-lg-block img-fluid" src="<?php echo URL::to('public/staticpages/img/hero_banner_img.png'); ?>" alt="slider graphic">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- <div class="container ">
    <div class="freeBar">
      <a href="#">Limited "Free Two Month" Available Now</a>
    </div>
  </div> -->
  <!-- Header Section End -->

  <!-- Main Section -->
  <div class="main">
    <div class="container">
      <div class="home_content">
        <div class="row align-items-center">
          <div class="col-sm-12 col-md-6 col-lg-6">
            <img src="<?php echo URL::to('public/staticpages/img/options-visual-1.png'); ?>" alt="visual options" class=" d-lg-block">
          </div>
          <div class="col-sm-12 col-md-6 col-lg-6">
            <div class="dboard">
              <!-- <img src="./img/Screen-display-graphic.png" alt="display graphic"> -->
              <p>Bubble Accounting incorporates a strong Client
                Management and Invoicing System along with associated
                tracking tools to support your practice and incorporate a
                lean process to client management & billing system</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="howitworks">
      <div class="container">
        <h2>How it works</h2>
        <div class="gridbox">
          <div class="card">
            <img src="<?php echo URL::to('public/staticpages/img/how-it-works-visual-1.png'); ?>" alt="how it works">
            <h3>Streamline workflows</h3>
            <p>Centralize workflows, automate daily routines, and eliminate manual data entry with one system of record.
            </p>
          </div>
          <div class="card">
            <img src="<?php echo URL::to('public/staticpages/img/how-it-works-visual-2.png'); ?>" alt="how it works">
            <h3>Focus on billable activities</h3>
            <p>Ensure accurate time recordings, and make sure every minute is always accounted for.</p>
          </div>
          <div class="card">
            <img src="<?php echo URL::to('public/staticpages/img/how-it-works-visual-3.png'); ?>" alt="how it works">
            <h3>Real-time financial insights</h3>
            <p>Monitor performance and finances, identify bottlenecks, and more with customizable dashboards and</p>
          </div>
        </div>
        <a class="moreBtn" href="#">Find out More</a>
      </div>
    </div>
  </div>
  <!-- videosection section -->
  <div class="videosection">
    <div class="container">
      <h2>Everything you need in one application</h2>
      <div class="row py-lg-5">
        <div class="col-lg-6 col-md-6 col-sm-12">
          <div class="sec1">
            <div class="row">
              <div class="col-lg-6"><img src="<?php echo URL::to('public/staticpages/img/clock-cog-computer-graphic.png'); ?>" alt="clock cog computer"></div>
              <div class="col-lg-6">
                <p>
                  As part of the overall Bubble Accounting System a practice carries out task on behalf of client with
                  the ultimate view of billing the client for those tasks.
                </p>
              </div>
            </div>


          </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 ps-lg-5">
          <div class="sec2">
            <div class="ratio ratio-16x9">
              <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/1tWySqrEiZA"
                title="YouTube video player" frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                allowfullscreen></iframe>
            </div>


            <div class="imgbg">
              <img src="<?php echo URL::to('public/staticpages/img/pale-orange-bubble.png'); ?>" alt="">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@stop