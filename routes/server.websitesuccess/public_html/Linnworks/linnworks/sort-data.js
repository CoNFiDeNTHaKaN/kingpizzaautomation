// return codes
/*

 0 - No Matches
 1 - Found a single Match
 2 - Found multiple matches (may be partial matches)

*/


module.exports = (function() {

    function searchById(array, id) {
      var ret = array.filter(function(value) {
        var stockID = value.StockItemId;
        if (stockID) {
          if (stockID == id) {
            return true
          }
        }

      });
      return ret;
    }


    function filterPartialSku(array, sku) {
      var sku = sku.toLowerCase(),
        ret = array.filter(function(val) {
          var itemNO = val.ItemNumber
          if (itemNO) {
            if (itemNO.toLowerCase().indexOf(sku) != -1) {
              return true;
            }
          }
        });
      return ret;
    }

    function formatStringToArray(prodTitle) {
      if (typeof prodTitle == "string"){
      var prodTitle = prodTitle.replace(/-/g, " ").replace(/ /g, "-|-").split("-|-"),
        loop = true;
      while (loop) {
        var index = prodTitle.indexOf("");
        if (index != -1) {
          prodTitle.splice(index, 1);
        } else {
          loop = false;
        }
      } // removes ""
    }
    else{
      console.log("mad error fam prodTitle is - " + prodTitle+  " - "+ typeof prodTitle)
    }
      return prodTitle
    }

    function betterSKUSearch(array, sku, prodTitle) { // returns code 0 when returns 0  & returns 1 when 1 result & returns 2 when more than 1 result
      var ret = filterPartialSku(array, sku),
          prodTitle = prodTitle || "";
      if (ret.length > 1) {
        var prodTitle = formatStringToArray(prodTitle);


        var arrays = {};
        for (var i = 0, len = prodTitle.length; i < len; i++) {
          var curr = prodTitle[i];
          arrays[i] = filterPartialSku(ret, curr);
        }
        var possibles = [];
        for (var key in arrays) {
          var current = arrays[key];
          if (current.length != 0) {

            for (var i = 0, len = current.length; i < len; i++) {
              if (possibles.indexOf(current[i].StockItemId) == -1) {
                possibles.push(current[i].StockItemId);
              }
            }
          }

        }
        if (possibles.length > 0) {
          return {
            data: findByStockId(ret, possibles),
            code: 1
          }
        } else {
          var code = possibles.length == 0 ? 0 : 2;
          return {
            data: ret,
            code: code
          }
        }

      }
      else{
        return{
          code:0
        }
      }
    }

    function findByStockId(array, stockID) {
      var ret = array.filter(function(val) {
        if (val.StockItemId == stockID) {
          return true;
        }
      })
      return ret;
    }

    function filterByName(array, prodTitle) {
      var prodTitle = formatStringToArray(prodTitle),
        ret,
        possibles = {};
      //  extrasData = {}

      prodTitle.forEach(function(val, i) {

        array.forEach(function(val) {
          var title = val.ItemTitle;
          if (title) {
            if (title.toLowerCase().indexOf(prodTitle[i].toLowerCase()) != -1) {
              var id = val.StockItemId;
              if (possibles.hasOwnProperty(id)) {
                possibles[id] = possibles[id] + 1;
              } else {
                possibles[id] = 1;
              }




            }
          }
        });
      });
      var possiblesNO = {};
      for (var key in possibles) {
        var curr = possibles[key];
        if (!possiblesNO.hasOwnProperty(curr)) {
          possiblesNO[curr] = [];
        }
        possiblesNO[curr].push(key);



      }
      var loop = true,
        counter = 0;
      while (loop) {

        if (counter > 10000) { // safety ting
          loop = false;
        }

        //  if ()
        if (possiblesNO.hasOwnProperty(prodTitle.length - counter)) {
          var data = possiblesNO[prodTitle.length - counter],
            code;
            if (data.length > 1){
              var details = data.map(function(val){
                  return findByStockId(array, val)
              }),
              wordCount = {};

              details.forEach(function(val, i){
                //console.log(val[0]);
                var title = formatStringToArray(val[0].ItemTitle);
                if (!wordCount.hasOwnProperty(title.length - prodTitle.length)){
                  wordCount[title.length - prodTitle.length] = []
                }
                wordCount[title.length - prodTitle.length].push(val[0]);
              });

            if (wordCount.hasOwnProperty("0")){
                if (wordCount[0].length == 1){
                  data = wordCount[0];
                }
            }
            }
          if (data.length == 1) {
            code = 1;
            if (typeof data[0] == "string"){
            data = findByStockId(array,data[0]);
            }
          } else {
            code = data.length == 0 ? 0 : 2;

          }
          ret = {
            data: data,
            code: code
          }
          loop = false;
        }
        if (counter == prodTitle.length) {
          loop = false;
        }
        counter++;
      }
      return ret
    }

    return {
      filterByName: filterByName,
      findByStockId: findByStockId,
      betterSKUSearch: betterSKUSearch,
      searchById: searchById,
      filterPartialSku: filterPartialSku
    }

  })()
  //Delrin Kistka-Heavy - White
  //BAT022
