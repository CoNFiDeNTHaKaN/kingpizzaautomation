module.exports = (function() {

var productsWithNoTax = ["White Children's T-Shirt-5-6","White Children's T-Shirt-7-8","White Children's T-Shirt-9-11","White Children's T-Shirt-9-11"];


function check(prodTitle) {
  var index = productsWithNoTax.indexOf(prodTitle),
      ret;

      if (index == -1){
        ret = false;
      }
      else{
        ret = true;
      }
      return ret;
}









return {
  check:check
}


}());
