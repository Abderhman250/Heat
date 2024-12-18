@extends('admin.layouts.app')

@section('links')
<link rel="stylesheet" href="{{ asset('admin/plugins/fontawesome-free/css/all.min.css') }}">
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
                        <h1 class="m-0">Update Coach</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">Update Coach</li>
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
                        <h3 class="card-title">Edit Coach</h3>
                    </div>
                    <div class="card-body">
                        <!-- Validation Errors -->
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Form Start -->
                        <form action="{{ route('admin.coach.update', $coach->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="user_id" class="form-control" value="{{ $coach->user->id }}">

                            <div class="row">
                                <!-- First Name -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="first_name">First Name</label>
                                        <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" 
                                               value="{{ old('first_name', $coach->user->first_name) }}" required>
                                        @error('first_name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <!-- Last Name -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="last_name">Last Name</label>
                                        <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" 
                                               value="{{ old('last_name', $coach->user->last_name) }}" required>
                                        @error('last_name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <!-- Phone -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone">Phone</label>
                                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                                               value="{{ old('phone', $coach->user->phone) }}">
                                        @error('phone') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <!-- Country Code -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="country_code">Country Code</label>
                                        <input type="text" name="country_code" class="form-control @error('country_code') is-invalid @enderror" 
                                               value="{{ old('country_code', $coach->user->country_code) }}">
                                        @error('country_code') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <!-- Gender -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="gender">Gender</label>
                                        <select name="gender" class="form-control select2 @error('gender') is-invalid @enderror">
                                            <option value="">Select Gender</option>
                                            <option value="1" {{ $coach->user->gender == 1 ? 'selected' : '' }}>Male</option>
                                            <option value="0" {{ $coach->user->gender == 0 ? 'selected' : '' }}>Female</option>
                                        </select>
                                        @error('gender') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <!-- Email -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                               value="{{ old('email', $coach->user->email) }}">
                                        @error('email') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <!-- Specialty -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="specialty">Specialty</label>
                                        <input type="text" name="specialty" class="form-control @error('specialty') is-invalid @enderror" 
                                               value="{{ old('specialty', $coach->specialty) }}" required>
                                        @error('specialty') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <!-- Bio -->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="bio">Bio</label>
                                        <textarea name="bio" rows="4" class="form-control @error('bio') is-invalid @enderror">{{ old('bio', $coach->bio) }}</textarea>
                                        @error('bio') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <!-- Photo Upload -->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="photo">Profile Photo</label>
                                        <input type="file" name="photo" class="form-control @error('photo') is-invalid @enderror">
                                        @if($coach->user->photo)
                                            <small>Current Photo: <a href="{{ $coach->user->photo }}" target="_blank">View</a></small>
                                        @endif
                                        @error('photo') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="col-md-12 text-right">
                                    <button type="submit" class="btn btn-primary">Update Coach</button>
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
 
@endsection
