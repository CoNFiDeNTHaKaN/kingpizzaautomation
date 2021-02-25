function init() {

  Orders = new Services.OrdersService;
  Inventory = new Services.InventoryService;
  Listings = new Services.ListingsService;
  tableSelector = '.tableholder';
  messageSelector = '.order-manager__message';
  window.debugmsg = true;

  window.inform = function(msg) {
    // console.log(typeof msg);
    // console.log(msg);
    if (msg === false) {
      document.querySelector(messageSelector).innerText = '';
    } else {
      document.querySelector(messageSelector).innerText = msg;
    }
  }

  String.prototype.iso = function() {
    var iso = this;
    var isoDateBits = iso.split('T')[0].split('-')
    var isoTimeBits = iso.split('T')[1].split(':')
    return {
      'friendly': isoDateBits[2] + '/' + isoDateBits[1] + '/' + isoDateBits[0] + ' ' + isoTimeBits[0] + ':' + isoTimeBits[1] + ':' + isoTimeBits[2].substring(0,2),
      'friendlydate': isoDateBits[2] + '/' + isoDateBits[1] + '/' + isoDateBits[0],
      'friendlytime': isoTimeBits[0] + ':' + isoTimeBits[1] + ':' + isoTimeBits[2].substring(0,2),
      'day': isoDateBits[2],
      'month': isoDateBits[1],
      'year': isoDateBits[0],
      'hour': isoTimeBits[0],
      'minute': isoTimeBits[1],
      'second': isoTimeBits[2],
    }
  }

  String.prototype.friendly = function() {
    var friendly = this;
    var friendlyDateBits = friendly.split(' ')[0].split('/');
    var friendlyTimeBits = friendly.split(' ')[1].split(':');
    return {
      'iso': friendlyDateBits[2] + '-' + friendlyDateBits[1] + '-' + friendlyDateBits[0] + 'T' + friendlyTimeBits[0] + ':' + friendlyTimeBits[1] + ':' + friendlyTimeBits[2],
      'isodate': friendlyDateBits[2] + '-' + friendlyDateBits[1] + '-' + friendlyDateBits[0],
      'isotime': friendlyTimeBits[0] + ':' + friendlyTimeBits[1] + ':' + friendlyTimeBits[2],
      'day': friendlyDateBits[2],
      'month': friendlyDateBits[1],
      'year': friendlyDateBits[0],
      'hour': friendlyTimeBits[0],
      'minute': friendlyTimeBits[1],
      'second': friendlyTimeBits[2],
    }
  }


  Table = {};
  Table.pageLength = 200;
  Table.pagesSoFar = 0;
  Table.el = document.querySelector(tableSelector);
  Table.clear = function() {
    var rows = Table.el.querySelector('tbody').children;
    if (rows.length > 0) {
      var rowCount = rows.length;
      while (Table.el.querySelector('tbody').firstChild) {
        Table.el.querySelector('tbody').removeChild(Table.el.querySelector('tbody').firstChild);
      }
      // console.log('Cleared rows : ', rowCount);
    } else {
      // console.log('No rows to clear');
    }
  }

  Table.addRow = function(order, addToTable = true) {
    newRow = document.createElement('tr');
    newRow.id = `order${order['NumOrderId']}`;
    if (order['Notes'] != "") {
      newhtml = `<td class="order-info"><p><b>${order['NumOrderId']}</b><br><i>${order['GeneralInfo']['ReceivedDate'].iso()['friendly']}</i><br>${order['CustomerInfo']['Address']['FullName']}<br>${order['GeneralInfo']['Source']}</p></td><td class="order-date"><div class="view">${order['GeneralInfo']['DespatchByDate'].iso()['friendlydate']}</div><div class="edit"><input  min="1" max="31" name="${order['NumOrderId']}['day']" class="input--day" value="${order['GeneralInfo']['DespatchByDate'].iso()['day']}">\/<input  min="1" max="12" name="${order['NumOrderId']}['month']" class="input--month" value="${order['GeneralInfo']['DespatchByDate'].iso()['month']}">\/<input  min="2019" max="2022" name="${order['NumOrderId']}['year']" class="input--year" value="${order['GeneralInfo']['DespatchByDate'].iso()['year']}"></div></td><td class="order-message"><div class="view"><pre>${order['Notes']}</pre></div><div class="edit"><textarea class="input--message" name="${order['NumOrderId']}['Notes']">${order['Notes']}</textarea></div></td><td class="order-save"><div class="edit"> <button class="btn" onclick="Table.updateRow(${order['NumOrderId']}, false);" >Cancel</button> <button class="btn btn-primary" onclick="Table.updateOrder(${order['NumOrderId']}, true);" >Save</button></div></td>`;
    } else {
      newhtml = `<td class="order-info"><p><b>${order['NumOrderId']}</b><br><i>${order['GeneralInfo']['ReceivedDate'].iso()['friendly']}</i><br>${order['CustomerInfo']['Address']['FullName']}<br>${order['GeneralInfo']['Source']}</p></td><td class="order-date"><div class="view">${order['GeneralInfo']['DespatchByDate'].iso()['friendlydate']}</div><div class="edit"><input  min="1" max="31" name="${order['NumOrderId']}['day']" class="input--day" value="${order['GeneralInfo']['DespatchByDate'].iso()['day']}">\/<input  min="1" max="12" name="${order['NumOrderId']}['month']" class="input--month" value="${order['GeneralInfo']['DespatchByDate'].iso()['month']}">\/<input  min="2019" max="2022" name="${order['NumOrderId']}['year']" class="input--year" value="${order['GeneralInfo']['DespatchByDate'].iso()['year']}"></div></td><td class="order-message"><pre> == No message specified == </pre></td><td class="order-save"><div class="edit"> <button class="btn" onclick="Table.updateRow(${order['NumOrderId']}, false);" >Cancel</button> <button class="btn btn-primary" onclick="Table.updateOrder(${order['NumOrderId']}, true);" >Save</button></div></td>`;
    }
    newRow.innerHTML = newhtml;
    newRow.addEventListener('dblclick', function() {
      this.classList.add('editable');
      window.inform(`Opening order #${order['NumOrderId']} for editing.`);
    })

    if (addToTable) {
      Table.el.querySelector('tbody').appendChild(newRow);
    } else {
      return newRow;
    }
  }

  Table.updateRow = function(NumOrderId,changesMade) {

      if (changesMade === false){
        window.inform(false)
      } else {
        window.inform(`Saving ${NumOrderId}.`);
      }

    Orders.GetOrderDetailsByNumOrderId(NumOrderId, function(response) {
      var thisOrder = response.result;

      Orders.getOrderNotes(thisOrder['OrderId'], function(response) {
        // console.log(response);
        thisOrder.Notes = "";
        response.result.forEach(function(note) {
          // console.log(note);
          if (note['CreatedBy'] === "AMAZON" || note['CreatedBy'] === "API") {
            thisOrder.Notes = note['Note'];
          }
        })

        var thisRow = document.querySelector(`tr#order${NumOrderId}`);
        var thisRowPosition = Array.prototype.indexOf.call(thisRow.parentNode.children, thisRow);
        // console.log(thisOrder, thisRow, thisRowPosition);

        var newRow = Table.addRow(thisOrder, false);
        // console.log(newRow)
        thisRow.parentNode.replaceChild(newRow, thisRow);

        if (changesMade){
            window.inform(`Successfully saved #${NumOrderId}.`);
        }

      });
    });
  };
  Table.updateOrder = function(order, changesMade) {
    var messageArea = document.querySelector(`[name="${order}['Notes']"]`);
    var dayArea = document.querySelector(`[name="${order}['day']"]`);
    var monthArea = document.querySelector(`[name="${order}['month']"]`);
    var yearArea = document.querySelector(`[name="${order}['year']"]`);

    window.inform(`Updating #${order}.`);
    Orders.GetOrderDetailsByNumOrderId(order, function(response) {
      var thisOrder = response.result;

      // isolate general info
      var generalInfo = thisOrder.GeneralInfo;

      //retrieve, update and format despatch date
      var thisOrderDespatchByDate = generalInfo.DespatchByDate;
      var newDespatchByDate = yearArea.value + '-' + monthArea.value.padStart(2, '0') + '-' + dayArea.value.padStart(2, '0');
      var newIsoDespatchByDate = newDespatchByDate + 'T' + thisOrderDespatchByDate.iso().friendlytime;
      generalInfo.DespatchByDate = newIsoDespatchByDate;

      //set generalinfo
      Orders.setOrderGeneralInfo(thisOrder.OrderId, generalInfo, false, function() {

        if (messageArea) {

          Orders.getOrderNotes(thisOrder['OrderId'], function(response) {
            thisOrderNotes = response.result;

            thisOrderNotes.forEach(function(note) {

              if (note['CreatedBy'] === "AMAZON" || note['CreatedBy'] === "API") {
                note['Note'] = messageArea.value;
              }

            });

            Orders.setOrderNotes(thisOrderNotes[0].OrderId, thisOrderNotes, function(response) {
              // console.log('update successful')
              Table.updateRow(thisOrder.NumOrderId, true);
            })

          });

        } else {
          Table.updateRow(thisOrder.NumOrderId, true);
        }

      });
    });


  }
  Table.retrieveOrders = function(count, page, callback) {
    Orders.getOpenOrders(count, page, null, null, false, null, function(response) {
      // console.log(response)
      if (typeof response.error == "undefined") {
        response.result.Data.forEach(function(order) {
          // console.log(order);
          Orders.getOrderNotes(order['OrderId'], function(response) {
            // console.log(response);
            order.Notes = "";
            response.result.forEach(function(note) {
              // console.log(note);
              if (note['CreatedBy'] === "AMAZON" || note['CreatedBy'] === "API") {
                order.Notes = note['Note'];
              }
            })
            Table.addRow(order);
          });
        });
        if (typeof callback == "function") {
          callback();
        }
      } else {
        alert('Something went wrong, please contact Website Success');
      }
    });
  }

  Table.loadNextPage = function() {
    Table.pagesSoFar++;
    Table.retrieveOrders(Table.pageLength, Table.pagesSoFar, function() {
      window.inform('Orders loaded. Double click an order to edit it...')
      Table.el.querySelector('tfoot').style.display = ''
    });
  }

  window.inform('Clearing table...')
  Table.clear();

  window.inform(`Loading ${Table.pageLength} most recent orders...`)
  Table.loadNextPage();

}

function OrderManagerModule($scope, $element, $q) {
  // console.log($scope, $element, $q)

}
