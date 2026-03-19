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
  
  <!-- Additional custom styles for navy blue and yellow theme -->
  <style>
    :root {
      --navy-dark: #0a1a3a;
      --navy-medium: #1a2e5a;
      --navy-light: #2a3e6a;
      --gold-primary: #f5b342;
      --gold-light: #f5c542;
      --gold-soft: #ffd966;
    }
    
    body {
      margin: 0;
      padding: 0;
      overflow: hidden;
      background: var(--navy-dark);
    }
    
    #main-wrapper {
      position: relative;
      width: 100vw;
      height: 100vh;
      overflow: hidden;
    }
    
    /* Canvas for interactive background */
    #interactive-bg {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: 0;
    }
    
    .gradient-overlay {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: radial-gradient(circle at 30% 40%, rgba(245, 180, 66, 0.12) 0%, rgba(10, 26, 58, 0.85) 70%);
      z-index: 1;
      pointer-events: none;
    }
    
    .content-wrapper {
      position: relative;
      z-index: 2;
      width: 100%;
      height: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      pointer-events: none;
    }
    
    .auth-card {
      pointer-events: auto;
      animation: cardAppear 0.6s ease-out;
    }
    
    @keyframes cardAppear {
      0% { opacity: 0; transform: translateY(20px); }
      100% { opacity: 1; transform: translateY(0); }
    }
    
    .auth-card .card {
      border: none;
      border-radius: 24px;
      overflow: hidden;
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
      backdrop-filter: blur(10px);
      background: rgba(255, 255, 255, 0.98);
      transform: translateY(0);
      transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
      border: 1px solid rgba(245, 180, 66, 0.2);
    }
    
    .auth-card .card:hover {
      transform: translateY(-8px) scale(1.02);
      box-shadow: 0 35px 60px -15px rgba(245, 180, 66, 0.3);
      border-color: rgba(245, 180, 66, 0.4);
    }
    
    .auth-card .card-body {
      padding: 2.5rem 2.5rem;
    }
    
    .logo-wrapper {
      text-align: center;
      margin-bottom: 1.5rem;
      position: relative;
    }
    
    .logo-wrapper img {
      width: 90px;
      height: auto;
      border-radius: 50%;
      padding: 8px;
      background: linear-gradient(135deg, var(--navy-dark), var(--navy-medium));
      box-shadow: 0 10px 25px rgba(10, 26, 58, 0.4);
      animation: float 4s ease-in-out infinite;
      border: 2px solid var(--gold-primary);
    }
    
    @keyframes float {
      0%, 100% { transform: translateY(0) rotate(0deg); }
      50% { transform: translateY(-8px) rotate(2deg); }
    }
    
    .title-wrapper {
      text-align: center;
      margin-bottom: 2.5rem;
    }
    
    .title-wrapper h2 {
      color: var(--navy-dark);
      font-size: 2.2rem;
      font-weight: 800;
      letter-spacing: 1px;
      margin-bottom: 0.5rem;
      position: relative;
      display: inline-block;
      text-shadow: 0 2px 4px rgba(245, 180, 66, 0.1);
    }
    
    .title-wrapper h2:after {
      content: '';
      position: absolute;
      bottom: -10px;
      left: 15%;
      width: 70%;
      height: 3px;
      background: linear-gradient(90deg, transparent, var(--gold-primary), var(--gold-light), var(--gold-primary), transparent);
      border-radius: 100px;
    }
    
    .title-wrapper p {
      color: var(--navy-light);
      font-size: 0.9rem;
      font-weight: 500;
      margin-bottom: 0;
      letter-spacing: 0.3px;
    }
    
    .form-label {
      color: var(--navy-dark);
      font-weight: 600;
      font-size: 0.9rem;
      margin-bottom: 0.5rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }
    
    .form-label i {
      color: var(--gold-primary);
      font-size: 1rem;
    }
    
    .input-group-custom {
      position: relative;
      margin-bottom: 1.5rem;
    }
    
    .form-control {
      border: 2px solid #edf2f7;
      border-radius: 16px;
      padding: 0.85rem 1.2rem;
      font-size: 0.95rem;
      transition: all 0.3s ease;
      background: #fafbfc;
    }
    
    .form-control:focus {
      border-color: var(--gold-primary);
      box-shadow: 0 0 0 4px rgba(245, 180, 66, 0.15);
      transform: scale(1.02);
      background: white;
    }
    
    .form-control::placeholder {
      color: #a0aec0;
      font-size: 0.9rem;
    }
    
    .form-check-input {
      border-color: var(--navy-light);
      width: 1.1rem;
      height: 1.1rem;
      cursor: pointer;
    }
    
    .form-check-input:checked {
      background-color: var(--navy-dark);
      border-color: var(--navy-dark);
    }
    
    .form-check-label {
      color: #4a5568;
      font-size: 0.9rem;
      cursor: pointer;
    }
    
    .forgot-link {
      color: var(--navy-dark) !important;
      font-size: 0.9rem;
      font-weight: 500;
      text-decoration: none;
      transition: all 0.3s ease;
      position: relative;
    }
    
    .forgot-link:after {
      content: '';
      position: absolute;
      bottom: -2px;
      left: 0;
      width: 0;
      height: 2px;
      background: var(--gold-primary);
      transition: width 0.3s ease;
    }
    
    .forgot-link:hover {
      color: var(--gold-primary) !important;
    }
    
    .forgot-link:hover:after {
      width: 100%;
    }
    
    .btn-signin {
      background: linear-gradient(135deg, var(--navy-dark), var(--navy-medium));
      color: white;
      border: none;
      border-radius: 16px;
      padding: 0.9rem;
      font-weight: 600;
      font-size: 1rem;
      letter-spacing: 0.5px;
      transition: all 0.4s ease;
      position: relative;
      overflow: hidden;
      cursor: pointer;
      box-shadow: 0 10px 20px -8px rgba(10, 26, 58, 0.5);
    }
    
    .btn-signin:before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
      transition: left 0.7s ease;
    }
    
    .btn-signin:hover {
      background: linear-gradient(135deg, var(--navy-medium), var(--navy-dark));
      transform: translateY(-3px);
      box-shadow: 0 15px 30px -8px var(--gold-primary);
    }
    
    .btn-signin:hover:before {
      left: 100%;
    }
    
    .btn-signin:active {
      transform: translateY(0);
    }
    
    .error-message {
      color: #dc3545;
      font-size: 0.9rem;
      text-align: center;
      margin-top: 1.5rem;
      padding: 0.75rem 1rem;
      background: rgba(220, 53, 69, 0.08);
      border-radius: 12px;
      border-left: 4px solid #dc3545;
      animation: shake 0.5s ease;
    }
    
    @keyframes shake {
      0%, 100% { transform: translateX(0); }
      20%, 60% { transform: translateX(-5px); }
      40%, 80% { transform: translateX(5px); }
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
      .auth-card .card-body {
        padding: 2rem 1.5rem;
      }
      
      .title-wrapper h2 {
        font-size: 1.9rem;
      }
    }
  </style>

  <title>S S L A I S</title>
