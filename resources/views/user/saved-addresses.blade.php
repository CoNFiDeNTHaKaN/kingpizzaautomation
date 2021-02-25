@extends('layouts.main')

@section('pageTitle', 'Addresses | My account')

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
  
              <h3>Your Saved Addresses</h3>
              <a href="#0" data-toggle="modal" data-target="#exampleModal" style="font-size:16px;">
                Add a New Address
              </a>
              <div style="clear:both"></div>
              @if(count($user->addresses)>0)
                @foreach($user->addresses as $address)
                
                <div style="width:18rem; padding:20px; margin:5px; float:left; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
                  
                  <b>{{$address->name}}</b><hr>
                  <b>Address Line 1:</b><br>
                  {{$address->address_line1}}<br>
                  <b>Address Line 2:</b><br>
                  {{$address->address_line2}}<br>
                  <b>City:</b><br>
                  {{$address->city}}<br>
                  <b>Postcode:</b><br>
                  {{$address->postcode}}<br><br>
                  <a href="#0" data-toggle="modal" data-target="#address-{{$address->id}}">Edit</a>
                  <a href="#" onclick="deleteConfirm({{$address->id}})">Delete</a>
                  
                </div>               
                
                @endforeach
                <div style="clear:both"></div>
              @else
              You don't have any saved address.
              @endif
            </div>
        </div>
    </div>
</div>


@endsection

@section('postcontent')

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="z-index:999999">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add a New Address</h5>
        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action={{route('user.addAddress')}} method="post">
          {{ csrf_field() }}
            <div class="form-group">
              <label for="line1">Address Name</label>
              <input id="name" class="form-control" type="text" name="name">
            </div>
            <div class="form-group">
              <label for="line1">Address line 1</label>
              <input id="line1" class="form-control" type="text" name="line1">
            </div>
            <div class="form-group">
              <label for="line2">Address line 2</label>
              <input id="line2" class="form-control" type="text" name="line2">
            </div>
            <div class="form-group">
              <label for="city">City</label>
              <input id="city" class="form-control" type="text" name="city">
            </div>
            <div class="form-group">
              <label for="postcode">Postcode</label>
              <input id="postcode" class="form-control" type="text" name="postcode">
            </div>
            <input type="submit" value="Add" class="btn_1 gradient medium" style="float:right;">
          </form>
      </div>
    </div>
  </div>
</div>

@if(count($user->addresses)>0)
  @foreach($user->addresses as $address)
  <div class="modal fade" id="address-{{$address->id}}" tabindex="-1"  aria-hidden="true" style="z-index:99999">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          @include('user.edit-address',$address)
        </div>
      </div>
    </div>
  </div>
  @endforeach
@endif
<script>
function deleteConfirm(id){
	var result = confirm("Do you want to delete this address?");
if (result) {
    window.location="{{url('/user/saved-addresses/delete/')}}/"+id;
	}
}
</script>
@endsection