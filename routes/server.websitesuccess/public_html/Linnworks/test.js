var express = require('express'),
  app = express(),
  bodyParser = require("body-parser");


  app.post("/handprinted", bodyParser.urlencoded({
    extended: true
  }), function(req, res) {

  /*  res.sendStatus(200);
    console.log(req.body)
    order.addToQueue(req.body.ObjectID);*/

    console.log("POST TO PAGE");
    console.log(req.body);

  });



  app.get("/handprinted", function(req, res) {
    console.log("GOT PAGE");
    res.send("WORKING")
  })


app.listen(8080)
