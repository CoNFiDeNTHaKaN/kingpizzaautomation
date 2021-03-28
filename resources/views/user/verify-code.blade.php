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
					<form action="{{route('user.postVerifyCode')}}" method="POST">
					@csrf
						<div class="form-group">
						<input class="form-control" name="code" placeholder="Code in SMS">
						</div>
						<div class="form-group">
						<input type="submit" class="form-control btn btn_1" value="Activate Account">
						</div>
					</form>
					Or <a href="{{route('user.verifyPhone')}}">go back</a> and enter your number again.
					</div>
				</div>
			</div>
            
        </div>
@endsection

