@extends('layouts.main')

@section('pageTitle', 'Verify Your Phone')

@section('content')
        <div class="container">
			<div class="forgot">
				<div class="row">
					<div class="col-12">
					@if ($errors->any())
				  <!-- yeni düzenledim -->
                  <div class="searchError">
				  <!-- *** -->
                    <ul>
                      @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                      @endforeach
                    </ul>
                  </div>
                @endif
					<p><b>{{session('message') ?? ''}}</b></p>
					Please enter your phone number to activate your account.
					<form action="{{route('user.sendVerificationCode')}}" method="POST">
					@csrf
						<div class="form-group">
						<input class="form-control" name="contact_number" placeholder="Your mobile phone number">
						</div>
						<div class="form-group">
						<input type="submit" class="form-control btn btn_1" value="Send">
						</div>
					</form>
					</div>
				</div>
			</div>
            
        </div>
@endsection

