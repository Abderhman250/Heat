<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | Dashboard</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('admin/plugins/fontawesome-free/css/all.min.css')}}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="{{ asset('admin/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
  <!-- iCheck -->
  <link rel="stylesheet" href="{{ asset('admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
  <!-- JQVMap -->
  <link rel="stylesheet" href="{{ asset('admin/plugins/jqvmap/jqvmap.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('admin/dist/css/adminlte.min.css')}}">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="{{ asset('admin/plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="{{ asset('admin/plugins/daterangepicker/daterangepicker.css')}}">
  <!-- summernote -->
  <link rel="stylesheet" href="{{ asset('admin/plugins/summernote/summernote-bs4.min.css')}}">

  <!-- daterange picker -->


  <!-- Bootstrap Color Picker -->
  <link rel="stylesheet" href="{{ asset('admin/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css')}}">
  <!-- Tempusdominus Bootstrap 4 -->
  <!-- Select2 -->
  <link rel="stylesheet" href="{{ asset('admin/plugins/select2/css/select2.min.css')}}">
  <link rel="stylesheet" href="{{ asset('admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
  <!-- Bootstrap4 Duallistbox -->
  <link rel="stylesheet" href="{{ asset('admin/plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css')}}">
  <link rel="stylesheet" href="{{ asset('admin/plugins/bs-stepper/css/bs-stepper.min.css')}}">

  <style>
    /* Global Fix for select2 */
    .select2-container .select2-selection--single {
      height: 38px !important;
      /* Match Bootstrap input height */
      line-height: 38px !important;
      /* Center text vertically */
      border: 1px solid #ced4da !important;
      /* Match Bootstrap border */
      border-radius: 4px !important;
      /* Rounded corners */
      padding-left: 10px !important;
      /* Consistent padding */
      font-size: 14px !important;
      /* Match Bootstrap font size */
      background-color: #ffffff;
      /* White background */
    }

    /* Align text in the rendered select2 box */
    .select2-container--default .select2-selection--single .select2-selection__rendered {
      line-height: 38px !important;
      padding-left: 10px !important;
      color: #495057;
      /* Match Bootstrap text color */
    }

    /* Fix the dropdown arrow */
    .select2-container--default .select2-selection--single .select2-selection__arrow {
      height: 38px !important;
      top: 1px !important;
      /* Align the arrow vertically */
      right: 8px !important;
    }

    /* Fix the dropdown options */
    .select2-container--default .select2-results__option {
      font-size: 14px;
      /* Match Bootstrap input font size */
      padding: 8px 12px;
      /* Spacing for dropdown options */
    }

    /* Highlight selected option */
    .select2-container--default .select2-results__option--highlighted[aria-selected] {
      background-color: #007bff !important;
      color: #ffffff !important;
    }

    /* Fix multi-select select2 box */
    .select2-container--default .select2-selection--multiple {
      min-height: 38px !important;
      border: 1px solid #ced4da !important;
      border-radius: 4px !important;
      padding: 4px 6px !important;
      font-size: 14px !important;
      background-color: #ffffff;
    }

    /* Fullscreen loader container */
    #logo-container {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: #ffffff;
      /* Background color */
      display: flex;
      justify-content: center;
      align-items: center;
      z-index: 9999;
      overflow: hidden;
      transition: opacity 2s ease-out;
      /* Smooth hide effect */
    }

    /* Logo styling */
    #logo-container img {
      width: 400px;
      /* Larger logo size */
      height: auto;
      animation: slide-fade 8s ease-in-out forwards;
      /* Extended to 8 seconds */
    }

    /* Left-to-Right Slide and Fade Animation */
    @keyframes slide-fade {
      0% {
        transform: translateX(-100%);
        /* Start from the left */
        opacity: 0;
      }

      25% {
        opacity: 1;
        /* Fully visible at the quarter mark */
      }

      50% {
        transform: translateX(0);
        /* Enter center */
        opacity: 1;
      }

      100% {
        transform: translateX(100%);
        /* Exit to the right */
        opacity: 0;
      }
    }

    /* Hide loader after animation */
    body.loaded #logo-container {
      opacity: 0;
      visibility: hidden;
    }


    .upload-container {
      cursor: pointer;
      transition: border-color 0.3s ease-in-out;
    }

    .upload-container:hover {
      border-color: #0056b3;
    }

    .cursor-pointer {
      cursor: pointer;
    }
  </style>

  @yield('links')
</head>

<body class="hold-transition sidebar-mini">
  <div id="logo-container">
    <img src="{{asset('admin/dist/img/heat2.png')}}" alt="Professional Logo">
  </div>

  @yield('content')
</body>
<script>
  window.addEventListener('load', () => {
    document.body.classList.add('loaded');
  });
</script>
<!-- jQuery -->
<script src="{{ asset('admin/plugins/jquery/jquery.min.js')}}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{ asset('admin/plugins/jquery-ui/jquery-ui.min.js')}}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="{{ asset('admin/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- ChartJS -->
<script src="{{ asset('admin/plugins/chart.js/Chart.min.js')}}"></script>

<script src="{{ asset('admin/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>


<script src="{{ asset('admin/plugins/sparklines/sparkline.js')}}"></script>
<!-- JQVMap -->
<script src="{{ asset('admin/plugins/jqvmap/jquery.vmap.min.js')}}"></script>
<script src="{{ asset('admin/plugins/jqvmap/maps/jquery.vmap.usa.js')}}"></script>
<!-- jQuery Knob Chart -->
<script src="{{ asset('admin/plugins/jquery-knob/jquery.knob.min.js')}}"></script>
<!-- daterangepicker -->
<script src="{{ asset('admin/plugins/moment/moment.min.js')}}"></script>
<script src="{{ asset('admin/plugins/daterangepicker/daterangepicker.js')}}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{ asset('admin/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>
<!-- Summernote -->
<script src="{{ asset('admin/plugins/summernote/summernote-bs4.min.js')}}"></script>
<!-- overlayScrollbars -->
<script src="{{ asset('admin/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('admin/dist/js/adminlte.js')}}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{ asset('admin/dist/js/demo.js')}}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<!-- <script src=" asset('admin/dist/js/pages/dashboard.js') "></script> -->
<script src="{{ asset('/admin/dist/js/pages/dashboard3.js')}}"></script>

<script src="{{ asset('admin/plugins/bootstrap-switch/js/bootstrap-switch.min.js')}}"></script>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@yield('scripts')

</body>

</html>