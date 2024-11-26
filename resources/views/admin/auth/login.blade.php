@extends('admin.layouts.app')


@section('content')
<div class="hold-transition login-page">
  <div class="login-box">
    <!-- /.login-logo -->
    @if ($errors->has('login'))
          <div class="alert alert-danger">{{ $errors->first('login') }}</div>
          @endif
    <div class="card card-outline card-primary">
      <div class="card-header text-center">
        <a href="../../index2.html" class="h1"><b>Admin</b>HEAT</a>
      </div>
      <div class="card-body">
        <p class="login-box-msg">Sign in to start your session</p>
        <form action="{{ route('admin.login') }}" method="POST">

          @csrf
          <div class="input-group mb-1">
            <input type="email" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}" required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div>
          </div>
          @if ($errors->has('email'))
          <span class="text-danger mb-3">{{ $errors->first('email') }}</span>
          @endif

          <div class="input-group mt-3 mb-3">
            <input type="password" name="password" class="form-control" placeholder="Password" required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          @if ($errors->has('password'))
          <span class="text-danger">{{ $errors->first('password') }}</span>
          @endif

          <div class="row">
            <div class="col-12">
              <div class="icheck-primary">
                <input type="checkbox" id="remember">
                <label for="remember">
                  Remember Me
                </label>
              </div>
            </div>
          </div>
      
          <!-- /.col -->
          <div class="row">
            <div class="col-12">
              <button type="submit" class="btn btn-primary btn-block">Sign In</button>
            </div>
          </div>
        </form>


        <!-- /.social-auth-links -->

        <!-- <p class="mb-1">
        <a href="forgot-password.html">I forgot my password</a>
      </p>
      <p class="mb-0">
        <a href="register.html" class="text-center">Register a new membership</a>
      </p> -->
      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->
  </div>
  @endsection