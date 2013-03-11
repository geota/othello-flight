$(function () {
  // register a click event on free cells
  $('.free').click(function (event) {
    // parse out the coords from id attrib
    var coords = $(this).attr('id').split(":");
    // set the hidden form elements
    $('#x').val(coords[0]);
    $('#y').val(coords[1]);
    // post the form
    $("#coords-submit").click();
  });
});