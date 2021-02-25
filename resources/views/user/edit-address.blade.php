<div class="content-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 mt-5">
              <h3>Edit  Address</h3>
              <form action="{{route('user.updateAddress')}}" method="post">
              {{ csrf_field() }}
                <div class="form-group">
                  <label for="line1">Address Name</label>
                  <input id="name" class="form-control" type="text" name="name" value="{{$address->name}}">
                </div>
                <div class="form-group">
                  <label for="line1">Address line 1</label>
                  <input id="line1" class="form-control" type="text" name="line1" value="{{$address->address_line1}}">
                </div>
                <div class="form-group">
                  <label for="line2">Address line 2</label>
                  <input id="line2" class="form-control" type="text" name="line2" value="{{$address->address_line2}}">
                </div>
                <div class="form-group">
                  <label for="city">City</label>
                  <input id="city" class="form-control" type="text" name="city" value="{{$address->city}}">
                </div>
                <div class="form-group">
                  <label for="postcode">Postcode</label>
                  <input id="postcode" class="form-control" type="text" name="postcode" value="{{$address->postcode}}">
                </div>
                <input type="hidden" name="addressid" value="{{$address->id}}">
                <input type="submit" value="Update" class="btn_1 gradient medium">
              </form>
            </div>
        </div>
    </div>
</div>


