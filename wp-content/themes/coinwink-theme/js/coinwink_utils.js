
// selectedCurrency = 'USD';


function formatCurrencyDecimalMax(totalValue, selectedCurrency) {
  var totalValue = Number(totalValue);
  if (selectedCurrency == 'percent') {
    selectedCurrency = 'USD';
  }
  if (selectedCurrency == 'BTC') {
    return totalValue.toFixed(8);
  }
  else if (selectedCurrency == 'ETH') {
    return totalValue.toFixed(7);
  }

  var formatter = new Intl.NumberFormat('en-US', {
    style: 'decimal',
    currency: selectedCurrency,
    minimumFractionDigits: 2,
    maximumFractionDigits: 6,

    // minimumSignificantDigits: 3,
    // maximumSignificantDigits: 6
  })
  return formatter.format(Number(totalValue));
}

function formatCurrencyDecimal(totalValue, selectedCurrency) {
  if (selectedCurrency == 'percent') {
    selectedCurrency = 'USD';
  }
  if (totalValue > 1000000) {
    var formatter = new Intl.NumberFormat('en-US', {
      style: 'decimal',
      currency: selectedCurrency,
      minimumFractionDigits: 0,
      maximumFractionDigits: 0
    })
  }
  else {
    var formatter = new Intl.NumberFormat('en-US', {
      style: 'decimal',
      currency: selectedCurrency,
      minimumFractionDigits: 2,
      maximumFractionDigits: 2
    })
  }
  return formatter.format(Number(totalValue));
}

function formatCurrencyM(totalValue, selectedCurrency) {
  totalValue = totalValue / 1000000;
  if (totalValue < 0.01) {
    var formatter = new Intl.NumberFormat('en-US', {
      style: 'decimal',
      currency: selectedCurrency,
      minimumFractionDigits: 0,
      maximumFractionDigits: 6
    })
  }
  else if (totalValue < 0.1) {
    var formatter = new Intl.NumberFormat('en-US', {
      style: 'decimal',
      currency: selectedCurrency,
      minimumFractionDigits: 0,
      maximumFractionDigits: 5
    })
  }
  else if (totalValue < 1) {
    var formatter = new Intl.NumberFormat('en-US', {
      style: 'decimal',
      currency: selectedCurrency,
      minimumFractionDigits: 0,
      maximumFractionDigits: 4
    })
  }
  else if (totalValue < 10) {
    var formatter = new Intl.NumberFormat('en-US', {
      style: 'decimal',
      currency: selectedCurrency,
      minimumFractionDigits: 0,
      maximumFractionDigits: 3
    })
  }
  else if (totalValue < 100000) {
    var formatter = new Intl.NumberFormat('en-US', {
      style: 'decimal',
      currency: selectedCurrency,
      minimumFractionDigits: 0,
      maximumFractionDigits: 2
    })
  }
  else {
    var formatter = new Intl.NumberFormat('en-US', {
      style: 'decimal',
      currency: selectedCurrency,
      minimumFractionDigits: 0,
      maximumFractionDigits: 0
    })
  }
  return formatter.format(Number(totalValue));
}

function formatPrice(coin_price) {
  if (coin_price < 1 && coin_price > 0.1) {
    coin_price = parseFloat(coin_price).toFixed(2);
  }
  if (coin_price < 0.1 && coin_price > 0.01) {
    coin_price = parseFloat(coin_price).toFixed(3);
  }
  else if (coin_price < 0.01 && coin_price > 0.001) {
    coin_price = parseFloat(coin_price).toFixed(4);
  }
  else if (coin_price < 0.001 && coin_price > 0.0001) {
    coin_price = parseFloat(coin_price).toFixed(5);
  }
  else if (coin_price < 0.0001 && coin_price > 0.00001) {
    coin_price = parseFloat(coin_price).toFixed(6);
  }
  else if (coin_price < 0.00001) {
    coin_price = parseFloat(coin_price).toFixed(7);
  }
  else {
    coin_price = parseFloat(coin_price).toFixed(2);
  }
  return coin_price;
}

function formatCrypto(totalValue, selectedCurrency) {
  if (totalValue < 1) {
    var formatter = new Intl.NumberFormat('en-US', {
      style: 'decimal',
      currency: selectedCurrency,
      minimumFractionDigits: 0,
      maximumFractionDigits: 6
    })
  }
  else if (totalValue < 10) {
    var formatter = new Intl.NumberFormat('en-US', {
      style: 'decimal',
      currency: selectedCurrency,
      minimumFractionDigits: 0,
      maximumFractionDigits: 5
    })
  }
  else if (totalValue < 100) {
    var formatter = new Intl.NumberFormat('en-US', {
      style: 'decimal',
      currency: selectedCurrency,
      minimumFractionDigits: 0,
      maximumFractionDigits: 4
    })
  }
  else if (totalValue < 1000) {
    var formatter = new Intl.NumberFormat('en-US', {
      style: 'decimal',
      currency: selectedCurrency,
      minimumFractionDigits: 0,
      maximumFractionDigits: 3
    })
  }
  else if (totalValue < 1000000) {
    var formatter = new Intl.NumberFormat('en-US', {
      style: 'decimal',
      currency: selectedCurrency,
      minimumFractionDigits: 0,
      maximumFractionDigits: 2
    })
  }
  else {
    var formatter = new Intl.NumberFormat('en-US', {
      style: 'decimal',
      currency: selectedCurrency,
      minimumFractionDigits: 0,
      maximumFractionDigits: 0
    })
  }
  return formatter.format(Number(totalValue));
}




// Helper functions

function getById(id) {
  return document.getElementById(id);
}

function divToggle(id) {
  if (document.getElementById(id).style.display == 'none' || document.getElementById(id).style.display == '') {
    document.getElementById(id).style.display = 'block'
  }
  else {
    document.getElementById(id).style.display = 'none'
  }
}

function divHide(id) {
  document.getElementById(id).style.display = 'none';
}

function divShow(id) {
  document.getElementById(id).style.display = "block";
}

function classAdd(id, className) {
  var el = getById(id);
  el.classList.add(className);
}

function classRemove(id, className) {
  var el = getById(id);
  el.classList.remove(className);
}