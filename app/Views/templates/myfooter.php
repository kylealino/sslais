
<!-- End footer -->

</div> <!-- Close body-wrapper -->
</div> <!-- Close page-wrapper -->
</div> <!-- Close main-wrapper -->
<!-- Toastr CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<!-- Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<!-- Scripts -->
<script src="<?=base_url('assets/js/vendor.min.js')?>"></script>
<script src="<?=base_url('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js')?>"></script>
<script src="<?=base_url('assets/libs/simplebar/dist/simplebar.min.js')?>"></script>
<script src="<?=base_url('assets/js/theme/app.init.js')?>"></script>
<script src="<?=base_url('assets/js/theme/theme.js')?>"></script>
<script src="<?=base_url('assets/js/theme/app.min.js')?>"></script>
<script src="<?=base_url('assets/js/theme/sidebarmenu.js')?>"></script>
<script src="<?=base_url('assets/libs/owl.carousel/dist/owl.carousel.min.js')?>"></script>
<script src="<?=base_url('assets/js/plugins/toastr-init.js')?>"></script>

<!-- jQuery & DataTables -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>


<script>
document.addEventListener('DOMContentLoaded', function() {
  const sidebar = document.getElementById('sidebar');
  const pageWrapper = document.getElementById('pageWrapper');
  const overlay = document.getElementById('sidebarOverlay');
  const mobileToggle = document.getElementById('mobileMenuToggle');
  const closeSidebar = document.getElementById('closeSidebar');
  const sidebarToggle = document.getElementById('sidebarToggle');
  const logoutBtn = document.getElementById('logoutBtn');
  const logoutForm = document.getElementById('logoutForm');
  const currentUrl = window.location.href;
    document.querySelectorAll('.sidebar-item a').forEach(link => {
      if (link.href === currentUrl) {
        link.parentElement.classList.add('active');
      }
    });
  // Desktop sidebar toggle (collapse/expand)
  if (sidebarToggle) {
    sidebarToggle.addEventListener('click', function() {
      sidebar.classList.toggle('collapsed');
      pageWrapper.classList.toggle('expanded');
    });
  }

  // Mobile sidebar open
  if (mobileToggle) {
    mobileToggle.addEventListener('click', function() {
      sidebar.classList.add('open');
      if (overlay) overlay.classList.add('active');
    });
  }

  // Mobile sidebar close
  function closeSidebarMenu() {
    sidebar.classList.remove('open');
    if (overlay) overlay.classList.remove('active');
  }

  if (closeSidebar) {
    closeSidebar.addEventListener('click', closeSidebarMenu);
  }

  if (overlay) {
    overlay.addEventListener('click', closeSidebarMenu);
  }

  // Logout confirmation
  if (logoutBtn) {
    logoutBtn.addEventListener('click', function(e) {
      e.preventDefault();
      if (confirm('Are you sure you want to logout?')) {
        if (logoutForm) {
          logoutForm.submit();
        }
      }
    });
  }
});
</script>
</body>
</html>