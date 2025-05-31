@extends('admin.layouts.app')

@section('links')
<link rel="stylesheet" href="{{ asset('admin/plugins/fontawesome-free/css/all.min.css') }}">
<link rel="stylesheet" href="{{ asset('admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('admin/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
<style>
  .btn-create {
    padding: 10px 20px;
    font-size: 14px;
    font-weight: bold;
    border-radius: 5px;
    transition: all 0.3s ease;
  }

  .btn-create:hover {
    background-color: #004085;
    color: #fff;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
  }

  .card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
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
            <h1 class="m-0">Appointments Management</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item active">Appointments</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="card col-12">
          <div class="card-header d-flex align-items-center">
  <h3 class="card-title">Appointments List</h3>
  <a href="{{ route('admin.appointments.create') }}" class="btn btn-primary btn-create ml-auto">
    <i class="fas fa-calendar-plus"></i> Create Appointment
  </a>
</div>


            <div class="card-body">
              <table id="userTable" class="table table-bordered table-striped table-hover">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Appointment Name</th>
                    <th>Class</th>
                    <th>Coach</th>
                    <th>Seat Selection</th>
                    <!-- <th>Min Participants</th>
                    <th>Max Participants</th> -->
                    <th>Start Time</th>
                    <th>Duration</th>
                    <th>Location</th>
 
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- Dynamic rows will populate here -->
                </tbody>
                <tfoot>
                  <tr>
                    <th>#</th>
                    <th>Appointment Name</th>
                    <th>Class</th>
                    <th>Coach</th>
                    <th>Seat Selection</th>
                    <!-- <th>Min Participants</th>
                    <th>Max Participants</th> -->
                    <th>Start Time</th>
                    <th>Duration</th>
                    <th>Location</th>
 
                    <th>Actions</th>
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
    $('#userTable').DataTable({
      processing: true,
      serverSide: true,
      ajax: "{{ route('admin.appointments.index') }}",
      columns: [
        { data: 'id', name: 'id' },
        { data: 'appointment_name', name: 'appointment_name' },
        { data: 'class', name: 'class' },
        { data: 'coach', name: 'coach' },
        { data: 'seat_selection', name: 'seat_selection' },
        // { data: 'min_participants', name: 'min_participants' },
        // { data: 'max_participants', name: 'max_participants' },
        { data: 'start_time', name: 'start_time' },
        { data: 'duration' ,  name:"duration"},
        // { data: 'end_time', name: 'end_time' },
        { data: 'location', name: 'location' },
        // { data: 'created_at', name: 'created_at' },
        // { data: 'updated_at', name: 'updated_at' },
        {
          data: 'action',
          name: 'action',
          orderable: false,
          searchable: false
        }
      ],
      paging: true,
      lengthChange: true,
      searching: true,
      ordering: true,
      info: true,
      autoWidth: false,
      responsive: true,
      buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#userTable_wrapper .col-md-6:eq(0)');
  });
</script>
@endsection
