$(function() {
  console.log("Document is loaded!");

  loadAll();

  $('#searchinput').on("change paste keyup", searchFilm);
});

function loadAll() {
  console.log('Load all');
  $('#searchinput').data('oldVal', "");
  $.get("search.php", function(data) {
    $('#tablebody').html(data);
    $('.loader').hide();
  });  
}

function searchFilm() {
  var s = $(this);

  console.log('"' + s.val() + '"');
  console.log('"' + s.data('oldVal') + '"');

  if(s.val()) {
    if(s.val() != s.data('oldVal')) {
      $('#tablebody').empty();
      $('.loader').show();
   
      console.log(s.val());
      $.get("search.php?query=" + s.val(), function(data) {
        $('#tablebody').html(data);
        $('.loader').hide();
      });
    }
    s.data('oldVal', s.val());
  } else if(s.data('oldVal')) {
    loadAll();
  }
}

/********* TODO ************
function getIds() {
  var ids = $('.editbox:checked').map(function() { return this.value; }).get();
  console.log(ids);
  return ids.join(',');
}

function getRandom(min, max) {
  return Math.floor((Math.random() * (max - min) + min));
}

function deleteFilms(tablename) {
  console.log(tablename);
  $.get("edit.php?table=" + tablename + "&method=delete&ids=" + getIds(), function(data){
    $('#filmtable > tbody').html(data);
  });
}

function moveFilm() {
  $.get("edit.php?table=filmlist&method=move&ids=" + getIds(), function(){
    var row = $('.editbox:checked').parent('td').parent('tr');
    row.remove();
  });
}

function addFilms(tablename) {
  $('#nofilmstr').remove();

  var filmlist = $('#addfilmstextarea').val().split('\n');
  console.log(filmlist);

  var i = 0;
  console.log("POST: " + filmlist[i]);
  $.post("add.php", {addfilm: filmlist[i], table: tablename}, function(data) {
    var tmp = $('#filmtable > tbody').html();
    $('#filmtable > tbody').html(data + tmp);
  });
  ++i;

  if(i < filmlist.length) {
    (function loops(){
      setTimeout(function() {
        console.log("POST: " + filmlist[i]);
        $.post("add.php", {addfilm: filmlist[i], table: tablename}, function(data) {
          var tmp = $('#filmtable > tbody').html();
          $('#filmtable > tbody').html(data + tmp);
        });
        ++i;
        if(i < filmlist.length)
          loops();
      }, getRandom(10000, 30000));
    })();
  }
}

function replaceFilm(tablename) {
  var film = $('#addfilmstextarea').val();
  console.log(film);

  $.post("add.php", {addfilm: film, table: tablename, replace: getIds()}, function(data) {
    $('.editbox:checked').parent('td').parent('tr').replaceWith(data);
    $('#addfilmstextarea').val('');
  });
}

*/