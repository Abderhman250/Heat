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
                        <h1 class="m-0">Manage Levels</h1>
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

        <!-- Main Content -->
        <section class="content">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Create Level</h3>
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
                        <form action="{{ route('admin.level.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <!-- Title Levels -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="title_levels">Title Levels</label>
                                        <input type="text" name="title_levels" id="title_levels" 
                                               class="form-control @error('title_levels') is-invalid @enderror" 
                                               placeholder="Enter level title" 
                                               value="{{ old('title_levels') }}" required>
                                        @error('title_levels')
                                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Required Classes -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="required_classes">Required Classes</label>
                                        <input type="number" name="required_classes" id="required_classes" 
                                               class="form-control @error('required_classes') is-invalid @enderror" 
                                               placeholder="Enter number of required classes" 
                                               value="{{ old('required_classes') }}" required>
                                        @error('required_classes')
                                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="col-md-12">
                                    <div class="text-right">
                                        <button type="submit" class="btn btn-success">Create Level</button>
                                    </div>
                                </div>
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
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('#required_classes').select2({
            placeholder: "Enter number of required classes",
            allowClear: true
        });
    });
</script>
@endsection
