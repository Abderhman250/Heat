@extends('admin.layouts.app')

 
@section('content')
<div class="container">
    <h1 class="mb-4">Classes</h1>
    <table class="table table-bordered" id="classTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Photo</th>
                <th>Room</th>
                <th>Seat Selection Required</th>
                <th>Capacity</th>
                <th>Created At</th>
                <th>Class Type ID</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        var classTable = $('#classTable').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": "{{ route('admin.classes.index') }}",
            "columns": [
                { data: 'id', name: 'id' },
                { data: 'name', name: 'name' },
                { data: 'description', name: 'description' },
                { data: 'photo', name: 'photo', orderable: false, searchable: false },
                { data: 'room', name: 'room' },
                { data: 'seat_selection_required', name: 'seat_selection_required' },
                { data: 'capacity', name: 'capacity' },
                { data: 'created_at', name: 'created_at' },
                { data: 'class_type_id', name: 'class_type_id' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "dom": 'Bfrtip',
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        });

        classTable.buttons().container().appendTo('#classTable_wrapper .col-md-6:eq(0)');
    });
</script>
@endpush