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
                        <h1 class="m-0">Edit Appointment</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">Appointments</li>
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
                        <h3 class="card-title">Edit Appointment</h3>
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
                        <form action="{{ route('admin.appointments.update', $appointment->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <!-- Appointment Name -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="appointment_name">Appointment Name</label>
                                        <input type="text" name="appointment_name" id="appointment_name" class="form-control @error('appointment_name') is-invalid @enderror" placeholder="Enter appointment name" value="{{ old('appointment_name', $appointment->appointment_name) }}">
                                        @error('appointment_name')
                                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Class Selection -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="class_id">Class</label>
                                        <select name="class_id" id="class_id" class="form-control select2 @error('class_id') is-invalid @enderror">
                                            <option value="">Select Class</option>
                                            @foreach($classes as $class)
                                            <option value="{{ $class->id }}" {{ old('class_id', $appointment->class_id) == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('class_id')
                                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Coach Selection -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="coach_id">Coach</label>    
                                        <select name="coach_id" id="coach_id" class="form-control select2 @error('coach_id') is-invalid @enderror">
                                            <option value="">Select Coach</option>
                                            @foreach($coaches as $coach)
                                            <option value="{{ $coach->id }}" {{ old('coach_id', $appointment->coach_id) == $coach->id ? 'selected' : '' }}>{{ $coach->user->first_name }} {{ $coach->user->last_name }}</option>
                                            @endforeach
                                        </select>
                                        @error('coach_id')
                                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Min Participants -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="min_participants">Minimum Participants</label>
                                        <input type="number" name="min_participants" id="min_participants" class="form-control @error('min_participants') is-invalid @enderror" placeholder="Enter minimum participants" value="{{ old('min_participants', $appointment->min_participants) }}">
                                        @error('min_participants')
                                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Max Participants -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="max_participants">Maximum Participants</label>
                                        <input type="number" name="max_participants" id="max_participants" class="form-control @error('max_participants') is-invalid @enderror" placeholder="Enter maximum participants" value="{{ old('max_participants', $appointment->max_participants) }}">
                                        @error('max_participants')
                                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Start Time -->
                                <!-- Start Time -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="start_time">Start Time</label>
                                        <input type="datetime-local" name="start_time" id="start_time" class="form-control @error('start_time') is-invalid @enderror"
                                            value="{{ old('start_time', \Carbon\Carbon::parse($appointment->start_time)->format('Y-m-d\TH:i')) }}">
                                        @error('start_time')
                                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- End Time -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="end_time">End Time</label>
                                        <input type="datetime-local" name="end_time" id="end_time" class="form-control @error('end_time') is-invalid @enderror"
                                            value="{{ old('end_time', \Carbon\Carbon::parse($appointment->end_time)->format('Y-m-d\TH:i')) }}">
                                        @error('end_time')
                                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>


                                <!-- Location -->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="location">Location</label>
                                        <input type="text" name="location" id="location" class="form-control @error('location') is-invalid @enderror" placeholder="Enter location" value="{{ old('location', $appointment->location) }}">
                                        @error('location')
                                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="box-footer text-right">
                                <button type="submit" class="btn btn-primary">Update Appointment</button>
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
        $('#coach_id, #class_id').select2({
            placeholder: "Select an option",
            allowClear: true
        });
    });
</script>
@endsection