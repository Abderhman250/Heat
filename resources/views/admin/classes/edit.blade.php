@extends('admin.layouts.app')

@section('links')
    <!-- Include your CSS and JS links here -->
@endsection

@section('content')
<div class="wrapper">
    @include('admin.layouts.nav')
    @include('admin.layouts.sidebar')

    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Edit Class</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard') }}">Home</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.classes.index') }}">Classes</a>
                            </li>
                            <li class="breadcrumb-item active">Edit</li>
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
                        <h3 class="card-title">Edit Class</h3>
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

                        <!-- Edit Form -->
                        <form action="{{ route('admin.classes.update', $class->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <!-- Name -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Class Name</label>
                                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="Enter class name" value="{{ old('name', $class->name) }}" required>
                                        @error('name')
                                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Room -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="room">Room</label>
                                        <input type="text" name="room" id="room" class="form-control @error('room') is-invalid @enderror" placeholder="Enter room name or number" value="{{ old('room', $class->room) }}">
                                        @error('room')
                                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Capacity -->
                                <!-- <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="capacity">Capacity</label>
                                        <input type="number" name="capacity" id="capacity" class="form-control @error('capacity') is-invalid @enderror" placeholder="Enter capacity" value="{{ old('capacity', $class->capacity) }}" min="1" required>
                                        @error('capacity')
                                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div> -->
                      
                                <!-- Type Name -->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="type_name">Class Type</label>
                                        <select name="class_type_id" id="class_type_id" class="form-control select2 @error('class_type_id') is-invalid @enderror">
                                            <option value="">Select Type</option>
                                            @foreach($class_type  as $key=>$value)
                                               <option value="{{$value->id}}" {{ old('class_type_id',$class->class_type_id) == $value->id ? 'selected' : '' }}>{{$value->type_name}}</option>
                                            @endforeach
                                        </select>
                                        @error('class_type_id')
                                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Booking Process -->

                                <!-- Description -->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" placeholder="Enter description" rows="4">{{ old('description', $class->description) }}</textarea>
                                        @error('description')
                                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

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
                                            <img id="photo-preview" src="{{$class->photo}}" alt="Photo Preview" class="img-thumbnail mt-3" style="display: block; max-width: 150px; height: auto; border: 2px solid #ddd;">
                                            @error('photo')
                                                <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="box-footer text-right">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Class
                                </button>
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
    function showPreview(event) {
        const preview = document.getElementById('photo-preview');
        const file = event.target.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            preview.src = '';
            preview.style.display = 'none';
        }
    }

</script>
@endsection
