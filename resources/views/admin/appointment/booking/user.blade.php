@extends('admin.layouts.app')

@section('links')
    {{-- Styles for DataTables and FontAwesome --}}
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
        {{-- Page Header --}}
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Bookings</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">Bookings</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Content --}}
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header d-flex align-items-center">
                                <h3 class="card-title">Bookings List</h3>
                            </div>
                            <div class="card">
                            <div class="card-header d-flex align-items-center">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="class">Class</label>
                                        <input type="text" name="class" id="class" class="form-control" placeholder="Class name ">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for=" ">Appointment Date</label>
                                        <input type="date" name="date" id="date" class="form-control" placeholder="MM/DD/YYYY">
                                    </div>
                                </div>
                            </div>
                            {{-- Table Content --}}
                            <div class="card-body">
    <table id="bookingsTable" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Appointment</th>
                <th>Class</th>
                <th>Seat</th>
                <th>Appointment Date </th>
                <th>Booking Date </th>

                <!-- <th>Status</th> -->
            </tr>
        </thead>
        <tbody></tbody>
        <tfoot>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Appointment</th>
                <th>Class</th>
                <th>Seat</th>
                <th>Appointment Date</th>
                <th>Booking Date </th>
               
                <!-- <th>Status</th> -->
            </tr>
        </tfoot>
    </table>
</div>
                            {{-- End Table Content --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    {{-- DataTables Scripts --}}
    <script src="{{ asset('admin/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('admin/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>

    <script>
$(document).ready(function () {
    // Initialize the date picker if needed
 
    // Initialize DataTable with filters
    var table = $('#bookingsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('admin.booking.index') }}",  // The URL for fetching data
            data: function (d) {
                // Include additional filters in the AJAX request
                d.class = $('#class').val();  // Get class filter value
                d.date = $('#date').val();    // Get date filter value
            }
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'username', name: 'username' },
            { data: 'appointment', name: 'appointment' },
            { data: 'class', name: 'class' },
            { data: 'seat', name: 'seat' },
            { data: 'start_time', name: 'start_time' },
            { data: 'created_at', name: 'created_at' },
            // { data: 'status', name: 'status' }
            
        ],
        responsive: true,
        lengthChange: true,
        searching: true,
        ordering: true,
        autoWidth: false,
        buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#bookingsTable_wrapper .col-md-6:eq(0)');

    // When the filter inputs change, redraw the table to reflect new data
    $('#class').on('keyup', function () {
        console.log('test');
        $('#bookingsTable').DataTable().ajax.reload();
    });
    
    $('#date').on('change', function () {
        console.log('test');
        $('#bookingsTable').DataTable().ajax.reload();
    });
});


    </script>
@endsection
