@extends('layouts.main')

@section('pageTitle', 'Verify Your Phone')

@section('content')
        <div class="container">
			<div class="forgot">
				<div class="row">
					<div class="col-12">
					<p><b>{{$message ?? ''}}</b></p>
					Please enter your phone number to activate your account.
					<form action="{{route('user.sendVerificationCode')}}" method="POST">
					@csrf
						<div class="form-group">
						<input class="form-control" name="contact_number" placeholder="Your mobile phone number">
						</div>
						<div class="form-group">
						<input type="submit" class="form-control btn btn_1">
						</div>
					</form>
					</div>
				</div>
			</div>
            
        </div>
@endsection

