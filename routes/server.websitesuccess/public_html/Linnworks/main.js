var express = require('express'),
  app = express(),
  bodyParser = require("body-parser"),
  xml2js = require('xml2js'),
  parser = new xml2js.Parser(),
  request = require('request'),
  linnworks = require("./linnworks/main.js"),
  linnworksSortData = require("./linnworks/sort-data.js"),
  prompt = require("prompt"),
  order = require("./order.js"),
  errorHandler = require("./errorHandler.js");

app.post("/handprinted", bodyParser.urlencoded({
  extended: true
}), function(req, res) {

  res.sendStatus(200);
  console.log(req.body)
  order.addToQueue(req.body.ObjectID);

});


app.get("/handprinted/dumpLog", function(req, res){
  res.send("dumping log");
  errorHandler.trigger("dumpLog");
});

app.get("/handprinted/dumpIDErrors", function(req, res){
  res.send("dumping ID Errors");
  errorHandler.trigger("dumpIDErrors");
})

app.get("/handprinted", function(req, res) {
  res.send("WORKING")
})

app.listen(8080);

function testOrder(){
  order.addToQueue(341564)
}

/*linnworks.getAuthenticationToken(function(token) {
  // body...
  linnworks.useful.getShippingMethods({token:token})
});
*/
errorHandler.on("error", function(data) {
  console.log(data);
})
