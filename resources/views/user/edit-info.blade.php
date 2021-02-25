@extends('layouts.main')

@section('pageTitle', 'My account')

@section('content')
    <div class="content-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-12 mt-5">
                  @if ($errors->any())
                  <div class="alert alert-danger">
                      <ul>
                          @foreach ($errors->all() as $error)
                              <li>{{ $error }}</li>
                          @endforeach
                      </ul>
                  </div>
                  @endif
                   @if (Session::has('success'))
                  <div class="alert alert-success">
                      <ul>
                          <li>{{ Session::get('success') }}</li>
                      </ul>
                  </div>
                   @endif
                  <h3>Edit your info</h3>
                  <form enctype="multipart/form-data" method="post" action="{{ route('user.editInfoSubmit') }}">
                    {{ csrf_field() }}
                    <div class="form-group">
                      <label for="first_name">First Name</label>
                      <input type="text" class="form-control" name="first_name" id="first_name" value="{{ $user->first_name }}" required>
                    </div>
                    <div class="form-group">
                      <label for="last_name">Last Name</label>
                      <input type="text" class="form-control" name="last_name" id="last_name" value="{{ $user->last_name }}" required>
                    </div>
                    <div class="form-group">
                      <label for="email">Email address</label>
                      <input type="email" class="form-control" name="email" id="email" value="{{ $user->email }}" required>
                    </div>
                    <div class="form-group">
                      <label for="contact_number">Contact number</label>
                      <input type="tel" class="form-control" name="contact_number" id="contact_number" value="{{ $user->contact_number }}" required>
                    </div>
                    <div class="form-group">
                      <label for="password">Password</label>
                      <input type="password" class="form-control" name="password" id="password" placeholder="••••••••">
                    </div>
                    <input type="submit" value="Save" class="btn_1 gredient medium">
                    <br>
                    <br>
                  </form>
                </div>
            </div>
        </div>
    </div>

@endsection
