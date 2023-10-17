<nav class="navbar navbar-expand-xl">
  <div class="container-fluid">

    <a class="navbar-brand me-auto" href="<?php echo URL::to('/'); ?>">
          <img src="<?php echo URL::to('public/staticpages/img/bubble-color-logo.png'); ?>" width="90" class="" alt="bubble logo" />
        </a>
    <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse"
      data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
      aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mb-2 mb-lg-0 ms-auto">
            <a class="logo_small" href="<?php echo URL::to('/'); ?>"><img src="<?php echo URL::to('public/staticpages/img/bubble-color-logo.png'); ?>" alt="bubble logo" /></a>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo URL::to('about'); ?>">About</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo URL::to('modules'); ?>">Modules</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo URL::to('access-payroll'); ?>">Access Payroll</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo URL::to('bubble-books'); ?>">Bubble Books</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo URL::to('contact'); ?>">Contact</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo URL::to('demo'); ?>">Demo</a>
            </li>
          </ul>
    </div>
    <div class="d-flex signup">
      <li class="nav-item">
        <a class="nav-link btn login" href="login.html">Login</a>
      </li>
      <li class="nav-item">
        <a class="nav-link btn" href="register.html">Register</a>
      </li>
    </div>
</nav>