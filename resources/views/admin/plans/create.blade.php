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
    .btn-success {
        padding: 10px 20px;
        font-size: 16px;
        font-weight: bold;
        border-radius: 5px;
        transition: all 0.3s ease;
    }
    .btn-success:hover {
        background-color: #218838;
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
                        <h1 class="m-0">Manage Plans</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">Plans</li>
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
                        <h3 class="card-title">Create Plan</h3>
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
                        <form action="{{ route('admin.plans.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <!-- Plan Name -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="plan_name">Plan Name</label>
                                        <input type="text" name="plan_name" id="plan_name" class="form-control @error('plan_name') is-invalid @enderror" placeholder="Enter plan name" value="{{ old('plan_name') }}" required>
                                        @error('plan_name')
                                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Type -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="type">Type</label>
                                        <input type="text" name="type" id="type" class="form-control @error('type') is-invalid @enderror" placeholder="Enter plan type" value="{{ old('type') }}" required>
                                        @error('type')
                                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Class -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="class_id">Class</label>
                                        <select name="class_id" id="class_id" class="form-control select2 @error('class_id') is-invalid @enderror">
                                            <option value="">Select Class</option>
                                            @foreach($classes as $class)
                                                <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('class_id')
                                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Total Classes -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="total_classes">Total Classes</label>
                                        <input type="number" name="total_classes" id="total_classes" class="form-control @error('total_classes') is-invalid @enderror" placeholder="Enter total classes" value="{{ old('total_classes') }}" min="0" required>
                                        @error('total_classes')
                                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Description -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea name="description" id="description" rows="4" class="form-control @error('description') is-invalid @enderror" placeholder="Enter description">{{ old('description') }}</textarea>
                                        @error('description')
                                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Photo -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="photo">Photo</label>
                                        <input type="file" name="photo" id="photo" class="form-control-file @error('photo') is-invalid @enderror">
                                        @error('photo')
                                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Price -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="price">Price (USD)</label>
                                        <input type="number" step="0.01" name="price" id="price" class="form-control @error('price') is-invalid @enderror" placeholder="Enter price" value="{{ old('price') }}" min="0" required>
                                        @error('price')
                                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Section Plan -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="section_plan_id">Section Plan</label>
                                        <select name="section_plan_id" id="section_plan_id" class="form-control select2 @error('section_plan_id') is-invalid @enderror">
                                            <option value="">Select Section Plan</option>
                                            @foreach($sectionPlans as $sectionPlan)
                                                <option value="{{ $sectionPlan->id }}" {{ old('section_plan_id') == $sectionPlan->id ? 'selected' : '' }}>{{ $sectionPlan->section_name }}</option>
                                            @endforeach
                                        </select>
                                        @error('section_plan_id')
                                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="box-footer text-right">
                                <button type="submit" class="btn btn-primary">Create Plan</button>
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
        $('#class_id, #section_plan_id').select2({
            placeholder: "Select an option",
            allowClear: true
        });
    });
</script>
@endsection
