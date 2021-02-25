module.exports = (function(argument) {

  var shipping = [{
    "Vendor": "ROYAL MAIL",
    "PostalServices": [{
      "pkPostalServiceId": "3a1f4245-b174-4bf1-b674-897e3a2ad27d",
      "PostalServiceName": "1st Class Large Letter - STL",
      "TrackingNumberRequired": false,
      "Vendor": null
    }, {
      "pkPostalServiceId": "77961a8d-3ea2-4e19-aa92-4834ba3ddaac",
      "PostalServiceName": "2nd Class Large Letter - STL",
      "TrackingNumberRequired": false,
      "Vendor": null
    }, {
      "pkPostalServiceId": "9f30d3b3-a931-4a8e-aed5-0d73e7212bae",
      "PostalServiceName": "2nd Class Letter - STL",
      "TrackingNumberRequired": false,
      "Vendor": null
    }, {
      "pkPostalServiceId": "01e8f285-7686-4442-ae45-22f6c92b6091",
      "PostalServiceName": "INTL BUS PARCELS ZONE SORT PRIORITY - IE1",
      "TrackingNumberRequired": false,
      "Vendor": null
    }, {
      "pkPostalServiceId": "6123a70b-d756-4255-b602-cc7552b9cf7c",
      "PostalServiceName": "Overseas int Signed For - OLA",
      "TrackingNumberRequired": true,
      "Vendor": null
    }, {
      "pkPostalServiceId": "332cb10b-2e27-4b1e-b20a-2eeddd1bde3e",
      "PostalServiceName": "Overseas Letters Air - OLA",
      "TrackingNumberRequired": false,
      "Vendor": null
    }, {
      "pkPostalServiceId": "fe6323e6-044c-44fc-bbd5-df6bc7308584",
      "PostalServiceName": "RM 24 - CRL",
      "TrackingNumberRequired": false,
      "Vendor": null
    }, {
      "pkPostalServiceId": "6538e8d4-e96e-490d-a143-314512f710e9",
      "PostalServiceName": "RM 24 Recorded - CRL",
      "TrackingNumberRequired": true,
      "Vendor": null
    }, {
      "pkPostalServiceId": "9edcb407-b9b6-4c4d-bb74-d0d46caeff20",
      "PostalServiceName": "RM 48 - CRL",
      "TrackingNumberRequired": false,
      "Vendor": null
    }, {
      "pkPostalServiceId": "dfcff22f-7906-4db5-bc47-3785173ff587",
      "PostalServiceName": "Special Delivery £500 comp - SD1",
      "TrackingNumberRequired": true,
      "Vendor": null
    }]
  }, {
    "Vendor": "NONE",
    "PostalServices": [{
      "pkPostalServiceId": "00000000-0000-0000-0000-000000000000",
      "PostalServiceName": "Default",
      "TrackingNumberRequired": false,
      "Vendor": null
    }]
  }, {
    "Vendor": "Interlink Express",
    "PostalServices": [{
      "pkPostalServiceId": "bff21236-c1a0-429b-92f8-3f18345891cc",
      "PostalServiceName": "DPD Classic - European Courier",
      "TrackingNumberRequired": true,
      "Vendor": null
    }, {
      "pkPostalServiceId": "9ba5bbb3-250d-4dac-943e-5bfa1f145af0",
      "PostalServiceName": "NEXT DAY - Expresspak up to 1 kg",
      "TrackingNumberRequired": true,
      "Vendor": null
    }, {
      "pkPostalServiceId": "340d4ef1-a9db-45c1-82d4-9922e2047366",
      "PostalServiceName": "NEXT DAY - Expresspak up to 5kg",
      "TrackingNumberRequired": true,
      "Vendor": null
    }, {
      "pkPostalServiceId": "886ab48b-62bb-4b3b-a8b6-7f6090c3a4a7",
      "PostalServiceName": "NEXT DAY COURIER - INTERLINK EXPRESS",
      "TrackingNumberRequired": true,
      "Vendor": null
    }]
  }, {
    "Vendor": "No Vendor defined",
    "PostalServices": [{
      "pkPostalServiceId": "48f220a4-13b3-4bf8-a04b-468ad8df07f9",
      "PostalServiceName": "EUROPEAN COURIER - OTHER",
      "TrackingNumberRequired": false,
      "Vendor": null
    }, {
      "pkPostalServiceId": "9d8d9f2f-9da3-432a-8c62-3f919e74a9db",
      "PostalServiceName": "European signed for - IE1",
      "TrackingNumberRequired": false,
      "Vendor": null
    }, {
      "pkPostalServiceId": "559444e8-ecf4-4c94-a650-0fa8c7254392",
      "PostalServiceName": "MY HERMES - HIGHLANDS AND ISLANDS",
      "TrackingNumberRequired": false,
      "Vendor": null
    }]
  }, {
    "Vendor": "PARCELFORCE",
    "PostalServices": [{
      "pkPostalServiceId": "bbcafc2b-242b-4604-84dd-cd43969fdca9",
      "PostalServiceName": "ParcelForce 24",
      "TrackingNumberRequired": true,
      "Vendor": null
    }, {
      "pkPostalServiceId": "7c2cf9b9-225d-4e36-8c98-d926d33f62e2",
      "PostalServiceName": "ParcelForce 48",
      "TrackingNumberRequired": true,
      "Vendor": null
    }, {
      "pkPostalServiceId": "0d0aa71f-db06-44e6-be3f-e82c2abee98e",
      "PostalServiceName": "ParcelForce Euro Priority Business",
      "TrackingNumberRequired": true,
      "Vendor": null
    }]
  }, {
    "Vendor": "Website",
    "PostalServices": [{
      "pkPostalServiceId": "55788b21-493c-453f-b0c9-2cf80fccc104",
      "PostalServiceName": "Website",
      "TrackingNumberRequired": false,
      "Vendor": null
    }]
  }];


  var usefulShipping = [{
    shippingName:"RM 48 - CRL",
    id:"9edcb407-b9b6-4c4d-bb74-d0d46caeff20",
    minWeight:0,
    maxWeight:2000,
    nextDay:false,
    country:"GB",
    vendor:"ROYAL MAIL"
  },{
    shippingName:"RM 24 - CRL",
    id:"fe6323e6-044c-44fc-bbd5-df6bc7308584",
    minWeight:2000,
    maxWeight:3000,
    nextDay:false,
    country:"GB",
    vendor:"ROYAL MAIL"
  },{
    shippingName:"Default",
    id:"00000000-0000-0000-0000-000000000000",
    minWeight:3000,
    maxWeight:Infinity,
    nextDay:false,
    country:"GB",
    vendor:"NONE"
  },{
    shippingName:"NEXT DAY COURIER - INTERLINK EXPRESS",
    id:"886ab48b-62bb-4b3b-a8b6-7f6090c3a4a7",
    minWeight:0,
    maxWeight:Infinity,
    nextDay:true,
    country:"GB",
    vendor:"Interlink Express"

  },
  {
    shippingName:"INTL BUS PARCELS ZONE SORT PRIORITY - IE1",
    id:"01e8f285-7686-4442-ae45-22f6c92b6091",
    minWeight:0,
    maxWeight:2000,
    nextDay:false,
    country:"*",
    exclude:"GB",
    vendor:"ROYAL MAIL"
  },{
    shippingName:"Default",
    id:"00000000-0000-0000-0000-000000000000",
    minWeight:2000,
    maxWeight:Infinity,
    nextDay:false,
    country:"*",
    exclude:"GB",
    vendor:"NONE"
  }
]

  function getShippingIdByName(shippingName) {
    var ret;
    /*shipping.forEach(function(obj, i) {
      var options = obj.PostalServices;
    });*/
    for (var i = 0, len = shipping.length; i < len; i++){
      var obj = shipping[i],
      foundOption = false;

      for (var i2 = 0, len2 = obj.PostalServices.length; i2 < len2; i2++) {
        var postalService = obj.PostalServices[i2];
        if (postalService.PostalServiceName == shippingName) {
          foundOption = true;
          ret = postalService.pkPostalServiceId;
          break;
        }
      }
      if(foundOption){
        break;
      }
    }

    return ret;


  }

function filterByWeight(weight) {
  return usefulShipping.filter(function(ship,i) {
    if(ship.minWeight < weight && ship.maxWeight > weight){
      return true;
    }
  });
}
  function getShippingOption(weight, countryCode, nextDay) { // weight must be in grams
    console.log(weight, countryCode, nextDay);
    var ret,
        possibleOptions = filterByWeight(weight),
        options = possibleOptions.filter(function(option, i) {
            if (option.country == "*" && option.exclude != countryCode && nextDay == option.nextDay) return true;
            else if(option.country == "GB" && countryCode == "GB" && nextDay == option.nextDay) return true;
        });
        ret = options;
        return ret;
  }

  return{
    getShippingIdByName:getShippingIdByName,
    getShippingOption:getShippingOption
  }


}());
