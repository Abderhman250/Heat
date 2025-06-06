@extends('admin.layouts.app')

@section('links')
<link rel="stylesheet" href="{{ asset('admin/plugins/fontawesome-free/css/all.min.css') }}">
<link rel="stylesheet" href="{{ asset('admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
<style>
    /* General Styling */
    .form-group label {
        font-weight: bold;
    }

    .form-control {
        border-radius: 5px;
    }

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

    /* Error Message Styling */
    .invalid-feedback {
        display: block;
        color: red;
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
                        <h1 class="m-0">Manage Classes</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">Classes</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <section class="content">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Create Class</h3>
                    </div>
                    <div class="card-body">
                        <!-- Validation Errors -->
                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>Whoops!</strong> There were some problems with your input.
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <!-- Form Start -->
                        <form action="{{ route('admin.classes.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <!-- Class Name -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Class Name</label>
                                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="Enter class name" value="{{ old('name') }}" required>
                                        @error('name')
                                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Room -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="room">Room</label>
                                        <input type="text" name="room" id="room" class="form-control @error('room') is-invalid @enderror" placeholder="Enter room name or number" value="{{ old('room') }}">
                                        @error('room')
                                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Capacity -->
                                <!-- <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="capacity">Capacity</label>
                                        <input type="number" name="capacity" id="capacity" class="form-control @error('capacity') is-invalid @enderror" placeholder="Enter capacity" value="{{ old('capacity') }}" min="0" required>
                                        @error('capacity')
                                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div> -->

                                <!-- Seat Selection -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="seat_selection_required">Seat Selection Required</label>
                                        <select name="seat_selection_required" id="seat_selection_required" class="form-control select2 @error('seat_selection_required') is-invalid @enderror">
                                            <option value="0" {{ old('seat_selection_required') == '0' ? 'selected' : '' }}>No</option>
                                            <option value="1" {{ old('seat_selection_required') == '1' ? 'selected' : '' }}>Yes</option>
                                        </select>
                                        @error('seat_selection_required')
                                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Type Name -->
                                <div class="col-md-6">     
                                    <div class="form-group">
                                        <label for="type_name">Class Type </label>
                                        <select name="type_name" id="type_name" class="form-control select2 @error('type_name') is-invalid @enderror">
                                            <option value="">Select Type</option>
                                            <option value="Spinning room (bikes room)" {{ old('type_name') == 'Spinning room (bikes room)' ? 'selected' : '' }}>Spinning room (bikes room)</option>
                                            <option value="Group strength / endurance class" {{ old('type_name') == 'Group strength / endurance class' ? 'selected' : '' }}>Group strength / endurance class</option>
                                            <option value="Yoga studio" {{ old('type_name') == 'Yoga studio' ? 'selected' : '' }}>Yoga studio</option>
                                         </select>
                                        @error('type_name')
                                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Booking Process -->
 

                                <!-- Description -->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" placeholder="Enter description" rows="4">{{ old('description') }}</textarea>
                                        @error('description')
                                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Photo -->

                                <!-- Photo Upload -->
                                <div class="col-md-6">
                                    <div class="form-group text-center">
                                        <label for="photo" class="font-weight-bold mb-3" style="font-size: 1.1rem;">Upload Your Photo</label>
                                        <div class="upload-container p-4 border rounded" style="border: 2px dashed #007bff; background-color: #f8f9fa;">
                                            <input type="file" name="photo" id="photo" class="form-control-file d-none @error('photo') is-invalid @enderror" onchange="showPreview(event);">
                                            <label for="photo" class="d-block cursor-pointer">
                                                <i class="fas fa-cloud-upload-alt fa-3x text-primary mb-2"></i>
                                                <p class="text-muted">Click to upload or drag and drop your file here</p>
                                            </label>
                                            <img id="photo-preview" src="" alt="Photo Preview" class="img-thumbnail mt-3" style="display: none; max-width: 150px; height: auto; border: 2px solid #ddd;">
                                            @error('photo')
                                            <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="box-footer text-right">
                                <button type="submit" class="btn btn-primary">Create Class</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('#seat_selection_required, #type_name, #booking_process').select2({
            placeholder: "Select an option",
            allowClear: true
        });
    });

    function showPreview(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('photo-preview');
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block'; // Show the preview image
            };
            reader.readAsDataURL(file);
        } else {
            preview.src = '';
            preview.style.display = 'none'; // Hide the preview if no file is selected
        }
    }
</script>
@endsection