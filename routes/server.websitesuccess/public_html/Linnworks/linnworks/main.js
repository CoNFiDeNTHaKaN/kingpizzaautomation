var request = require('request'),
  errorHandler = require("../errorHandler.js"), //,
  productTaxCheck = require("./products.js").check,
  shippingOptions = require("./shippingOptions.js");

module.exports = (function() {

  var userName = "shirley@handprinted.net",
    password = "H0rt0n";

  var orderCallback,
    bcOrderID;

  function getAuthenticationToken(callback) {

    function getID() {


      //https://api.linnworks.net//api/Auth/MultiLogin


      var options = {
        url: 'https://api.linnworks.net/api/Auth/MultiLogin?userName=' + userName + '&password=' + password,
        method: "GET"
      }



      request(options, function(error, response, body) {
        if (!error && response.statusCode == 200) {
          var json = JSON.parse(body);
          //   console.log();
          getToken(json[0].Id);
        } else {
          var errorMessage = "UNKNOWN";
          try {
            errorMessage = JSON.stringify(response);
          } catch (e) {}
          errorHandler.trigger("error", "failed @getID - " + errorMessage);
        }
      })



    }


    function getToken(id) {

      if (id != undefined) {
        var body = 'userName=' + userName + '&password=' + password + '&userId=' + id;
        var options = {
          url: "https://api.linnworks.net//api/Auth/Authorize",
          body: body,
          method: "POST",
          headers: {
            "content-type": "application/x-www-form-urlencoded"
          }
        }

        request(options, function(error, response, body) {
          //   console.log(error, response, body)
          if (response) {
            if (!error && response.statusCode == 200) {
              //getToken(body.Id);
              if (callback) {
                //callback(id);
                var json = JSON.parse(body);
                callback(json.Token);
              }

            } else {
              var errorMessage = "UNKNOWN";
              try {
                errorMessage = JSON.stringify(response);
              } catch (e) {}
              errorHandler.trigger("error", "failed @getToken - " + errorMessage);
            }
          } else {
            errorHandler.trigger("error", "failed @getToken - " + "no response");
          }
        })

      } else {
        console.log("no id supplied", id)
          //  consol
      }


    }

    getID();
    //  errorHandler.trigger("error", "test error");


  }

  var useful = {
    getShippingMethods: function(obj) {
      //https://eu.linnworks.net/api/Orders/GetShippingMethods
      var token = obj.token;
      var options = {
        url: "https://eu1.linnworks.net//api/Orders/GetShippingMethods?token=" + token,
        method: "POST",
        headers: {
          "content-type": "application/x-www-form-urlencoded"
        }
      }
      request(options, function(error, response, body) {

        console.log(error, response)

      });


    }
  }

  var inventory = {
      getItemChannelSKU: function(obj) {
        var token = obj.token,
          itemID = obj.itemID,
          callback = obj.callback;
        var body = "inventoryItemId=" + itemID;
        console.log(body);
        var options = {
          url: "https://eu1.linnworks.net//api/Inventory/GetInventoryItemChannelSKUs?token=" + token,
          method: "POST",
          headers: {
            "content-type": "application/x-www-form-urlencoded"
          },
          body: body
        }
        request(options, function(error, response, body) {

          if (response) {
            if (!error && response.statusCode == 200) {
              if (callback) {

                var body = JSON.parse(body),
                  useful;

                //  console.log(body, typeof body);

                if (Array.isArray(body)) {
                  //    var useful
                  body.forEach(function(val) {
                    if (val.Source == "WEBSITE") {
                      useful = val;
                    }
                  });
                } else {
                  useful = body;
                }
                var obj = {
                  sku: useful.SKU,
                  skuID: useful.ChannelSKURowId
                }
                callback(null, obj);
              } else {
                console.log(body)
              }
            } else {
              /*  if (callback) {
                  callback([error, JSON.stringify(response)], null)
                } else {
                  console.log("error", error, JSON.stringify(response));
                }*/
              var errorMessage = "UNKNOWN";
              try {
                errorMessage = JSON.stringify(response);
              } catch (e) {}
              errorHandler.trigger("error", "failed @getItemChannelSKU - " + errorMessage);
            }
          } else {
            errorHandler.trigger("error", "failed @getItemChannelSKU - " + "no response");
          }

        });
      },
      getViews: function(token) {
        var options = {
          url: "https://eu1.linnworks.net//api/Inventory/GetInventoryViews?token=" + token,
          method: "POST",
          headers: {
            "content-type": "application/x-www-form-urlencoded"
          }
        }
        request(options, function(error, response, body) {

          console.log(error, body)

        });


      },
      getItems: function(token, obj, callback) {
        var error = false;
        var obj = obj || "{}";

        try {
          obj = JSON.parse(obj)
        } catch (e) {
          error = true;
          console.log(e);
        }

        if (!error) {

          var view = obj.view || {};
          var itemsCount = obj.itemsCount || 1000;
          var stockLocationIds = obj.stockLocationIds || "00000000-0000-0000-0000-000000000000";
          view = JSON.stringify(view);

          var body = "view=" + view + "&stockLocationIds=" + stockLocationIds + "&startIndex=0&itemsCount=" + itemsCount;
          var options = {
            url: "https://eu1.linnworks.net//api/Inventory/GetInventoryItems?token=" + token,
            method: "POST",
            headers: {
              "content-type": "application/x-www-form-urlencoded"
            },
            body: body
          }
          request(options, function(error, response, body) {
            if (response) {


              //  console.log(error, body, response);
              if (response.statusCode == 200) {
                //  console.log("done");
                if (callback) {
                  callback(body);
                } else {
                  console.log(body)
                }
              } else {
                var errorMessage = "UNKNOWN";
                try {
                  errorMessage = JSON.stringify(response);
                } catch (e) {}
                errorHandler.trigger("error", "failed @getItems - " + errorMessage);
              }
            } else {
              console.log(error, response, body);
              errorHandler.trigger("error", "failed @getItems - " + "no response");
            }

          });

        } else {
          console.log("Error");
        }
      },
      getItemTitles: function(token, itemID) {
        var itemID = itemID || "4e9ed35b-86d7-4837-a9c0-fa60ddd04acb";
        var body = "inventoryItemId=" + itemID;
        var options = {
          url: "https://eu1.linnworks.net//api/Inventory/GetInventoryItemTitles?token=" + token,
          method: "POST",
          headers: {
            "content-type": "application/x-www-form-urlencoded"
          },
          body: body
        }
        request(options, function(error, response, body) {

          console.log(error, body)

        });


      },
      getItemById: function(token, itemID,callback, skipError) {
        var itemID = itemID || "4e9ed35b-86d7-4837-a9c0-fa60ddd04acb";
        var body = "id=" + itemID;
        var options = {
          url: "https://eu1.linnworks.net//api/Inventory/GetInventoryItemById?token=" + token,
          method: "POST",
          headers: {
            "content-type": "application/x-www-form-urlencoded"
          },
          body: body
        }
        request(options, function(error, response, body) {

          //  console.log(error, body)
          if (response) {


            if (response.statusCode == 200) {
              if (callback) {
                callback(null, body);
              } else {
                console.log(body)
              }
            } else {

              /*  if (callback) {
                  callback(error)
                } else {
                  console.log(error);
                }*/

              var errorMessage = "UNKNOWN";
              try {
                errorMessage = JSON.stringify(response);
              } catch (e) {}
              errorHandler.trigger("error", "failed @getItemById - " + errorMessage);
            }
          } else {
            console.log(error, response, body);
            if (!skipError) {
              errorHandler.trigger("error", "failed @getItemById - " + "no response");
            } else {
              callback(error);
            }
          }

        });
      },
      getUsefulStuff: function(token, callback) { // get items is fucking shit
        //console.log(inventory);
        var getNoItemsObj = '{"itemsCount":1}';
        //  getIDsObj
        function getNoItemsFn(data) {
          //console.log(typeof data);
          var data = JSON.parse(data);
          var getIDsObj = '{"itemsCount":' + data.TotalItems + '}';

          function getIDsFn(data) {
            var data = JSON.parse(data);
            var ids = data.Items.map(function(val, i) {
              return val.Id;
            });
            //console.log(ids);
            new getUsefulData(ids);

          }
          inventory.getItems(token, getIDsObj, getIDsFn)
        }

        function getUsefulData(idArray) {
          var _ = this;
          _.currentConnections = 0;
          _.connectionLimit = 10;
          _.queue = idArray;
          _.data = [];
          _.init();
        }
        getUsefulData.prototype.processQueue = function(fromRequest) {
          var _ = this;
          var queueLength = _.queue.length;
          if (fromRequest) {
            _.currentConnections--;
            if (queueLength == 0 && _.currentConnections == 0) {
              console.log("finished requests");
              if (callback) {
                callback(_.data);
              } else {

                console.log("---- data below ----");
                console.log(_.data);
              }

            }
          }

          if (_.currentConnections < _.connectionLimit) {
            if (queueLength != 0) {
              _.currentConnections++;
              _.callrequest(_.queue.shift());
            }
          }
        }
        getUsefulData.prototype.callrequest = function(id, lastCallError) {
          var _ = this;
          inventory.getItemById(token, id, function(err, res) {
            if (err && !lastCallError) {
              _.callrequest(id, true);
            } else if (res) {
              _.data.push(JSON.parse(res));
            } else {
              _.data.push({
                "id": id,
                "error": err
              });
            }
            _.processQueue(true);
          }, true);


        }
        getUsefulData.prototype.init = function() {
          var _ = this;
          for (var i = 0, len = _.connectionLimit; i < len; i++) {
            _.processQueue();
          }
          //_.processQueue()
        }





        inventory.getItems(token, getNoItemsObj, getNoItemsFn)
      }




    }
    //  }
  var stock = {
    getLocations: function(token) {
      //  getAuthenticationToken(callback);
      //  function callback(){
      var options = {
        url: "https://eu1.linnworks.net//api/Inventory/GetStockLocations?token=" + token,
        method: "POST",
        headers: {
          "content-type": "application/x-www-form-urlencoded"
        }
      }
      request(options, function(error, response, body) {

        console.log(error, body)

      });
      //  }
    },
    getItems: function(token, obj) {
      //  getAuthenticationToken(callback);
      //  function callback(){
      var error = false;
      try {
        var obj = JSON.parse(obj);
      } catch (e) {
        error = true
      }
      if (!error) {
        console.log(obj.locationId);
        var locationId = obj.locationId || "00000000-0000-0000-0000-000000000000";
        var keyWord = obj.keyWord || "";
        var body = "keyWord=" + keyWord + "&locationId=" + locationId + "&entriesPerPage=10&pageNumber=0&excludeComposites=true";
        var options = {
          url: "https://eu1.linnworks.net//api/Stock/GetStockItems?token=" + token,
          body: body,
          method: "POST",
          headers: {
            "content-type": "application/x-www-form-urlencoded"
          }
        }
        request(options, function(error, response, body) {
          //   console.log(error, response, body)
          console.log(error, body)

        })
      } else {
        console.log("error");
      }
      //}
    },
    SKUExists: function(token) {
      var body = "SKU=BA22-White";
      var options = {
        url: "https://eu1.linnworks.net//api/Stock/SKUExists?token=" + token,
        body: body,
        method: "POST",
        headers: {
          "content-type": "application/x-www-form-urlencoded"
        }
      }
      request(options, function(error, response, body) {

        console.log(error, body)

      });
    }
  }

  var user = {
    getLocationID: function(token) {
      //  var body = "keyWord=B&locationId=00000000-0000-0000-0000-000000000000&entriesPerPage=10&pageNumber=0&excludeComposites=false" ;
      var options = {
        url: "https://eu1.linnworks.net//api/Orders/GetUserLocationId?token=" + token,
        //  body:body,
        method: "POST",
        headers: {
          "content-type": "application/x-www-form-urlencoded"
        }
      }
      request(options, function(error, response, body) {
        //   console.log(error, response, body)
        console.log(error, body)

      })
    }
  }

  var order = {
    setOrderTotalsInfo: function(obj) {
      var token = obj.token,
        orderID = obj.orderID,
        tax = obj.tax,
        subTotal = obj.subTotal,
        overallTotal = obj.overallTotal,
        shippingCost = obj.shippingCost,
        callback = obj.callback,
       body = "orderId=" + orderID + '&info={"Tax":' + tax + ',"Currency":"GBP", "SubTotal":' + subTotal + ',"TotalCharge":' + overallTotal + ',"CountryTaxRate":20}',
      //  body = "orderId=" + orderID + '&info={"Currency":"GBP","CountryTaxRate":20}',
        options = {
          url: "https://eu1.linnworks.net//api/Orders/SetOrderTotalsInfo?token=" + token,
          body: body,
          method: "POST",
          headers: {
            "content-type": "application/x-www-form-urlencoded"
          }
        }

      request(options, function(error, response, body) {
        if (response) {
          if (!error && response.statusCode == 204) {
            if (callback) {
              callback(null, body)
            } else {
              console.log(body);
            }
          } else {
            var errorMessage = "UNKNOWN";
            try {
              errorMessage = JSON.stringify(response);
            } catch (e) {}
            errorHandler.trigger("error", "failed @setOrderTotalInfo - " + errorMessage);
          }
        } else {
          errorHandler.trigger("error", "failed @setOrderTotalInfo - " + "no response");
        }

      });


    },
    setShippingInfo: function(obj) {
      var token = obj.token,
        orderID = obj.orderID,
        postage = obj.postage,
        vendor = obj.vendor,
        postalID = obj.postalID,
        postalServiceName = obj.postalServiceName,
        callback = obj.callback,
        weight = obj.weight;
      var body = "orderId=" + orderID + '&info={"Vendor":"' + vendor +'","PostalServiceId":"' + postalID + '","PostalServiceName":"' + postalServiceName + '","TotalWeight":' + weight +',"ItemWeight":' + weight + ',"PackageCategoryId":"00000000-0000-0000-0000-000000000000","PackageCategory":"00000000-0000-0000-0000-000000000000","PackageTypeId":"00000000-0000-0000-0000-000000000000","PackageType":"Website","PostageCost":' + postage + ',"PostageCostExTax":' + ((postage / 5) * 4) + ',"TrackingNumber":"null","ManualAdjust":true}';
      console.log(body);
      var options = {
        url: "https://eu1.linnworks.net//api/Orders/SetOrderShippingInfo?token=" + token,
        body: body,
        method: "POST",
        headers: {
          "content-type": "application/x-www-form-urlencoded"
        }
      }
      request(options, function(error, response, body) {
        //console.log(error, body)
        if (response) {
          if (!error && response.statusCode == 200) {
            if (callback) {
              callback(null, body)
            } else {
              console.log(body);
            }
          } else {
            var errorMessage = "UNKNOWN";
            try {
              errorMessage = JSON.stringify(response);
            } catch (e) {}
            errorHandler.trigger("error", "failed @setShippingInfo - " + errorMessage);
          }
        } else {
          errorHandler.trigger("error", "failed @setShippingInfo - " + "no response");
        }

      });
    },
    setGeneralInfo: function(obj) {
      var token = obj.token,
        orderID = obj.orderID,
        callback = obj.callback,
        date = new Date().toISOString();
      //  date = "2015-11-19T00:00:00.000Z";
      body = "orderId=" + orderID + '&info={"Status":1,"ReceivedDate":"' + date + '","ReferenceNum":"WEBSITE","ExternalReferenceNum":"' + bcOrderID + '","Source":"NEW-WEBSITE","SubSource":"NEW-WEBSITE"}&wasDraft=false',
        options = {
          url: "https://eu1.linnworks.net/api/Orders/SetOrderGeneralInfo?token=" + token,
          body: body,
          method: "POST",
          headers: {
            "content-type": "application/x-www-form-urlencoded"
          }
        };



      request(options, function(error, response, body) {
        if (response) {

          if (!error && response.statusCode == 204) {
            if (callback) {
              callback(null, body)
            } else {
              console.log(body);
            }
          } else {
            var errorMessage = "UNKNOWN";
            try {
              errorMessage = JSON.stringify(response);
            } catch (e) {}
            errorHandler.trigger("error", "failed @setGeneralInfo - " + errorMessage);
          }
        } else {
          errorHandler.trigger("error", "failed @setGeneralInfo - " + "no response");
        }

      });


    },
    getAllOpenOrders: function(token) {
      var body = 'filters={}&sorting=[{"FieldCode":0,"Direction":0},{"FieldCode":0,"Direction":0}]&fulfilmentCenter=00000000-0000-0000-0000-000000000000&additionalFilter=a'
      var options = {
        url: "https://eu1.linnworks.net//api/Orders/GetAllOpenOrders?token=" + token,
        //  body:body,
        method: "POST",
        headers: {
          "content-type": "application/x-www-form-urlencoded"
        },
        body: body
      }
      request(options, function(error, response, body) {
        //   console.log(error, response, body)

        if (!error && response.statusCode == 200) {

          /*if (callback) {
            callback(body)
          }*/ //else {
          console.log(body, typeof body);
          //}
        } else {
          console.log("error at get order", error, body)
        }

      })
    },
    getOrder: function(token) {
      var body = "orderId=8bb5994c-ca45-4fc7-a625-46ff54ae787d&fulfilmentLocationId=00000000-0000-0000-0000-000000000000&loadItems=true&loadAdditionalInfo=true"
      var options = {
        url: "https://eu1.linnworks.net//api/Orders/GetOrder?token=" + token,
        body: body,
        method: "POST",
        headers: {
          "content-type": "application/x-www-form-urlencoded"
        },
        body: body
      }
      request(options, function(error, response, body) {
        //   console.log(error, response, body)
        if (!error && response.statusCode == 200) {

          //  if (callback) {
          //  callback(body)
          //  } else {
          console.log(body, typeof body);
          //  }
        } else {
          console.log("error at get order")
        }

      })
    },
    createNew: function(token, fulfilmentCenter, callback) {
      var fulfilmentCenter = fulfilmentCenter || "00000000-0000-0000-0000-000000000000";
      var body = "fulfilmentCenter=" + fulfilmentCenter;
      var options = {
        url: "https://eu1.linnworks.net//api/Orders/CreateNewOrder?token=" + token,
        //  body:body,
        method: "POST",
        headers: {
          "content-type": "application/x-www-form-urlencoded"
        },
        body: body
      }
      request(options, function(error, response, body) {
        //   console.log(error, response, body)
        if (response) {
          if (!error && response.statusCode == 200) {
            var body = JSON.parse(body);
            body = body.OrderId;

            if (callback) {
              callback(body)
            } else {
              console.log(body, typeof body, "logging body");
            }
          } else {
            var errorMessage = "UNKNOWN";
            try {
              errorMessage = JSON.stringify(response);
            } catch (e) {}
            errorHandler.trigger("error", "failed @createNew - " + errorMessage);
          }
        } else {
          errorHandler.trigger("error", "failed @createNew - " + "no response");
        }

      })
    },
    addOrderItem: function(data) {
      var token = data.token,
        obj = data.data,
        callback = data.callback;

      /*  var body = {
          "orderId": obj.orderID,
          "itemId": obj.StockItemId,
          "channelSKU": obj.channelSKU,
          "fulfilmentCenter": '00000000-0000-0000-0000-000000000000',
          "quantity": obj.quantity
        }*/
      var body = "orderId=" + obj.orderID + "&itemId=" + obj.itemID + "&channelSKU=" + obj.channelSKU + "&fulfilmentCenter=00000000-0000-0000-0000-000000000000" + "&quantity=" + obj.quantity;
      //body = JSON.stringify(body);

      var options = {
        url: "https://eu1.linnworks.net//api/Orders/AddOrderItem?token=" + token,
        //  body:body,
        method: "POST",
        headers: {
          //  "content-type": "application/json"
          "content-type": "application/x-www-form-urlencoded"
        },
        body: body
      }
      request(options, function(error, response, body) {
        //   console.log(error, response, body)
        if (response) {
          if (!error && response.statusCode == 200) {

            if (callback) {

              callback(null, body)
            } else {
              console.log("no callback")
              console.log(body);
            }
          } else {
            //console.log("error at AddOrderItem", JSON.stringify(error));
            var errorMessage = "UNKNOWN";
            try {
              errorMessage = JSON.stringify(response);
            } catch (e) {}
            errorHandler.trigger("error", "failed @addOrderItem - " + errorMessage);
          }
          //    console.log(error, body)
        } else {
          errorHandler.trigger("error", "failed @addOrderItem - " + "no response");
        }
      });
    },
    lockOrder: function(token, obj, callback) {
      if (typeof obj == "string") {
        obj = JSON.parse(obj);
      }
      //console.log(typeof obj);

      var body = "orderIds=" + JSON.stringify(obj.id) + "&lockOrder=" + obj.lockOrder;

      var options = {
        url: "https://eu1.linnworks.net//api/Orders/LockOrder?token=" + token,
        //  body:body,
        method: "POST",
        headers: {
          "content-type": "application/x-www-form-urlencoded"
        },
        body: body
      }
      request(options, function(error, response, body) {
        //   console.log(error, response, body)
        if (response) {
          if (!error && response.statusCode == 200) {
            if (callback) {
              callback(body);
            } else {
              console.log(body)
            }
          } else {
            var errorMessage = "UNKNOWN";
            try {
              errorMessage = JSON.stringify(response);
            } catch (e) {}
            errorHandler.trigger("error", "failed @lockOrder - " + errorMessage);
          }
        } else {
          errorHandler.trigger("error", "failed @lockOrder - " + "no response");
        }

      });


    },
    setCustomerInfo: function(token, obj, callback) {

      var body = "orderId=" + obj.orderID + "&info=" + JSON.stringify(obj.info);
      var options = {
        url: "https://eu1.linnworks.net//api/Orders/SetOrderCustomerInfo?token=" + token,
        method: "POST",
        headers: {
          "content-type": "application/x-www-form-urlencoded"
        },
        body: body
      }
      request(options, function(error, response, body) {
        if (response) {
          if (!error && response.statusCode == 200) {
            if (callback) {
              callback(null, body);
            } else {
              console.log(body)
            }
          } else {
            /*
              if (callback) {
                callback(error);
              }
              else{
                var errorMessage ="UNKNOWN";
                try{
                  errorMessage =JSON.stringify(response);
                }
                catch(e){}
                errorHandler.trigger("error", "failed @setCustomerInfo2? - " + errorMessage);
              }*/

            // currently bug with setCustomerInfo need to contact linnworks {"Code":"-","Message":"Subquery returned more than 1 value. This is not permitted when the subquery follows =, !=, <, <= , >, >= or when the subquery is used as an expression."}' } {"Code":"-","Message":"Subquery returned more than 1 value. This is not permitted when the subquery follows =, !=, <, <= , >, >= or when the subquery is used as an expression."}

            var json = "NULL";
            try {
              json = JSON.stringify(response)
            } catch (e) {}
            errorHandler.trigger("addToLog", "error with setCustomerInfo " + json);
            if (callback) {
              callback(error);
            }
          }
        } else {
          errorHandler.trigger("error", "failed @setCustomerInfo - " + "no response");
        }

      });

    },
    completeOrder: function(token, id, callback) {
      var body = "orderId=" + id;
      var options = {
        url: "https://eu1.linnworks.net//api/Orders/CompleteOrder?token=" + token,
        //  body:body,
        method: "POST",
        headers: {
          "content-type": "application/x-www-form-urlencoded"
        },
        body: body
      }
      request(options, function(error, response, body) {
        //   console.log(error, response, body)
        if (response) {
          if (!error && response.statusCode == 200) {
            if (callback) {
              callback(body);
            } else {
              console.log(body)
            }
          } else {
            var errorMessage = "UNKNOWN";
            try {
              errorMessage = JSON.stringify(response);
            } catch (e) {}
            errorHandler.trigger("error", "failed @completeOrder - " + errorMessage);
          }

        } else {
          errorHandler.trigger("error", "failed @completeOrder - " + "no response");
        }
      });

    },
    betterOrderCreation: function(token, obj, callback) {
      if (typeof obj == "string") {
        obj = JSON.parse(obj);
      }

      // get channel sku stuff
      //    console.log(obj.linnworksData, typeof obj.linnworksData)
      var ids = obj.linnworksData.map(function(val, i) {

        return {
          itemID: val.StockItemId,
          token: token
        }
      });
      //  console.log(ids, "logging ids");
      var channelSKU = new RequestQueueSystem({
        queueItems: ids,
        callback: onRecievedSKUs,
        returnIDS: function(id, data) {
          var data = data || {
              itemID: "null"
            }
            //  console.log(data, "logging data", typeof data);
          return {
            key: data.itemID,
            data: id
          }
        }
      });

      channelSKU.init();

      function onRecievedSKUs(data) {
        obj.linnworksData.forEach(function(val, i) {
          val.channelSKU = data[channelSKU.ids[val.StockItemId]].skuID;
        });
      }
      order.createNew(token, null, function(data) {

        var orderID = data;
        var orderCreationData = obj.linnworksData.map(function(val, i) {
          var linnworksData = val,
            bcData = obj.bcData.products[i];

          return {
            token: token,
            data: {
              orderID: orderID,
              itemID: linnworksData.StockItemId,
              channelSKU: linnworksData.channelSKU,
              quantity: bcData.quantity
            }
          }

        });



        var addOrderItem = new RequestQueueSystem({
          queueItems: orderCreationData,
          callback: addedOrderItems,
          fn: order.addOrderItem
        });
        addOrderItem.init();

        function addedOrderItems(data) {

          // works out costs && weight or order
          // --------------------------------------------------------------------------------------
          var subTotal = 0,
            tax = 0,
            shippingCost = parseFloat(obj.bcData.shipping.amount),
            countryCode = obj.bcData.customer.address.shipping.countryCode[0],
            overallTotal = 0,
            nextDayShipping = parseFloat(obj.bcData.shipping.amount) == 7.5 ? true : false,
            orderWeight = 0;





          obj.linnworksData.forEach(function(prod, i) {

            var productPrices = (Math.round((prod.RetailPrice * 1.2) * 100) / 100) * prod.bcQuantity;
            //  subTotal += (prod.RetailPrice * prod.bcQuantity);
            subTotal += productPrices / 1.2;
            orderWeight += prod.Weight * prod.bcQuantity;


            if (productTaxCheck(prod.Itemtitle) == false) {
              tax += Math.round((productPrices / 12 * 2) * 100) / 100;
            }
          });
          overallTotal = subTotal + tax + shippingCost;

          var shippingOptionsRet = shippingOptions.getShippingOption(orderWeight, countryCode, nextDayShipping);



          // --------------------------------------------------------------------------------------



          var customerInfo = obj.bcData.shipping.attention;
          var error = customerInfo == null ? true : false,
            customerObj = {};
          customerInfo = customerInfo.split("&amp;");
          customerInfo.forEach(function(val, i) {
            var arr = val.split("="),
              key = arr[0],
              value = arr[1];
            customerObj[key] = value;
          });
          customerInfo = customerObj;

          var country = "United Kingdom",
            countryID = "445c01b5-e48c-4002-aef5-3065a4ff162b";

          if (obj.bcData.customer.address.shipping.countryCode[0] != "GB") {
            console.log("not uk");
            country = "Unknown";
            countryID = "00000000-0000-0000-0000-000000000000";
          }
          var customerData = {
            orderID: orderID,
            info: {
              ChannelBuyerName: "",
              Address: {
                EmailAddress: customerInfo.email,
                Address1: obj.bcData.customer.address.shipping.addressLine1[0],
                Address2: obj.bcData.customer.address.shipping.addressLine2[0],
                Address3: "",
                Town: obj.bcData.customer.address.shipping.city[0],
                Region: obj.bcData.customer.address.shipping.county[0],
                PostCode: obj.bcData.customer.address.shipping.postcode[0],
                Country: country,
                FullName: customerInfo.firstName + " " + customerInfo.lastName,
                Company: "",
                PhoneNumber: customerInfo.phone,
                CountryId: countryID
              }
            }
          }

          function setGeneralInfoCallback() {
            console.log("fully complered order??");
          }

          function shippingCallback() {
            //order.setGeneralInfo({token:token,callabck:setGeneralInfoCallback, orderID:orderID})
              order.setCustomerInfo(token, customerData, function(err, res) {
                order.completeOrder(token, orderID, function(data) {
                  console.log("complered order");
                  //  order.processQueue();

                  //  order.setGeneralInfo({token:token,callabck:setGeneralInfoCallback, orderID:orderID}) //---------         RECIEVING ERROR '{"Code":"-","Message":"SqlDateTime overflow. Must be between 1/1/1753 12:00:00 AM and 12/31/9999 11:59:59 PM."}'

                  order.setOrderTotalsInfo({
                    token: token,
                    orderID: orderID,
                    tax: tax,
                    subTotal: subTotal,
                    overallTotal: overallTotal,
                    shippingCost: shippingCost,
                    callback: function() {
                      order.setGeneralInfo({
                        token: token,
                        orderID: orderID,
                        callback: function() {
                          orderCallback();
                        }
                      })

                    }
                  });




                });
              });


           /*order.setCustomerInfo(token, customerData, function(err, res) { // this returns 0 for totals

              order.setOrderTotalsInfo({
                token: token,
                orderID: orderID,
                tax: tax,
                subTotal: subTotal,
                overallTotal: overallTotal,
                shippingCost: shippingCost,
                callback: function() {
                  order.setGeneralInfo({
                    token: token,
                    orderID: orderID,
                    callback: function() {
                      order.completeOrder(token, orderID, function(data) {
                        orderCallback();
                      });
                    }
                  })

                }
              });

            });*/



/*
            order.setCustomerInfo(token, customerData, function(err, res) { // doesn't include any product tax
                      order.setGeneralInfo({
                        token: token,
                        orderID: orderID,
                        callback: function() {
                          order.completeOrder(token, orderID, function(data) {
                            orderCallback();
                          });
                        }
                      })



                });
*/


          }
      //    shippingOptionsRet
          order.setShippingInfo({
            token: token,
            callback: shippingCallback,
            orderID: orderID,
            postage: parseFloat(obj.bcData.shipping.amount),
            vendor:shippingOptionsRet[0].vendor,
            postalID:shippingOptionsRet[0].id,
            postalServiceName:shippingOptionsRet[0].shippingName,
            weight:orderWeight
          })

//      vendor = obj.vendor,
    //  postalID = obj.postalID,
    //  postalServiceName = obj.postalServiceName,


        }


      });

    }
  }


  function RequestQueueSystem(options) {
    var _ = this;
    //  callback = callback || function(data){console.log(data)}
    _.defaults = {
      maxConnections: 10,
      fn: inventory.getItemChannelSKU,
      args: null,
      timeoutLength: 0,
      callback: function(data) {
        console.log(data)
      },
      returnIDS: function(id, data) {
        return {
          data: data,
          key: id
        }
      },
      queueItems: []
    }
    _.options = _.mergeOptions(_.defaults, options);
    _.queue = [];
    _.idQueue = [];
    _.data = {};
    _.erorrs = [];
    _.currentConnections = 0;
    _.interval;
    _.ids = {};
    _.currentID = 1;
  }
  RequestQueueSystem.prototype.mergeOptions = function(obj1, obj2) {
    var _ = this;

    for (var p in obj2) {
      try {
        // Property in destination object set; update its value.
        if (obj2[p].constructor == Object) {
          obj1[p] = MergeRecursive(obj1[p], obj2[p]);

        } else {
          obj1[p] = obj2[p];

        }

      } catch (e) {
        // Property in destination object not set; create it and set its value.
        obj1[p] = obj2[p];

      }
    }

    return obj1;

  }
  RequestQueueSystem.prototype.addToQueue = function(args) { //getItemChannelSKU     (token, itemID, callback)
    var _ = this;

    var id = _.currentID;
    _.currentID++;
    var ret = _.options.returnIDS(id, val);
    _.ids[ret.key] = ret.data
    _.queue.push(args);
    _.idQueue.push(id);
    _.checkQueue();
  }
  RequestQueueSystem.prototype.checkQueue = function() {
    var _ = this;
    if (_.currentConnections < _.options.maxConnections) {
      _.currentConnections++;
      _.processQueue();
    }
  }
  RequestQueueSystem.prototype.processQueue = function(fromCallRequest) {
    var _ = this;
    console.log(_.currentConnections, _.queue.length)
    if (_.options.maxConnections != _.currentConnections && _.queue.length != 0) {
      _.currentConnections++;
      var ret = _.queue.shift();
      var id = _.idQueue.shift();
      _.callRequest(id, ret);
    }

    if (_.currentConnections == 0 && _.queue.length == 0) {
      if (_.options.timeoutLength) {
        _.interval = setInterval(function() { // wait time to submit callback
          _.options.callback(_.data);
        }, _.options.timeoutLength);
      } else {
        _.options.callback(_.data);
      }
    }
  }
  RequestQueueSystem.prototype.callRequest = function(id, fnArgs, repeat) {
    var _ = this;
    //  inventory.getItemChannelSKU()
    var fnArgs = fnArgs;
    //  console.log(fnArgs, typeof fnArgs, "logging my thang");
    if ((typeof fnArgs).toLowerCase() != "object") {
      console.log(fnArgs, "error here fam")
    }
    fnArgs.callback = function(err, data) {
      if (data) {
        console.log("got data");
        _.data[id] = data;
        _.currentConnections--;
        _.processQueue(true);

      } else if (err && !repeat) { // re-call request
        console.log("recalling request")
        _.callRequest(id, _.options.args, 1);
      } else if (err && repeat == 1) { // try once more on delay -- wait 5 seconds
        console.log("recalling request again")
        setTimeout(function() {
          _.callRequest(id, _.options.args, 2);
        }, 5000);

      } else if (err && repeat == 2) {
        _.errors.push(err, 2);
        _.currentConnections--;
        _.processQueue(true)
      }
    }
    _.options.fn(fnArgs);
  }

  RequestQueueSystem.prototype.init = function() {
    var _ = this;
    if (_.options.queueItems.length != 0) {
      _.queue = _.queue.concat(_.options.queueItems);
      //  console.log(_.queue, "logging queue");
      for (var i = 0; i < _.options.maxConnections; i++) {
        var id = _.currentID;
        _.currentID++;
        var ret = _.options.returnIDS(id, _.queue[0]);
        _.ids[ret.key] = ret.data;
        _.idQueue.push(id);
        _.processQueue();
      }
    }
  }

  function setCallback(callback) {
    orderCallback = callback;
  }

  function setBCOrder(orderID) {
    bcOrderID = orderID;
  }
  return {
    //getInventoryViews:getInventoryViews,
    getAuthenticationToken: getAuthenticationToken,
    setCallback: setCallback,
    inventory: inventory,
    stock: stock,
    user: user,
    order: order,
    useful: useful,
    setBCOrder: setBCOrder
  }
}());
