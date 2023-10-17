@extends('homeheader')
@section('content')
    <div class="herosection">
      <div class="container-fluid">
        <div class="row align-items-start">
          <div class="col-lg-8 col-md-12 col-sm-12">
            <div class="hero_content about_page p-0">
              <img src="<?php echo URL::to('public/staticpages/img/bubble-icon-color.png'); ?>" width="50" alt="bubble color icon">
              <h1>About Bubble.ie and our Modern <span>Accountancy Practice Management</span> Platform</h1>
              <p class="my-4">Welcome to Bubbile.ie, where we revolutionize accounting practice management with our
                comprehensive online solution. As industry veterans with 15 years of experience running our own
                accounting practices, we understand the limitations of existing software in the market. That's why we
                created Bubble.ie an all-in-one platform that integrates a powerful task management system with
                dedicated modules designed specifically for accounting practices.</p>
              <p>What sets Bubble.ie apart is our task-dependent modules. From VAT Return and RCT Management to Payroll,
                CRO Return, Anti Money Laundering, Year End Accounts, and Tax Return Management, our solution covers it
                all. No longer will you need to juggle multiple software applications. Our platform brings together
                these essential components under one suite, simplifying your workflow and saving you time and effort.
              </p>
              <p>But that's not all. Bubble.ie also offers robust internal tools to enhance your practice’s efficiency.
                Our Time and Task management feature ensures seamless coordination and communication within your team.
                In-house accounting tools streamline the management of purchases, sales, bank accounts, and billing,
                making sure nothing falls through the cracks.</p>
              <p>Usability is at the heart of our platform. Bubble.ie is designed to be user-friendly, even for those
                without extensive technical expertise. We believe that technology should empower you, not overwhelm you.
                With our intuitive interface and automated tasks, you'll find yourself accomplishing more with ease.Rest
                assured that Bubble.ie is built by accounting practice management professionals, for professionals. We
                understand the unique challenges you face, and we're committed to providing a scalable solution that
                adapts to practices of all sizes. Whether you're a small team or a larger practice, Bubble.ie has you
                covered.</p>
              <p>We take the security and confidentiality of your data seriously. Bubble.ie ensures your information is
                stored on a dedicated server with 32-bit encryption. Trust in our robust infrastructure to safeguard
                your practice's sensitive data.</p>
              <p>Join the growing number of accounting practices that have embraced Bubble.ie. Streamline your tasks,
                optimize client management, and simplify your practice management workflow. Experience the future of
                accounting practice management today.</p>
            </div>

          </div>
          <div class="col-lg-4 col-md-12 col-sm-12">
            <div class="rtimg">
              <img class="d-sm-none d-lg-block img-fluid" src="<?php echo URL::to('public/staticpages/img/About-01.png'); ?>" alt="about">
            </div>
          </div>
        </div>
      </div>

    </div>

  </div>
@stop