<!DOCTYPE html>
<html lang="en" dir="ltr" data-bs-theme="light" data-color-theme="Blue_Theme" data-layout="vertical">

<head>
  <!-- Required meta tags -->
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="<?=base_url('assets/js/theme/app.init.js')?>"></script>

  <link rel="shortcut icon" type="image/png" href="<?=base_url('assets/images/logos/sslai.png')?>" />
  <!-- Core Css -->
  <link rel="stylesheet" href="<?=base_url('assets/css/styles.css')?>" />

  <title>S S L A I S</title>
</head>

<body>
  <!-- Preloader -->
  <div class="preloader">
    <img src="<?=base_url('assets/images/logos/preloader.svg')?>" alt="loader" class="lds-ripple img-fluid" />
  </div>
  <div id="main-wrapper" class="auth-customizer-none">
    <div class="position-relative overflow-hidden radial-gradient min-vh-100 w-100 d-flex align-items-center justify-content-center">
      <div class="d-flex align-items-center justify-content-center w-100">
        <div class="row justify-content-center w-100">
          <div class="col-md-8 col-lg-6 col-xxl-3 auth-card">
            <div class="card mb-0">
              <div class="card-body">
                <div class="row">
     
                <a href="" class="text-nowrap logo-img d-block px-4 py-9 w-100 text-center">
                  <img src="<?=base_url('assets/images/logos/sslai.png?v=1')?>" class="dark-logo" alt="Logo-Dark" style="width: 70px; height: auto;" />
                </a>
                  <h2 class="mb-1 fs-10 fw-bolder text-center text-success">SSLAIS</h2>
                  <p class="mb-7 text-center fw-bolder">Science Savings and Loan Association Information System</p>
                </div>
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
                  <button type="submit" class="btn btn-success w-100 mb-4 rounded-2">Sign In</button>
                  <?php
                    $cmesslogin = session()->getFlashdata('mesyszicas_memsg_login');
                    if(!empty($cmesslogin)):
                      echo "<div class=\"col-12 d-flex align-items-center justify-content-center mb-3\">
                        <div style=\"color: #B81111\">{$cmesslogin}!</div>
                      </div>";
                    endif;
                  ?>    
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