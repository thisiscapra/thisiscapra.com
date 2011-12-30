/* Author: 

*/

$(document).ready(function() {
  $("#x_next").click(function() {
    switch_image($("#x_images .x_current_image").next(), $(".x_image").first());
    return false;
  });

  $("#x_prev").click(function() {
    switch_image($("#x_images .x_current_image").prev(), $(".x_image").last());
    return false;
  });
});

switch_image = function(elem, alt) {
  if (elem.length == 0) {
    elem = alt;
  }
  $("#x_spinner").show();
  $("#x_portfolio").fadeOut(500, function() {
    $.ajax({
      method: "GET",
      href: $(elem).text(),
      complete: function() {
        $("#x_spinner").hide();
        $("#x_portfolio")[0].src = $(elem).text();
      },
      success: function() {
        $(".x_image").removeClass("x_current_image");
        $(elem).addClass("x_current_image");  
        $("#x_portfolio").fadeIn(500);
      }
    });
  });
  return elem;
}

















