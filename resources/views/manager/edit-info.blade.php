@extends('templates.manager')

@section('pageTitle', 'Edit restaurant info')

@push('bodyClasses', ' edit-info ')

@section('content')
  <form enctype="multipart/form-data" method="post" action="{{ route('manager.editInfoSubmit') }}">
  <div class="wrapper">
    <h1>Edit restaurant info</h1>
    <div class="form-holder">
        {{ csrf_field() }}
        <div class="form-group">
          <h2>Personal Information</h2>
          <div class="formitem">
            <label>First Name</label>
            <input type="text" required name="first_name" value="{{ $user->first_name }}">
          </div>
          <div class="formitem">
            <label>Last Name</label>
            <input type="text" required name="last_name" value="{{ $user->last_name }}">
          </div>
          <div class="formitem">
            <label>Email Address</label>
            <input type="email" required name="email" value="{{ $user->email }}">
          </div>
          <div class="formitem">
            <label>Password</label>
            <input type="password" name="password" placeholder="••••••••" >
          </div>
        </div>
        <div class="form-group">
          <h2>Restaurant Information</h2>
          <div class="formitem">
            <label>Restaurant Name</label>
            <input type="text" required name="restaurant[name]" value="{{ $restaurant->name }}">
          </div>
          <div class="formitem">
            <label for="restaurant_cover">Cover Image</label>
            @foreach($restaurant->cover as $cover)
            <div style="display:inline-block">
            <label for="restaurant_cover" class="formitem__imagepreview"  style="background-image: url('{{ $cover }}')"></label>
            <a href="{{route('manager.deleteCover',$loop->index)}}">Delete Image</a>
            </div>
            @endforeach
            </label><br>
            <input type="file" id="restaurant_cover" name="restaurant[cover]">
          </div>
          <div class="formitem">
            <label for="restaurant_logo">Logo</label>
            <label for="restaurant_logo" class="formitem__imagepreview" style="background-image: url('{{ $restaurant->logo }}')">

            </label>
            <input type="file" id="restaurant_logo" name="restaurant[logo]" value="{{ $restaurant->logo }}">
          </div>
          <div class="formitem">
            <label>Description</label>
            <textarea name="restaurant[description]" required >{{ $restaurant->description }}</textarea>
          </div>
          <div class="formitem">
            <label>Allergy Notice</label>
            <input type="text" required name="restaurant[allergy_info]" value="{{ $restaurant->allergy_info }}">
          </div>
          <div class="formitem">
            <label>Twitter URL</label>
            <input type="text" name="restaurant[twitter]" value="{{ $restaurant->twitter }}">
          </div>
          <div class="formitem">
            <label>Facebook URL</label>
            <input type="text" name="restaurant[facebook]" value="{{ $restaurant->facebook }}">
          </div>
          <div class="formitem">
            <label>YouTube URL</label>
            <input type="text" name="restaurant[youtube]" value="{{ $restaurant->youtube }}">
          </div>
          <div class="formitem">
            <label>Contact Number</label>
            <input type="text" required name="restaurant[contact_number]" value="{{ $restaurant->contact_number }}">
          </div>
          <div class="formitem">
            <label>Hygiene Rating</label>
            <input type="number" min="0" max="5" step="1" name="restaurant[hygiene_rating]" value="{{ $restaurant->hygiene_rating }}">
          </div>
          <div class="formitem formitem--toggles">
            <label>Featured food categories</label>
            <label><input type="checkbox" name="restaurant[favourites][]" value="pizzas"><span>Pizzas</span></label>
            <label><input type="checkbox" name="restaurant[favourites][]" value="kebabs"><span>Kebabs</span></label>
            <label><input type="checkbox" name="restaurant[favourites][]" value="burgers"><span>Burgers</span></label>
            <label><input type="checkbox" name="restaurant[favourites][]" value="wraps"><span>Wraps</span></label>
            <label><input type="checkbox" name="restaurant[favourites][]" value="sides"><span>Sides</span></label>
            <label><input type="checkbox" name="restaurant[favourites][]" value="pasta"><span>Pasta</span></label>
            <label><input type="checkbox" name="restaurant[favourites][]" value="steak-and-ribs"><span>Steak and Ribs</span></label>
          </div>
          <div class="formitem formitem--toggles">
            <label>Restaurant options</label>
            <label><input type="checkbox" name="restaurant[flags][]" value="halal"><span>Halal available</span></label>
            <label><input type="checkbox" name="restaurant[flags][]" value="new"><span>New</span></label>
            <label><input type="checkbox" name="restaurant[flags][]" value="special-offers"><span>Special offers</span></label>
            <label><input type="checkbox" name="restaurant[flags][]" value="free-delivery"><span>Free delivery</span></label>
          </div>
          <div class="formitem">
            <label>Average collection waiting time</label>
            <input type="number" min="0" step="5" required name="restaurant[collection_waiting_time]" value="{{ $restaurant->collection_lead_time }}">
          </div>
          <div class="formitem">
            <label>Average delivery waiting time</label>
            <input type="number" min="0" step="5" required name="restaurant[delivery_waiting_time]" value="{{ $restaurant->delivery_lead_time }}">
          </div>
          <div class="formitem">
            <label>Delivery minimum order value</label>
            <input type="number" required min="0" step="0.01" name="restaurant[delivery_minimum]" value="{{ $restaurant->delivery_minimum }}">
          </div>
          <div class="formitem">
            <label>Delivery fee</label>
            <input type="number" required min="0" step="0.01" name="restaurant[delivery_fee]" value="{{ $restaurant->delivery_fee }}">
          </div>
          <div class="formitem">
            <label>Percentage discount</label>
            <input type="number" min="0" step="1" max="99"  name="restaurant[discount_percentage]" value="{{ $restaurant->discount_percentage }}">
          </div>
          <div class="formitem">
            <label>Service charge</label>
            <input type="number" required min="0" step="0.01" max="99.99" name="restaurant[service_charge]" value="{{ $restaurant->service_charge }}">
          </div>

        </div>

        <div class="form-group">
          <h2>Restaurant location</h2>
          <div class="row">
            <div class="col-12 col-md-4">
              <div class="formitem">
                <label>Address line 1 </label>
                <input type="text" required name="restaurant[address_line_1]" value="{{ $restaurant->address_line_1 }}">
              </div>
              <div class="formitem">
                <label>Address line 2 </label>
                <input type="text" name="restaurant[address_line_2]" value="{{ $restaurant->address_line_2 }}">
              </div>
              <div class="formitem">
                <label>Address city </label>
                <input type="text" required name="restaurant[address_city]" value="{{ $restaurant->address_city }}">
              </div>
              <div class="formitem">
                <label>Address county </label>
                <input type="text" required name="restaurant[address_county]" value="{{ $restaurant->address_county }}">
              </div>
              <div class="formitem">
                <label>Address postcode </label>
                <input type="text" required name="restaurant[address_postcode]" value="{{ $restaurant->address_postcode }}">
                <input type="hidden" name="restaurant[lat]" value="{{ $restaurant->lat }}">
                <input type="hidden" name="restaurant[lng]" value="{{ $restaurant->lng }}">
              </div>
              <div class="formitem">
                <a class="button button--green find-address">
                  Find address on map
                </a>
              </div>
            </div>

            <div class="col-12 col-md-8 flex flex--column">
              <div class="radius-map">

              </div>
              <div class="row">
                <div class="col-12 col-sm-8">
              <div class="flex flex--v-center">
              <p><b>Delivery range</b></p>
              <input type="number" name="rangeDisplay" value="{{ $restaurant->delivery_range ?? '4' }}" readonly>
              <span><b>&nbsp;miles</b></span>
            </div>
              <div class="flex flex--v-center range-with-labels">
                <span>1 mile</span>
                <input disabled name="restaurant[delivery_range]" type="range" value="{{ $restaurant->delivery_range ?? '4' }}" step=".5" min="1" max="15">
                <span>15 miles</span>
              </div>
            </div>
          </div></div>
        </div>
        </div>

        <div class="form-group">
          <h2>Opening Hours</h2>
          <div class="formitem">
            <div class="formitem__hoursgrid">
              <div>
                <label>Sunday</label>
                <input type="text" name="restaurant[opening_hours][0][0]" value="{{ $restaurant->opening_hours[0][0] }}">
                <span>until</span>
                <input type="text" name="restaurant[opening_hours][0][1]" value="{{ $restaurant->opening_hours[0][1] }}">
              </div>

              <div>
                <label>Monday</label>
                <input type="text" name="restaurant[opening_hours][1][0]" value="{{ $restaurant->opening_hours[1][0] }}">
                <span>until</span>
                <input type="text" name="restaurant[opening_hours][1][1]" value="{{ $restaurant->opening_hours[1][1] }}">
              </div>

              <div>
                <label>Tuesday</label>
                <input type="text" name="restaurant[opening_hours][2][0]" value="{{ $restaurant->opening_hours[2][0] }}">
                <span>until</span>
                <input type="text" name="restaurant[opening_hours][2][1]" value="{{ $restaurant->opening_hours[2][1] }}">
              </div>

              <div>
                <label>Wednesday</label>
                <input type="text" name="restaurant[opening_hours][3][0]" value="{{ $restaurant->opening_hours[3][0] }}">
                <span>until</span>
                <input type="text" name="restaurant[opening_hours][3][1]" value="{{ $restaurant->opening_hours[3][1] }}">
              </div>

              <div>
                <label>Thursday</label>
                <input type="text" name="restaurant[opening_hours][4][0]" value="{{ $restaurant->opening_hours[4][0] }}">
                <span>until</span>
                <input type="text" name="restaurant[opening_hours][4][1]" value="{{ $restaurant->opening_hours[4][1] }}">
              </div>

              <div>
                <label>Friday</label>
                <input type="text" name="restaurant[opening_hours][5][0]" value="{{ $restaurant->opening_hours[5][0] }}">
                <span>until</span>
                <input type="text" name="restaurant[opening_hours][5][1]" value="{{ $restaurant->opening_hours[5][1] }}">
              </div>

              <div>
                <label>Saturday</label>
                <input type="text" name="restaurant[opening_hours][6][0]" value="{{ $restaurant->opening_hours[6][0] }}">
                <span>until</span>
                <input type="text" name="restaurant[opening_hours][6][1]" value="{{ $restaurant->opening_hours[6][1] }}">
              </div>
            </div>
          </div>
        </div>

        <div class="form-group">
          <h2>Ordering Hours</h2>
          <div class="formitem">
            <div class="formitem__hoursgrid">
              <div>
                <label>Sunday</label>
                <input type="text" name="restaurant[order_hours][0][0]" value="{{ $restaurant->order_hours[0][0] }}">
                <span>until</span>
                <input type="text" name="restaurant[order_hours][0][1]" value="{{ $restaurant->order_hours[0][1] }}">
              </div>

              <div>
                <label>Monday</label>
                <input type="text" name="restaurant[order_hours][1][0]" value="{{ $restaurant->order_hours[1][0] }}">
                <span>until</span>
                <input type="text" name="restaurant[order_hours][1][1]" value="{{ $restaurant->order_hours[1][1] }}">
              </div>

              <div>
                <label>Tuesday</label>
                <input type="text" name="restaurant[order_hours][2][0]" value="{{ $restaurant->order_hours[2][0] }}">
                <span>until</span>
                <input type="text" name="restaurant[order_hours][2][1]" value="{{ $restaurant->order_hours[2][1] }}">
              </div>

              <div>
                <label>Wednesday</label>
                <input type="text" name="restaurant[order_hours][3][0]" value="{{ $restaurant->order_hours[3][0] }}">
                <span>until</span>
                <input type="text" name="restaurant[order_hours][3][1]" value="{{ $restaurant->order_hours[3][1] }}">
              </div>

              <div>
                <label>Thursday</label>
                <input type="text" name="restaurant[order_hours][4][0]" value="{{ $restaurant->order_hours[4][0] }}">
                <span>until</span>
                <input type="text" name="restaurant[order_hours][4][1]" value="{{ $restaurant->order_hours[4][1] }}">
              </div>

              <div>
                <label>Friday</label>
                <input type="text" name="restaurant[order_hours][5][0]" value="{{ $restaurant->order_hours[5][0] }}">
                <span>until</span>
                <input type="text" name="restaurant[order_hours][5][1]" value="{{ $restaurant->order_hours[5][1] }}">
              </div>

              <div>
                <label>Saturday</label>
                <input type="text" name="restaurant[order_hours][6][0]" value="{{ $restaurant->order_hours[6][0] }}">
                <span>until</span>
                <input type="text" name="restaurant[order_hours][6][1]" value="{{ $restaurant->order_hours[6][1] }}">
              </div>
            </div>
          </div>
        </div>


        <div class="form-group">
          <h2>Delivery Hours</h2>
          <div class="formitem">
            <div class="formitem__hoursgrid">
              <div>
                <label>Sunday</label>
                <input type="text" name="restaurant[delivery_hours][0][0]" value="{{ $restaurant->delivery_hours[0][0] }}">
                <span>until</span>
                <input type="text" name="restaurant[delivery_hours][0][1]" value="{{ $restaurant->delivery_hours[0][1] }}">
              </div>

              <div>
                <label>Monday</label>
                <input type="text" name="restaurant[delivery_hours][1][0]" value="{{ $restaurant->delivery_hours[1][0] }}">
                <span>until</span>
                <input type="text" name="restaurant[delivery_hours][1][1]" value="{{ $restaurant->delivery_hours[1][1] }}">
              </div>

              <div>
                <label>Tuesday</label>
                <input type="text" name="restaurant[delivery_hours][2][0]" value="{{ $restaurant->delivery_hours[2][0] }}">
                <span>until</span>
                <input type="text" name="restaurant[delivery_hours][2][1]" value="{{ $restaurant->delivery_hours[2][1] }}">
              </div>

              <div>
                <label>Wednesday</label>
                <input type="text" name="restaurant[delivery_hours][3][0]" value="{{ $restaurant->delivery_hours[3][0] }}">
                <span>until</span>
                <input type="text" name="restaurant[delivery_hours][3][1]" value="{{ $restaurant->delivery_hours[3][1] }}">
              </div>

              <div>
                <label>Thursday</label>
                <input type="text" name="restaurant[delivery_hours][4][0]" value="{{ $restaurant->delivery_hours[4][0] }}">
                <span>until</span>
                <input type="text" name="restaurant[delivery_hours][4][1]" value="{{ $restaurant->delivery_hours[4][1] }}">
              </div>

              <div>
                <label>Friday</label>
                <input type="text" name="restaurant[delivery_hours][5][0]" value="{{ $restaurant->delivery_hours[5][0] }}">
                <span>until</span>
                <input type="text" name="restaurant[delivery_hours][5][1]" value="{{ $restaurant->delivery_hours[5][1] }}">
              </div>

              <div>
                <label>Saturday</label>
                <input type="text" name="restaurant[delivery_hours][6][0]" value="{{ $restaurant->delivery_hours[6][0] }}">
                <span>until</span>
                <input type="text" name="restaurant[delivery_hours][6][1]" value="{{ $restaurant->delivery_hours[6][1] }}">
              </div>
            </div>
          </div>

        </div>
    </div>
  </div>

  <div class="submit-bar">
    <div class="wrapper">
      <input type="submit" value="Save restaurant info" class="button button--green">
    </div>
  </div>
