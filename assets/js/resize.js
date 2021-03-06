var autoSizeText;

//resizes ALL elements with the resize class
autoSizeText = function() {
  var el, elements, i, len, results;
  elements = $('.cooldowns');
  console.log(elements);
  if (elements.length < 0) {
    return;
  }
  results = [];
  for (i = 0, len = elements.length; i < len; i++) {
    el = elements[i];
    results.push((function(el) {
      var resizeText, results1;
      resizeText = function() {
        var elNewFontSize;
        elNewFontSize = (parseInt($(el).css('font-size').slice(0, -2)) - 1) + 'px';
        return $(el).css('font-size', elNewFontSize);
      };
      results1 = [];
      while (el.scrollHeight > el.offsetHeight) {
        results1.push(resizeText());
      }
      return results1;
    })(el));
  }
  return results;
};

$(document).ready(function() {
  return autoSizeText();
});