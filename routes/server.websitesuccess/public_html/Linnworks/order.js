var xml2js = require('xml2js'),
  parser = new xml2js.Parser(),
  request = require('request'),
  linnworks = require("./linnworks/main.js"),
  linnworksSortData = require("./linnworks/sort-data.js"),
  errorHandler = require("./errorHandler.js"),
  shippingOptions = require("./linnworks/shippingOptions.js");


module.exports = (function() {
  var dataErrors = [];
  var queue = [],
    IDErrors = {},
    runningID = null,
    running = false;
  /* queue functions */
  linnworks.setCallback(processQueue);

  function addToQueue(id) {
    queue.push(id);
    processQueue();
  }

  function processQueue() {
    if (!running && queue.length != 0) {
      runningID = queue.shift();
      running = true;
      if (IDErrors.hasOwnProperty(runningID)) {
        errorHandler.trigger("error", "already tried getting order width ID " + runningID + " once");
      } else {
        getOrder(runningID, getLinnworksData);
      }

    } else if (queue.length == 0) { // finish  // checkOrderErrors
      console.log("FINISHED QUEUE");
      errorHandler.trigger("dumpLog", dumpLogCallback);
      errorHandler.trigger("checkOrderErrors", function(ret) {
        if (ret == false) {
          errorHandler.trigger("dumpIDErrors");
        } else {
          ret.forEach(function(id, i) {
            if (!IDErrors.hasOwnProperty(id)) {
              addToQueue(id);
            }
          });
        }
      });

    }
  }

  errorHandler.on("error", function(err) {
    errorHandler.pushError(runningID, err);
    IDErrors[runningID] = true;
    running = false;
    processQueue();
  });
  errorHandler.on("addToLog", function(err){
    errorHandler.pushError(runningID, err);
  })

  errorHandler.on("dumpIDErrors", function() {
    IDErrors = {};
  })


  function dumpLogCallback() {
    errorHandler.trigger("checkOrderErrors", function(ret) {
      if (Array.isArray(ret)) {
        setTimeout(function(argument) {
          // body...
          ret.forEach(function(id, i) {
            if (!IDErrors.hasOwnProperty(id)) {
              IDErrors[id] = true;
              addToQueue(id);
            } else {
              errorHandler.pushError(id, "Already failed once")
            }

          });
        }, 600000); // waits 10 minutes;
      } else {
        IDErrors = {};
      }
    });
  }



  function getOrder(orderID, callback) {
    var body = '<?xml version="1.0" encoding="utf-8"?>' +
      '<soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">' +
      '<soap12:Body>' +
      '<Order_Retrieve xmlns="http://tempuri.org/CatalystDeveloperService/CatalystCRMWebservice">' +
      '<username>james@websitesuccess.co.uk</username>' +
      '<password>Fuckyou69</password>' +
      '<siteId>2190918</siteId>' +
      '<orderId>' + orderID + '</orderId>' +
      '</Order_Retrieve>' +
      '</soap12:Body>' +
      '</soap12:Envelope>';

    linnworks.setBCOrder(orderID);

    console.log("called function");

    var options = {
      url: 'https://handprinted.worldsecuresystems.com/catalystwebservice/catalystcrmwebservice.asmx',
      method: "POST",
      headers: {
        'Content-Type': 'application/soap+xml',
        'Content-Length': Buffer.byteLength(body)
      },
      body: body
    }

    request(options, function(error, response, body) {

      if (!error && response.statusCode == 200) {
        parser.parseString(body, function(err, result) {
          var data = formatBCData(result);
          if (result) {
            if (callback) {

              callback(data)
            } else {
              console.log(JSON.stringify(data));
            }

          } else {
            console.log("error");
          }
        });
      }
    });
  }

  function formatBCData(bcData) {
    var bc = bcData["soap:Envelope"]["soap:Body"][0]["Order_RetrieveResponse"][0]["Order_RetrieveResult"][0],
      shippingAttentionInfo = {};
    bc.shippingAttention[0].split("&amp;").forEach(function(val) {
      var arr = val.split("=");
      shippingAttentionInfo[arr[0]] = arr[1];
    });
    var products = bc.products[0].Product.map(function(val, i) {
        var obj = {
          productCode: val.productCode[0],
          description: val.productDescription[0],
          productId: val.productId[0],
          name: val.productName[0],
          quantity: val.units[0]
        }
        return obj;
      }),
      shipping = {
        amount: bc.shippingAmount[0],
        attention: bc.shippingAttention[0],
        instructions: bc.shippingInstructions[0],
        optionID: bc.shippingOptionId[0],
        taxRate: bc.shippingTaxRate[0]
      },
      customer = {
        address: {},
        info: {
          firstName: shippingAttentionInfo.firstName,
          lastName: shippingAttentionInfo.lastName,
          email: shippingAttentionInfo.email,
          phone: shippingAttentionInfo.phone
        }
      }
    bc.addresses[0].Address.forEach(function(val) {

      var isBilling = val.addressTypeID == 8 ? true : false,
        obj = {
          addressLine1: val.addressLine1,
          addressLine2: val.addressLine2,
          city: val.city,
          countryCode: val.countryCode,
          county: val.state,
          postcode: val.zipcode
        }

      if (isBilling) {
        customer.address.billing = obj;
      } else {
        customer.address.shipping = obj;
      }

    });

    return {
      products: products,
      shipping: shipping,
      customer: customer
    }
  }

  function getLinnworksData(bcData) {
    console.log("Called getLinnworksData");
    linnworks.getAuthenticationToken(function(token) {
      linnworks.inventory.getUsefulStuff(token, function(data) {
        //    console.log(data);
        //  console.log(typeof data);
        //  console.log(data);
        // data is from linnworks
        // bcData is from business catalyst

        //var ret = //linnworksSortData.betterSKUSearch(data, bcData.product.productCode, bcData.product.)
        var products = []

        bcData.products.forEach(function(val, i) {
          var prodTitle = val.name + " " + val.description;
          var ret = linnworksSortData.betterSKUSearch(data, val.productCode, prodTitle),
            ret2 = false,
            ret3 = false;

          //  console.log(ret);
          if (ret.code == 0) { // found no matches --
            ret2 = linnworksSortData.filterByName(data, prodTitle);
            //  console.log("found no matches at ret")
          } else if (ret.code == 2) { // found more than 1 match, will filter this array
            //  console.log("found more than 1 match at ret")
            ret2 = linnworksSortData.filterByName(ret.data, prodTitle);
          } else if (ret.code == 1) { // found it
            products.push(ret.data[0]);
            products[products.length -1].bcQuantity = parseInt(val.quantity);
            //  console.log("found a match at ret")
          }


          if (ret2) {

            if (ret.code == 0 && ret2.code == 0) { // no matches at all
              var obj = {
                  sku: val.productCode,
                  title: prodTitle,
                  error: "no matches at all"
                },
                objStr = "UNKNOWN";
              try {
                objStr = JSON.stringify(obj);
              } catch (e) {}
              errorHandler.trigger("error", "failed @getLinnworksData - " + objStr);
              // throw error
            } else if (ret.code == 2 && ret2.code == 0) { // refilter by name
              //    console.log("filter by name at ret2")
              ret3 = linnworksSortData.filterByName(data, prodTitle);
            } else if (ret2.code == 1) { // found a match
              products.push(ret2.data[0]);
              products[products.length -1].bcQuantity = parseInt(val.quantity);
              //  console.log("found a match at ret2", ret2.data)
            } else if (ret2.code == 2) {
              var obj = {
                  sku: val.productCode,
                  title: prodTitle,
                  error: "too many matches"
                },
                objStr = "UNKNOWN";
              try {
                objStr = JSON.stringify(obj);
              } catch (e) {}
              errorHandler.trigger("error", "failed @getLinnworksData - " + objStr);
            }


          }

          if (ret3) {

            if (ret3.code == 0) { // found no matches
              //  console.log("no matches at ret3")
              var obj = {
                  sku: val.productCode,
                  title: prodTitle,
                  error: "no matches at all"
                },
                objStr = "UNKNOWN";
              try {
                objStr = JSON.stringify(obj);
              } catch (e) {}
              errorHandler.trigger("error", "failed @getLinnworksData - " + objStr);


              // throw error
            } else if (ret3.code == 2) { // more than 1 match
              // throw error
              //    console.log("more than 1 match at ret3");
              var obj = {
                  sku: val.productCode,
                  title: prodTitle,
                  error: "more than 1 match for both areas",
                  data: ret3.data
                },
                objStr = "UNKNOWN";
              try {
                objStr = JSON.stringify(obj);
              } catch (e) {}
              errorHandler.trigger("error", "failed @getLinnworksData - " + objStr);
            } else if (ret3.code == 1) { // found a match
              products.push(ret3.data[0]);
              products[products.length -1].bcQuantity = parseInt(val.quantity);
              //  console.log("found a match at ret3")
            }
          }


        });

        var orderCreationData = {
          bcData: bcData,
          linnworksData: products
        }
      /*  var orderWeight = 0;
            nextDayShipping = parseFloat(orderCreationData.bcData.shipping.amount) == 7.5 ? true : false,
            countryCode = orderCreationData.bcData.customer.address.shipping.countryCode[0]


        orderCreationData.linnworksData.forEach(function(prod) {
          orderWeight+= prod.Weight * prod.bcQuantity;
        });
        var ret = shippingOptions.getShippingOption(orderWeight, countryCode, nextDayShipping);
        console.log(ret, "@shippingOption");*/
        //console.log(JSON.stringify(orderCreationData));
     linnworks.order.betterOrderCreation(token, orderCreationData, function(data) {});
        //bcData //products



      });

    });
  }




  return {
    addToQueue: addToQueue,
    processQueue: processQueue
  }




})()
