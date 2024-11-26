@extends('admin.layouts.app')

@section('links')
<link rel="stylesheet" href="{{ asset('admin/plugins/fontawesome-free/css/all.min.css') }}">
<link rel="stylesheet" href="{{ asset('admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('admin/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
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
                  <h1 class="m-0">Users</h1>
               </div>
               <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                     <li class="breadcrumb-item"><a href="#">Home</a></li>
                     <li class="breadcrumb-item active">Users</li>
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
                     <h3 class="card-title">Users List</h3>
                  </div>
                  <div class="card-body">
                     <table id="userTable" class="table table-bordered table-striped">
                        <thead>
                           <tr>
                              <th>ID</th>
                              <th>Username</th>
                              <th>First Name</th>
                              <th>Last Name</th>
                              <th>Phone</th>
                              <th>Gender</th>
                              <th>Email</th>
                              <th>Date of Birth</th>
                              <th>Photo</th>
                 
                              <th>Action</th>
                           </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <tfoot>
                           <tr>
                              <th>ID</th>
                              <th>Username</th>
                              <th>First Name</th>
                              <th>Last Name</th>
                              <th>Phone</th>
                              <th>Gender</th>
                              <th>Email</th>
                              <th>Date of Birth</th>
                              <th>Photo</th>

           
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
         "ajax": "{{ route('admin.users') }}", // Adjust the route if necessary
         columns: [{
               data: 'id',
               name: 'id'
            },
            {
               data: 'username',
               name: 'username'
            },
            {
               data: 'first_name',
               name: 'first_name'
            },
            {
               data: 'last_name',
               name: 'last_name'
            },
            {
               data: 'phone',
               name: 'phone'
            },
            {
               data: 'gender',
               name: 'gender'
            },
            {
               data: 'email',
               name: 'email'
            },
            {
               data: 'dob',
               name: 'dob'
            },
            {
               data: 'photo',
               name: 'photo',
               orderable: false,
               searchable: false
            },
       
            {
               data: 'action',
               name: 'action',
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
         "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
      }).buttons().container().appendTo('#userTable_wrapper .col-md-6:eq(0)');
   });
</script>
@endsection