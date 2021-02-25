var fs = require("fs"),
    os = require("os");

module.exports = (function(argument) {

var currentErrors = [],
    orderErrors = []; // store error ids in here

// PUB / SUB
var events = {},
  trigger = function(eventName, data) {
  if (events.hasOwnProperty(eventName)) {
    events[eventName].forEach(function(fn, i) {
      fn(data);
    })
  }
},
on = function (eventName, callback) {
  if (!events.hasOwnProperty(eventName)) {
    events[eventName] = [];
  }
  events[eventName].push(callback);
};




function pushError(id, err) {
  orderErrors.push(id);
  currentErrors.push(new Date().toString() + " --- " + err);
}

function dumpLog() {
  var str = "";
  currentErrors.forEach(function(val, i) {
    str += val + os.EOL;
  });
  currentErrors = [];
  fs.appendFile("error.txt", str, "utf8",function(err){
    if(err){
      console.log("ERROR WRITING TO FILE",err);
    }
  });
}


function checkOrderErrors(callback) {
  var ret;
  if (orderErrors.length == 0){
    ret = false
  }
  else{
    ret = orderErrors;
  }
  callback(ret);
  orderErrors = [];
}

// ADD LISTENERS
on("dumpLog", dumpLog);
on("checkOrderErrors", checkOrderErrors);



return{
  on:on,
  trigger:trigger,
  pushError:pushError
}

}());
