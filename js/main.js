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
    $('#filmtable ').html(data);
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