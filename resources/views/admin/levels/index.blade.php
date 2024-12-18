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

  /* Align button to the right */
  .card-header .btn-create {
    float: right;
  }

  /* Table Styling */
  .table {
    margin-top: 20px;
  }

  /* Card Styling */
  .card {
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
  }

  .card-header {
    background-color: #f4f6f9;
    border-bottom: 2px solid #dcdfe3;
  }
</style>
<style>
  /* Button styling */
  .btn-create {
    font-size: 16px;
    font-weight: bold;
    padding: 10px 20px;
    border-radius: 5px;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
  }

  .btn-create i {
    margin-right: 8px;
  }

  .btn-create:hover {
    background-color: #004085;
    color: #fff;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
  }

  /* Align button to max right */
  .header-actions {
    margin-left: auto;
    /* Pushes the button to the far right */
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
            <h1 class="m-0">Levels</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
              <li class="breadcrumb-item active">Levels</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header d-flex align-items-center">
                <h3 class="card-title">Levels List</h3>
                <div class="header-actions">
                  <a href="{{ route('admin.level.create') }}" class="btn btn-primary btn-create">
                    <i class="fas fa-user-plus"></i> Create Level
                  </a>
                </div>
              </div>
              <div class="card-body">
                <table id="userTable" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Title Levels</th>
                      <th>Required Classes</th>
                      <th>Edit</th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                  <tfoot>
                    <tr>
                      <th>ID</th>
                      <th>Title Levels</th>
                      <th>Required Classes</th>
                      <th>Edit</th>

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
      processing: true,
      serverSide: true,
      ajax: "{{ route('admin.level.index') }}",
      columns: [{
          data: 'id',
          name: 'id'
        },
        {
          data: 'title_levels',
          name: 'title_levels'
        },
        {
          data: 'required_classes',
          name: 'required_classes'
        },
        {
          data: 'edit',
          name: 'edit'
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