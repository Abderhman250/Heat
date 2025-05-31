@extends('admin.layouts.app')

@section('links')
<link rel="stylesheet" href="{{ asset('admin/plugins/fontawesome-free/css/all.min.css') }}">
<link rel="stylesheet" href="{{ asset('admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('admin/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
<style>
    /* Button Styling */
    .btn-primary {
        padding: 10px 20px;
        font-size: 16px;
        font-weight: bold;
        border-radius: 5px;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #004085;
        color: #fff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    /* Align the button to the right */
    .card-header .btn-create {
        float: right;
    }
</style>

@endsection

@section('content')
<div class="wrapper">
  @include('admin.layouts.nav')
  @include('admin.layouts.sidebar')

  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Plans</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Plans</li>
            </ol>
          </div>
        </div>
      </div>
    </div>
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Plans List</h3>
              <a href="{{ route('admin.plans.create') }}" class="btn btn-primary btn-create">
                                    <i class="fas fa-plus-circle"></i> Create Plan
              </a>
            </div>
            <div class="card-body">
              <table id="userTable" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>ID</th>
                    <!-- <th>Photo</th> -->
                    <th>Plan Name</th>
                    <!-- <th>Plan Type</th> -->
                    <th>Plan Header</th>
                    <th>Total Classes</th>
                    <th>Price</th>
                    <th>Class</th>
                    <th>Description</th>
                    <th>Action</th>

                  </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                  <tr>
                    <th>ID</th>
                    <!-- <th>Photo</th> -->
                    <th>Plan Name</th>
                    <!-- <th>Plan Type</th> -->
                    <th>Plan Header</th>
                    <th>Total Classes</th>
                    <th>Price</th>
                    <th>Class</th>
                    <th>Description</th>
                    <th>Action</th>
                    </tr>
                </tfoot>
              </table>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('admin/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('admin/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('admin/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('admin/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('admin/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('admin/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('admin/plugins/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('admin/plugins/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('admin/plugins/pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ asset('admin/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('admin/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('admin/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
<script>
  $(function() {
    var userTable = $('#userTable').DataTable({
      "processing": true,
      "serverSide": true,
      "ajax": "{{ route('admin.plans.index') }}", // Adjust the route if necessary
      columns: [
        { data: 'id', name: 'id' },
        // { data: 'photo', name: 'photo' },
        { data: 'plan_name', name: 'plan_name' },
        // { data: 'type', name: 'type' },
        { data: 'sectionPlan', name: 'sectionPlan' },
        { data: 'total_classes', name: 'total_classes' },
        { data: 'price', name: 'price' },
        { data: 'class', name: 'class' },
        { data: 'description', name: 'description' },
        {
               data: 'active',
               name: 'active',
               orderable: false,
               searchable: false
            }
      ],
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
      "createdRow": function(row, data, dataIndex) {
        $(row).find('td').each(function() {
          $(this).html($(this).html());
        });
      },
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#userTable_wrapper .col-md-6:eq(0)');
  });



  $(document).on('click', '.activate-plan, .deactivate-plan', function() {
    let palnId = $(this).data('id');
    let button = $(this); // Reference to the clicked button
    let action = button.hasClass('activate-plan') ? 'deactivate':'activate' ;
    
    // Change button text and style immediately
    if (action === 'activate') {
        button.text('Activating...').attr('disabled', true).removeClass('btn-success').addClass('btn-secondary');
    } else {
        button.text('Deactivating...').attr('disabled', true).removeClass('btn-danger').addClass('btn-secondary');
    }
 
    $.ajax({
        url: `/admin/plans/${action}`, // Replace with your route URL
        type: 'POST',
        data: {
            id: palnId,
            _token: '{{ csrf_token() }}' // CSRF token for security
        },
        success: function(response) {
         Swal.fire({
                icon: 'success',
                title: 'Success',
                text: response.message,
                timer: 2000, // Auto-close in 2 seconds
                showConfirmButton: false
            });

            // Reload the table
            $('#users-table').DataTable().ajax.reload();
        },
        error: function() {
         Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Something went wrong. Please try again.'
            });

            // Revert button changes on error
            if (action === 'activate') {
                button.text('Activate').attr('disabled', false).removeClass('btn-secondary').addClass('btn-success');
            } else {
                button.text('Deactivate').attr('disabled', false).removeClass('btn-secondary').addClass('btn-danger');
            }
        }
    });
});
</script>
@endsection
