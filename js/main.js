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

function addFilms() {
  $('#nofilmstr').remove();

  var filmlist = $('#addfilmstextarea').val().split('\n');
  console.log(filmlist);

  var i = 0;
  console.log("POST: " + filmlist[i]);
  $.post("add.php", {addfilm: filmlist[i]}, function(data) {
    var tmp = $('#filmtable > tbody').html();
    $('#filmtable > tbody').html(data + tmp);
  });
  ++i;

  if(i < filmlist.length) {
    (function loops(){
      setTimeout(function() {
        console.log("POST: " + filmlist[i]);
        $.post("add.php", {addfilm: filmlist[i]}, function(data) {
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