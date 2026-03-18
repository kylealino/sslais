<!DOCTYPE html>
<html lang="en" dir="ltr" data-bs-theme="light" data-color-theme="Blue_Theme" data-layout="vertical">

<head>
  <!-- Required meta tags -->
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="<?=base_url('assets/js/theme/app.init.js')?>"></script>
  <!-- Favicon icon-->
  <link rel="shortcut icon" type="image/png" href="<?=base_url('assets/images/logos/fnrilogo.png')?>" />

  <!-- Core Css -->
  <link rel="stylesheet" href="<?=base_url('assets/css/styles.css')?>" />

  <title>FODS</title>
</head>

<body>
  <!-- Preloader -->
  <div class="preloader">
    <img src="<?=base_url('assets/images/logos/preloader.svg')?>" alt="loader" class="lds-ripple img-fluid" />
  </div>
  <div id="main-wrapper" class="auth-customizer-none">
    <div class="position-relative overflow-hidden radial-gradient min-vh-100 w-100">
      <div class="position-relative z-index-5">
        <div class="row">
          <div class="col-xl-7 col-xxl-8">
            <a href="" class="text-nowrap logo-img d-block px-4 py-9 w-100">
              <img src="<?=base_url('assets/images/logos/fnrilogo.png')?>" class="dark-logo" alt="Logo-Dark" style="width: 40px; height: auto;" />
              <img src="<?=base_url('assets/images/logos/fnrilogo.png')?>" class="light-logo" alt="Logo-light" style="width: 40px; height: auto;" />
            </a>
            <div class="d-none d-xl-flex align-items-center justify-content-center h-n80">
              <img src="<?=base_url('assets/images/backgrounds/login-security.svg')?>" alt="flexy-img" class="img-fluid" width="500">
            </div>
          </div>
          <div class="col-xl-5 col-xxl-4">
            <div class="authentication-login min-vh-100 bg-body row justify-content-center align-items-center p-4">
              <div class="auth-max-width col-sm-8 col-md-6 col-xl-7 px-4">
                
                <a href="" class="text-nowrap logo-img d-block px-4 py-9 w-100 text-center">
                  <img src="<?=base_url('assets/images/logos/fnrilogo.png')?>" class="dark-logo" alt="Logo-Dark" style="width: 70px; height: auto;" />
                  <img src="<?=base_url('assets/images/logos/fnrilogo.png')?>" class="light-logo" alt="Logo-light" style="width: 70px; height: auto;" />
                </a>
                <h2 class="mb-1 fs-10 fw-bolder text-center text-info">F O D S</h2>
                <p class="mb-7 text-center fw-bolder">Financial Obligation and Disbursement System</p>

                <form action="<?=site_url();?>mylogin-auth" method="post" novalidate>
                  <div class="mb-3">
                    <label for="MyUsername" class="form-label">Username</label>
                    <input type="text" class="form-control"  name="MyUsername" id="MyUsername">
                  </div>
                  <div class="mb-4">
                    <label for="MyPassword" class="form-label">Password</label>
                    <input type="password" class="form-control"  name="MyPassword" id="MyPassword">
                  </div>
                  <div class="d-flex align-items-center justify-content-between mb-4">
                    <div class="form-check">
                      <input class="form-check-input primary" type="checkbox" value="" id="flexCheckChecked" checked>
                      <label class="form-check-label text-dark fs-3" for="flexCheckChecked">
                        Remember this Device
                      </label>
                    </div>
                    <a class="text-primary fw-medium fs-3" href="">Forgot Password?</a>
                  </div>
                  <!-- <a href="../main/index.html" class="btn btn-primary w-100 py-8 mb-4 rounded-2">Sign In</a> -->
                  <button type="submit" class="btn btn-primary w-100 mb-4 rounded-2">Sign In</button>
                  <?php
                    $cmesslogin = session()->getFlashdata('mesyszicas_memsg_login');
                    if(!empty($cmesslogin)):
                      echo "<div class=\"col-12 d-flex align-items-center justify-content-center mb-3\">
                        <div style=\"color: #B81111\">{$cmesslogin}!</div>
                      </div>";
                    endif;
                  ?>    
                  <div class="d-flex align-items-center justify-content-center">
                    <p class="fs-4 mb-0 fw-medium">New to FODS?</p>
                    <a class="text-primary fw-medium ms-2" href="">Contact the administrator</a>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script>
  function handleColorTheme(e) {
    document.documentElement.setAttribute("data-color-theme", e);
  }
</script>
    <button class="btn btn-primary p-3 rounded-circle d-flex align-items-center justify-content-center customizer-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
      <i class="icon ti ti-settings fs-7"></i>
    </button>

  </div>

  <div class="dark-transparent sidebartoggler"></div>
  <!-- Import Js Files -->
  <script src="<?=base_url('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js')?>"></script>
  <script src="<?=base_url('assets/libs/simplebar/dist/simplebar.min.js')?>"></script>
  <script src="<?=base_url('assets/js/theme/app.init.js')?>"></script>
  <script src="<?=base_url('assets/js/theme/theme.js')?>"></script>
  <script src="<?=base_url('assets/js/theme/app.min.js')?>"></script>

  <!-- solar icons -->
  <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
</body>

</html>