</head>

<body>
  <!-- Preloader -->
  <div class="preloader">
    <img src="<?=base_url('assets/images/logos/preloader.svg')?>" alt="loader" class="lds-ripple img-fluid" />
  </div>
  
  <div id="main-wrapper">
    <!-- Interactive Canvas Background -->
    <canvas id="interactive-bg"></canvas>
    
    <!-- Gradient Overlay -->
    <div class="gradient-overlay"></div>
    
    <!-- Content -->
    <div class="content-wrapper">
      <div class="row justify-content-center w-100">
        <div class="col-md-8 col-lg-6 col-xxl-3 auth-card">
          <div class="card mb-0">
            <div class="card-body">
              
              <!-- Logo and Title Section -->
              <div class="logo-wrapper">
                <img src="<?=base_url('assets/images/logos/sslai.png?v=1')?>" alt="SSLAIS Logo" />
              </div>
              
              <div class="title-wrapper">
                <h2>SSLAIS</h2>
                <p>Science Savings and Loan Association<br />Information System</p>
              </div>
              
              <!-- Login Form -->
              <form action="<?=site_url();?>mylogin-auth" method="post" novalidate>
                <div class="mb-4">
                  <label for="MyUsername" class="form-label">
                    <i class="bi bi-person-badge"></i> Username
                  </label>
                  <input type="text" class="form-control" name="MyUsername" id="MyUsername" placeholder="Enter your username" required>
                </div>
                
                <div class="mb-4">
                  <label for="MyPassword" class="form-label">
                    <i class="bi bi-shield-lock"></i> Password
                  </label>
                  <input type="password" class="form-control" name="MyPassword" id="MyPassword" placeholder="Enter your password" required>
                </div>
                
                <div class="d-flex align-items-center justify-content-between mb-4">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked" checked>
                    <label class="form-check-label" for="flexCheckChecked">
                      Remember this Device
                    </label>
                  </div>
                  <a class="forgot-link" href="">Forgot Password?</a>
                </div>
                
                <button type="submit" class="btn-signin w-100 mb-4">
                  Sign In
                </button>
                
                <?php
                  $cmesslogin = session()->getFlashdata('mesyszicas_memsg_login');
                  if(!empty($cmesslogin)):
                    echo "<div class=\"error-message\">
                      <i class=\"bi bi-exclamation-triangle-fill me-2\"></i>
                      {$cmesslogin}!
                    </div>";
                  endif;
                ?>
              </form>
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
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  
  <!-- Interactive Background Script - Improved Balance -->
  <script>
    (function() {
      const canvas = document.getElementById('interactive-bg');
      const ctx = canvas.getContext('2d');
      
      let mouseX = 0, mouseY = 0;
      let mouseInside = false;
      
      // Set canvas size
      function resizeCanvas() {
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
      }
      
      // Track mouse movement
      document.addEventListener('mousemove', (e) => {
        mouseX = e.clientX;
        mouseY = e.clientY;
        mouseInside = true;
      });
      
      document.addEventListener('mouseleave', () => {
        mouseInside = false;
      });
      
      // Financial Dashboard Elements
      class FinancialElement {
        constructor() {
          this.x = Math.random() * canvas.width;
          this.y = Math.random() * canvas.height;
          this.originalX = this.x;
          this.originalY = this.y;
          this.size = Math.random() * 30 + 15;
          this.opacity = Math.random() * 0.1 + 0.05;
          this.phase = Math.random() * Math.PI * 2;
        }
      }
      
      // Peso Coins (More elegant)
      class PesoCoin extends FinancialElement {
        constructor() {
          super();
          this.size = Math.random() * 25 + 12;
          this.rotation = Math.random() * Math.PI * 2;
          this.rotationSpeed = (Math.random() - 0.5) * 0.01;
        }
        
        update() {
          // Subtle floating motion
          this.x = this.originalX + Math.sin(Date.now() * 0.001 + this.phase) * 20;
          this.y = this.originalY + Math.cos(Date.now() * 0.001 + this.phase) * 10;
          this.rotation += this.rotationSpeed;
          
          // Mouse interaction - gentle push
          if (mouseInside) {
            const dx = mouseX - this.x;
            const dy = mouseY - this.y;
            const distance = Math.sqrt(dx * dx + dy * dy);
            
            if (distance < 200) {
              const angle = Math.atan2(dy, dx);
              const force = (200 - distance) / 200 * 3;
              this.x -= Math.cos(angle) * force;
              this.y -= Math.sin(angle) * force;
            }
          }
        }
        
        draw() {
          ctx.save();
          ctx.translate(this.x, this.y);
          ctx.rotate(this.rotation);
          
          // Draw coin
          ctx.beginPath();
          ctx.arc(0, 0, this.size, 0, Math.PI * 2);
          
          // Gradient for coin
          const gradient = ctx.createRadialGradient(-5, -5, 2, 0, 0, this.size);
          gradient.addColorStop(0, `rgba(255, 215, 0, ${this.opacity * 2})`);
          gradient.addColorStop(0.7, `rgba(245, 180, 66, ${this.opacity})`);
          gradient.addColorStop(1, `rgba(200, 140, 40, ${this.opacity * 0.5})`);
          
          ctx.fillStyle = gradient;
          ctx.fill();
          ctx.strokeStyle = `rgba(245, 180, 66, ${this.opacity * 2})`;
          ctx.lineWidth = 1;
          ctx.stroke();
          
          // Draw ₱ symbol
          ctx.font = `bold ${this.size * 0.6}px 'Inter', Arial`;
          ctx.fillStyle = `rgba(255, 255, 255, ${this.opacity * 1.5})`;
          ctx.textAlign = 'center';
          ctx.textBaseline = 'middle';
          ctx.fillText('₱', 0, 0);
          
          ctx.restore();
        }
      }
      
      // Growth Chart (Upward trend)
      class GrowthChart {
        constructor(x, y) {
          this.x = x;
          this.y = y;
          this.width = 180;
          this.height = 80;
          this.points = [];
          this.originalPoints = [];
          this.opacity = 0.15;
          
          // Create upward trending data
          for (let i = 0; i < 12; i++) {
            const xPos = (i / 11) * this.width;
            const trend = Math.sin(i * 0.8) * 15 + (i * 5); // Upward trend with waves
            const yPos = this.y - 20 - trend;
            this.points.push({x: xPos, y: yPos});
            this.originalPoints.push({x: xPos, y: yPos});
          }
        }
        
        update() {
          if (mouseInside) {
            const dx = mouseX - (this.x + this.width/2);
            const dy = mouseY - (this.y - this.height/2);
            const distance = Math.sqrt(dx * dx + dy * dy);
            
            if (distance < 250) {
              const influence = (250 - distance) / 250;
              
              // Create wave effect on graph
              this.points.forEach((point, i) => {
                const wave = Math.sin(Date.now() * 0.01 + i * 0.5) * influence * 12;
                point.y = this.originalPoints[i].y - wave;
              });
            } else {
              // Return to original
              this.points.forEach((point, i) => {
                point.y += (this.originalPoints[i].y - point.y) * 0.1;
              });
            }
          }
        }
        
        draw() {
          ctx.save();
          ctx.translate(this.x, this.y);
          
          // Draw area under graph
          ctx.beginPath();
          ctx.moveTo(0, 0);
          this.points.forEach(point => {
            ctx.lineTo(point.x, point.y - this.y);
          });
          ctx.lineTo(this.width, 0);
          ctx.closePath();
          ctx.fillStyle = `rgba(245, 180, 66, ${this.opacity * 0.5})`;
          ctx.fill();
          
          // Draw line
          ctx.beginPath();
          ctx.strokeStyle = `rgba(245, 180, 66, ${this.opacity * 1.5})`;
          ctx.lineWidth = 2;
          
          this.points.forEach((point, i) => {
            if (i === 0) {
              ctx.moveTo(point.x, point.y - this.y);
            } else {
              ctx.lineTo(point.x, point.y - this.y);
            }
          });
          
          ctx.stroke();
          
          // Draw points
          this.points.forEach(point => {
            ctx.beginPath();
            ctx.arc(point.x, point.y - this.y, 3, 0, Math.PI * 2);
            ctx.fillStyle = `rgba(245, 180, 66, ${this.opacity * 2})`;
            ctx.fill();
          });
          
          ctx.restore();
        }
      }
      
      // Loan Bars (Like loan amounts)
      class LoanBar {
        constructor(x, y) {
          this.x = x;
          this.y = y;
          this.width = 45;
          this.height = Math.random() * 100 + 40;
          this.targetHeight = this.height;
          this.currentHeight = this.height;
          this.opacity = Math.random() * 0.1 + 0.1;
        }
        
        update() {
          if (mouseInside) {
            const dx = mouseX - (this.x + this.width/2);
            const dy = mouseY - (this.y - this.height/2);
            const distance = Math.sqrt(dx * dx + dy * dy);
            
            if (distance < 200) {
              const influence = (200 - distance) / 200;
              this.targetHeight = this.height * (1 + influence * 0.3);
            } else {
              this.targetHeight = this.height;
            }
          }
          
          // Smooth transition
          this.currentHeight += (this.targetHeight - this.currentHeight) * 0.1;
        }
        
        draw() {
          // Draw bar
          ctx.fillStyle = `rgba(245, 180, 66, ${this.opacity})`;
          ctx.fillRect(this.x, this.y - this.currentHeight, this.width, this.currentHeight);
          
          // Draw outline
          ctx.strokeStyle = `rgba(245, 180, 66, ${this.opacity * 2})`;
          ctx.lineWidth = 1;
          ctx.strokeRect(this.x, this.y - this.currentHeight, this.width, this.currentHeight);
          
          // Draw value on top
          ctx.font = 'bold 10px Inter';
          ctx.fillStyle = `rgba(245, 180, 66, ${this.opacity * 2})`;
          ctx.textAlign = 'center';
          ctx.fillText('₱' + Math.round(this.currentHeight * 1000), this.x + this.width/2, this.y - this.currentHeight - 5);
        }
      }
      
      // Create balanced composition
      let pesoCoins = [];
      let growthCharts = [];
      let loanBars = [];
      
      function init() {
        pesoCoins = [];
        growthCharts = [];
        loanBars = [];
        
        // Create fewer, better placed coins (15 coins)
        for (let i = 0; i < 15; i++) {
          pesoCoins.push(new PesoCoin());
        }
        
        // Create growth charts (3 charts positioned strategically)
        growthCharts.push(new GrowthChart(canvas.width * 0.15, canvas.height * 0.3));
        growthCharts.push(new GrowthChart(canvas.width * 0.75, canvas.height * 0.6));
        growthCharts.push(new GrowthChart(canvas.width * 0.85, canvas.height * 0.2));
        
        // Create loan bars (12 bars in groups)
        for (let i = 0; i < 4; i++) {
          for (let j = 0; j < 3; j++) {
            loanBars.push(new LoanBar(
              canvas.width * 0.1 + i * 120 + j * 20,
              canvas.height * 0.7 + j * 30
            ));
          }
        }
        
        // Second group
        for (let i = 0; i < 4; i++) {
          for (let j = 0; j < 3; j++) {
            loanBars.push(new LoanBar(
              canvas.width * 0.6 + i * 100 + j * 15,
              canvas.height * 0.2 + j * 25
            ));
          }
        }
      }
      
      // Animation loop
      function animate() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        
        // Draw navy blue background with elegant gradient
        const gradient = ctx.createRadialGradient(
          canvas.width * 0.3, canvas.height * 0.4, 100,
          canvas.width * 0.7, canvas.height * 0.6, canvas.width
        );
        gradient.addColorStop(0, '#0f1f45');
        gradient.addColorStop(0.5, '#1a2e5a');
        gradient.addColorStop(1, '#0a1a3a');
        ctx.fillStyle = gradient;
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        
        // Draw subtle ledger lines
        ctx.strokeStyle = 'rgba(245, 180, 66, 0.03)';
        ctx.lineWidth = 0.5;
        
        for (let i = 0; i < canvas.height; i += 40) {
          ctx.beginPath();
          ctx.moveTo(0, i);
          ctx.lineTo(canvas.width, i);
          ctx.stroke();
        }
        
        for (let i = 0; i < canvas.width; i += 40) {
          ctx.beginPath();
          ctx.moveTo(i, 0);
          ctx.lineTo(i, canvas.height);
          ctx.stroke();
        }
        
        // Draw all elements with balanced layering
        loanBars.forEach(bar => {
          bar.update();
          bar.draw();
        });
        
        growthCharts.forEach(chart => {
          chart.update();
          chart.draw();
        });
        
        pesoCoins.forEach(coin => {
          coin.update();
          coin.draw();
        });
        
        // Draw mouse highlight effect
        if (mouseInside) {
          ctx.beginPath();
          ctx.arc(mouseX, mouseY, 120, 0, Math.PI * 2);
          ctx.strokeStyle = 'rgba(245, 180, 66, 0.15)';
          ctx.lineWidth = 2;
          ctx.stroke();
          
          ctx.beginPath();
          ctx.arc(mouseX, mouseY, 60, 0, Math.PI * 2);
          ctx.strokeStyle = 'rgba(245, 180, 66, 0.25)';
          ctx.stroke();
        }
        
        requestAnimationFrame(animate);
      }
      
      // Handle resize
      window.addEventListener('resize', () => {
        resizeCanvas();
        init();
      });
      
      // Initialize
      resizeCanvas();
      init();
      animate();
      
      // Remove preloader after page load
      window.addEventListener('load', () => {
        document.querySelector('.preloader').style.display = 'none';
      });
    })();
  </script>
</body>

</html>