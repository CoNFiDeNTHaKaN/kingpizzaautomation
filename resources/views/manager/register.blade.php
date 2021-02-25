@extends('templates.manager')

@section('content')
<div class="wrapper">
  <h1>Eat Kebab Online business registration</h1>
  <div class="form-holder">
    <form enctype="multipart/form-data" method="post" action="{{ route('manager.registerSubmit') }}">
      {{ csrf_field() }}
      <div class="form-group">
        <h2>Personal Information</h2>
        <div class="form-item">
          <label>First Name</label>
          <input type="text" required name="first_name" value="{{ old('first_name') }}">
        </div>
        <div class="form-item">
          <label>Last Name</label>
          <input type="text" required name="last_name" value="{{ old('last_name') }}">
        </div>
        <div class="form-item">
          <label>Email Address</label>
          <input type="email" required name="email" value="{{ old('email') }}">
        </div>
        <div class="form-item">
          <label>Password</label>
          <input type="password" required name="password" value="{{ old('password') }}">
        </div>
      </div>
      <div class="form-group">
        <h2>Restaurant Information</h2>
        <div class="form-item">
          <label>Restaurant Name</label>
          <input type="text" required name="restaurant[name]" value="{{ old('restaurant[name]') }}">
        </div>
        <div class="form-item">
          <label>Cover Image</label>
          <input type="file" name="restaurant[cover]" value="{{ old('restaurant[cover]') }}">
        </div>
        <div class="form-item">
          <label>Logo</label>
          <input type="file" required name="restaurant[logo]" value="{{ old('restaurant[logo]') }}">
        </div>
        <div class="form-item">
          <label>Description</label>
          <textarea name="restaurant[description]" required >{{ old('restaurant[description]') }}</textarea>
        </div>
        <div class="form-item">
          <label>Allergy Notice</label>
          <input type="text" required name="restaurant[allergy_info]" value="{{ old('restaurant[allergy_info]') }}">
        </div>
        <div class="form-item">
          <label>Twitter URL</label>
          <input type="text" name="restaurant[twitter]" value="{{ old('restaurant[twitter]') }}">
        </div>
        <div class="form-item">
          <label>Facebook URL</label>
          <input type="text" name="restaurant[facebook]" value="{{ old('restaurant[facebook]') }}">
        </div>
        <div class="form-item">
          <label>Youtube URL</label>
          <input type="text" name="restaurant[youtube]" value="{{ old('restaurant[youtube]') }}">
        </div>
        <div class="form-item">
          <label>Contact Number</label>
          <input type="text" required name="restaurant[contact_number]" value="{{ old('restaurant[contact_number]') }}">
        </div>
        <div class="form-item">
          <label>Opening Hours</label>
          <div class="form-item__hoursgrid">
            <div>
              <label>Monday</label>
              <input type="text" name="restaurant[opening_hours][0][0]" value="{{ old('restaurant[opening_hours][0][0]') }}">
              <span>until</span>
              <input type="text" name="restaurant[opening_hours][0][1]" value="{{ old('restaurant[opening_hours][0][1]') }}">
            </div>

            <div>
              <label>Tuesday</label>
              <input type="text" name="restaurant[opening_hours][1][0]" value="{{ old('restaurant[opening_hours][1][0]') }}">
              <span>until</span>
              <input type="text" name="restaurant[opening_hours][1][1]" value="{{ old('restaurant[opening_hours][1][1]') }}">
            </div>

            <div>
              <label>Wednesday</label>
              <input type="text" name="restaurant[opening_hours][2][0]" value="{{ old('restaurant[opening_hours][2][0]') }}">
              <span>until</span>
              <input type="text" name="restaurant[opening_hours][2][1]" value="{{ old('restaurant[opening_hours][2][1]') }}">
            </div>

            <div>
              <label>Thursday</label>
              <input type="text" name="restaurant[opening_hours][3][0]" value="{{ old('restaurant[opening_hours][3][0]') }}">
              <span>until</span>
              <input type="text" name="restaurant[opening_hours][3][1]" value="{{ old('restaurant[opening_hours][3][1]') }}">
            </div>

            <div>
              <label>Friday</label>
              <input type="text" name="restaurant[opening_hours][4][0]" value="{{ old('restaurant[opening_hours][4][0]') }}">
              <span>until</span>
              <input type="text" name="restaurant[opening_hours][4][1]" value="{{ old('restaurant[opening_hours][4][1]') }}">
            </div>

            <div>
              <label>Saturday</label>
              <input type="text" name="restaurant[opening_hours][5][0]" value="{{ old('restaurant[opening_hours][5][0]') }}">
              <span>until</span>
              <input type="text" name="restaurant[opening_hours][5][1]" value="{{ old('restaurant[opening_hours][5][1]') }}">
            </div>

            <div>
              <label>Sunday</label>
              <input type="text" name="restaurant[opening_hours][6][0]" value="{{ old('restaurant[opening_hours][6][0]') }}">
              <span>until</span>
              <input type="text" name="restaurant[opening_hours][6][1]" value="{{ old('restaurant[opening_hours][6][1]') }}">
            </div>
          </div>
        </div>
        <div class="form-item">
          <label>Ordering Hours</label>
          <div class="form-item__hoursgrid">
            <div>
              <label>Monday</label>
              <input type="text" name="restaurant[order_hours][0][0]" value="{{ old('restaurant[order_hours][0][0]') }}">
              <span>until</span>
              <input type="text" name="restaurant[order_hours][0][1]" value="{{ old('restaurant[order_hours][0][1]') }}">
            </div>

            <div>
              <label>Tuesday</label>
              <input type="text" name="restaurant[order_hours][1][0]" value="{{ old('restaurant[order_hours][1][0]') }}">
              <span>until</span>
              <input type="text" name="restaurant[order_hours][1][1]" value="{{ old('restaurant[order_hours][1][1]') }}">
            </div>

            <div>
              <label>Wednesday</label>
              <input type="text" name="restaurant[order_hours][2][0]" value="{{ old('restaurant[order_hours][2][0]') }}">
              <span>until</span>
              <input type="text" name="restaurant[order_hours][2][1]" value="{{ old('restaurant[order_hours][2][1]') }}">
            </div>

            <div>
              <label>Thursday</label>
              <input type="text" name="restaurant[order_hours][3][0]" value="{{ old('restaurant[order_hours][3][0]') }}">
              <span>until</span>
              <input type="text" name="restaurant[order_hours][3][1]" value="{{ old('restaurant[order_hours][3][1]') }}">
            </div>

            <div>
              <label>Friday</label>
              <input type="text" name="restaurant[order_hours][4][0]" value="{{ old('restaurant[order_hours][4][0]') }}">
              <span>until</span>
              <input type="text" name="restaurant[order_hours][4][1]" value="{{ old('restaurant[order_hours][4][1]') }}">
            </div>

            <div>
              <label>Saturday</label>
              <input type="text" name="restaurant[order_hours][5][0]" value="{{ old('restaurant[order_hours][5][0]') }}">
              <span>until</span>
              <input type="text" name="restaurant[order_hours][5][1]" value="{{ old('restaurant[order_hours][5][1]') }}">
            </div>

            <div>
              <label>Sunday</label>
              <input type="text" name="restaurant[order_hours][6][0]" value="{{ old('restaurant[order_hours][6][0]') }}">
              <span>until</span>
              <input type="text" name="restaurant[order_hours][6][1]" value="{{ old('restaurant[order_hours][6][1]') }}">
            </div>
          </div>
        </div>
        <div class="form-item">
          <label>Delivery Hours</label>
          <div class="form-item__hoursgrid">
            <div>
              <label>Monday</label>
              <input type="text" name="restaurant[delivery_hours][0][0]" value="{{ old('restaurant[delivery_hours][0][0]') }}">
              <span>until</span>
              <input type="text" name="restaurant[delivery_hours][0][1]" value="{{ old('restaurant[delivery_hours][0][1]') }}">
            </div>

            <div>
              <label>Tuesday</label>
              <input type="text" name="restaurant[delivery_hours][1][0]" value="{{ old('restaurant[delivery_hours][1][0]') }}">
              <span>until</span>
              <input type="text" name="restaurant[delivery_hours][1][1]" value="{{ old('restaurant[delivery_hours][1][1]') }}">
            </div>

            <div>
              <label>Wednesday</label>
              <input type="text" name="restaurant[delivery_hours][2][0]" value="{{ old('restaurant[delivery_hours][2][0]') }}">
              <span>until</span>
              <input type="text" name="restaurant[delivery_hours][2][1]" value="{{ old('restaurant[delivery_hours][2][1]') }}">
            </div>

            <div>
              <label>Thursday</label>
              <input type="text" name="restaurant[delivery_hours][3][0]" value="{{ old('restaurant[delivery_hours][3][0]') }}">
              <span>until</span>
              <input type="text" name="restaurant[delivery_hours][3][1]" value="{{ old('restaurant[delivery_hours][3][1]') }}">
            </div>

            <div>
              <label>Friday</label>
              <input type="text" name="restaurant[delivery_hours][4][0]" value="{{ old('restaurant[delivery_hours][4][0]') }}">
              <span>until</span>
              <input type="text" name="restaurant[delivery_hours][4][1]" value="{{ old('restaurant[delivery_hours][4][1]') }}">
            </div>

            <div>
              <label>Saturday</label>
              <input type="text" name="restaurant[delivery_hours][5][0]" value="{{ old('restaurant[delivery_hours][5][0]') }}">
              <span>until</span>
              <input type="text" name="restaurant[delivery_hours][5][1]" value="{{ old('restaurant[delivery_hours][5][1]') }}">
            </div>

            <div>
              <label>Sunday</label>
              <input type="text" name="restaurant[delivery_hours][6][0]" value="{{ old('restaurant[delivery_hours][6][0]') }}">
              <span>until</span>
              <input type="text" name="restaurant[delivery_hours][6][1]" value="{{ old('restaurant[delivery_hours][6][1]') }}">
            </div>
          </div>
        </div>
        <div class="form-item">
          <label>Hygiene Rating</label>
          <input type="text" name="restaurant[hygiene_rating]" value="{{ old('restaurant[hygiene_rating]') }}">
        </div>
        <div class="form-item">
          <label>Collection waiting time</label>
          <input type="text" required name="restaurant[collection_waiting_time]" value="{{ old('restaurant[collection_waiting_time]') }}">
        </div>
        <div class="form-item">
          <label>Delivery waiting time</label>
          <input type="text" required name="restaurant[delivery_waiting_time]" value="{{ old('restaurant[delivery_waiting_time]') }}">
        </div>
        <div class="form-item">
          <label>Address line 1 </label>
          <input type="text" required name="restaurant[address_line_1]" value="{{ old('restaurant[address_line_1]') }}">
        </div>
        <div class="form-item">
          <label>Address line 2 </label>
          <input type="text" name="restaurant[address_line_2]" value="{{ old('restaurant[address_line_2]') }}">
        </div>
        <div class="form-item">
          <label>Address city </label>
          <input type="text" required name="restaurant[address_city]" value="{{ old('restaurant[address_city]') }}">
        </div>
        <div class="form-item">
          <label>Address county </label>
          <input type="text" required name="restaurant[address_county]" value="{{ old('restaurant[address_county]') }}">
        </div>
        <div class="form-item">
          <label>Address postcode </label>
          <input type="text" required name="restaurant[address_postcode]" value="{{ old('restaurant[address_postcode]') }}">
        </div>
        <div class="form-item">
          <label>Delivery minimum order value</label>
          <input type="number" required min="0" step="0.01" name="restaurant[delivery_minimum]" value="{{ old('restaurant[delivery_minimum]') }}">
        </div>
        <div class="form-item">
          <label>Delivery fee</label>
          <input type="number" required min="0" step="0.01" name="restaurant[delivery_fee]" value="{{ old('restaurant[delivery_fee]') }}">
        </div>
        <div class="form-button">
          <input type="submit" value="Submit" class="button">
        </div>
      </div>
    </form>
  </div>
</div>
@endsection
