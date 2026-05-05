<script src="{{asset('admin/assets/js/core/popper.min.js')}}"></script>
<script src="{{asset('admin/assets/js/core/bootstrap.min.js')}}"></script>
<script src="{{asset('admin/assets/js/plugins/perfect-scrollbar.min.js')}}"></script>
<script src="{{asset('admin/assets/js/plugins/smooth-scrollbar.min.js')}}"></script>
<script src="{{asset('admin/assets/js/plugins/chartjs.min.js')}}"></script>

<script src="{{asset('admin/assets/js/plugins/jquery-3.7.1.js')}}"></script>
<script src="{{asset('admin/assets/js/plugins/dataTables.js')}}"></script>
<script src="{{asset('admin/assets/js/plugins/dataTables.bootstrap5.js')}}"></script>


<script src="{{asset('admin/assets/js/plugins/pdfmake.min.js')}}"></script>
<script src="{{asset('admin/assets/js/plugins/vfs_fonts.js')}}"></script>
<script src="{{asset('admin/assets/js/plugins/jszip.min.js')}}"></script>
<script src="{{asset('admin/assets/js/plugins/buttons.dataTables.js')}}"></script>
<script src="{{asset('admin/assets/js/plugins/dataTables.buttons.js')}}"></script>
<script src="{{asset('admin/assets/js/plugins/buttons.html5.min.js')}}"></script>
<script src="{{asset('admin/assets/js/plugins/buttons.print.min.js')}}"></script>

<script src="{{asset('admin/assets/js/select2/select2.min.js')}}"></script>

<!-- 
  <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" /> -->
<script>
  var win = navigator.platform.indexOf('Win') > -1;
  if (win && document.querySelector('#sidenav-scrollbar')) {
    var options = {
      damping: '0.5'
    }
    Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
  }
</script>
<script async defer src="{{asset('admin/assets/js/buttons.js')}}"></script>
<script src="{{asset('admin/assets/js/material-dashboard.js?v=3.2.0')}}"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('form').forEach(function (form) {
      form.addEventListener('submit', function () {
        // Add loading class to disable interactions
        form.classList.add('loading');

        // Find submit buttons in this form
        form.querySelectorAll('[type="submit"]').forEach(function (btn) {
          btn.disabled = true;

          // Optional: change text and add spinner
          if (!btn.dataset.originalText) {
            btn.dataset.originalText = btn.innerHTML;
          }
          btn.innerHTML = btn.dataset.originalText + ' <span class="spinner"></span>';
        });
      });
    });
  });

  $(document).ready(function () {
    $('.js-select2').select2();
  });
</script>