</form>
@endsection

@push('footerScripts')
  <script type="text/javascript" src="//maps.google.com/maps/api/js?key=AIzaSyACvDX33bTkHYANI7IbADvmKlKyF4xsD0Q&sensor=true"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gmaps.js/0.4.25/gmaps.min.js" integrity="sha256-7vjlAeb8OaTrCXZkCNun9djzuB2owUsaO72kXaFDBJs=" crossorigin="anonymous"></script>
  <script>
  (function(){
      var $findAddress = document.querySelector('.find-address');
      var $postcodeField = document.querySelector('[name="restaurant[address_postcode]"]');
      var $radiusSlider = document.querySelector('[name="restaurant[delivery_range]"]');
      var $radiusDisplay = document.querySelector('[name="rangeDisplay"]');
      var $latitudeField = document.querySelector('[name="restaurant[lat]"]');
      var $longitudeField = document.querySelector('[name="restaurant[lng]"]');
      var circle = false;

      var $map = new GMaps({
        div: '.radius-map',
        lat: {{ $restaurant->lat ?? '52.561667' }},
        lng:  {{ $restaurant->lng ?? '-1.447222' }},
        zoom: {{ (empty($restaurant->lat)) ? '6' : '14' }}
      });
      $map.rangeCircle = function (radiusInMiles, marker = false) {
        if (!marker) {
          marker = $map.markers[0];
        }
        $radiusDisplay.value = radiusInMiles;
        $map.removePolygons();

        var lat = marker.position.lat();
        var lng = marker.position.lng();

        circle = $map.drawCircle({
          lat: lat,
          lng: lng,
          radius: (parseFloat(radiusInMiles)*1609),
          fillColor: '#65AD55',
          fillOpacity: 0.4,
          strokeColor: '#65AD55',
          strokeOpacity: 0.8
        });
        $map.fitBounds(circle.getBounds());
      }
      $map.geocodeToMap = function (postcode) {
        GMaps.geocode({
          address: postcode,
          region: 'GB',
          componentRestrictions: {
            country: 'GB',
          },
          callback: function(results, status) {
            $map.removeMarkers();
            if (status == 'OK') {
              $radiusSlider.disabled = false;
              var latlng = results[0].geometry.location;
              var lat = latlng.lat();
              var lng = latlng.lng();

              $latitudeField.value = lat;
              $longitudeField.value = lng;

              $map.setCenter(lat, lng);
              $map.addMarker({
                lat: lat,
                lng: lng
              });
              $map.setZoom(13);
              $map.rangeCircle($radiusSlider.value);
            } else {
              alert('No results found for this address. Please check the information you entered, especially your postcode.');
            }
          }
        });
      }

      $findAddress.addEventListener('click', function () {
        postcode = $postcodeField.value;
        $map.geocodeToMap(postcode);
      });

      $radiusSlider.addEventListener('input', function () {
        if ($map.markers.length > 0) {
          $map.rangeCircle( this.value );
        }
      });

      @if(!empty($restaurant->address_postcode))
        document.addEventListener('DOMContentLoaded', $map.geocodeToMap("{{$restaurant->address_postcode}}"));
      @endif
    }())
  </script>
@endpush
