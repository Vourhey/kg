$(function() {
  console.log("Document is loaded!");

  currentXHR = null;

  loadAll();

  $('#searchinput').on("change paste keyup", searchFilm);
  $('#tablebody').on('click', '.plusbutton', moveFromTempToFilmlist);
  $('.btn-group').hide();
  $('#tablebody').on('click', '.editbox', function() {
    if($('.editbox:checked').length == 0) {
      $('.btn-group').hide();
    } else {
      $('.btn-group').show();
    }
  });
  $('#movebtn').click(moveFilms);
  $('#deletebtn').click(deleteFilms);
});

function loadAll() {
  console.log('Load all');
  var si = $('#searchinput');
  si.data('oldVal', "");

  $.get("search.php?table="+si.data('table'), function(data) {
    $('#tablebody').html(data);
    $('.loader').hide();
  });  
}

function searchFilm() {
  var s = $(this);

  if(currentXHR) {
    console.log("Canceling previous ajax request");
    console.log(currentXHR);
    currentXHR.abort();
  }

  console.log('"' + s.val() + '"');
  console.log('"' + s.data('oldVal') + '"');

  if(s.val()) {
    if(s.val() != s.data('oldVal')) {
      $('#tablebody').empty();
      $('.loader').show();
   
      console.log(s.val());
      currentXHR = 
        $.get("search.php?query=" + encodeURIComponent(s.val()) + "&table="+s.data('table'), function(data) {
          $('#tablebody').html(data);
          $('.loader').hide();
        });
      //console.log(s.jqxhr);
    }
    s.data('oldVal', s.val());
  } else if(s.data('oldVal')) {
    loadAll();
  }
}

function moveFromTempToFilmlist() {
  console.log("moveFromTempToFilmlist");

  var row = $(this).parent('td').parent('tr');
  var kpid = $(this).val();
  $.get("edit.php?method=moveToFilmlist&ids=" + kpid, function(data) {
    row.replaceWith(data);
  });
}

function getIds() {
  var ids = $('.editbox:checked').map(function() { return this.value; }).get();
  console.log(ids);
  return ids.join(',');
}

function moveFilms(e) {
  console.log("moving films");
  e.preventDefault();   // preventing reload
  $.get("edit.php?method=move&ids=" + getIds(), function(){
    $('.editbox:checked').parent('td').parent('tr').remove();
    $('.btn-group').hide();
  });
}

function deleteFilms(e) {
  e.preventDefault();
  console.log("deleting films");
  $.get("edit.php?table=" + $('#searchinput').data('table') + "&method=delete&ids=" + getIds(), 
        function(){
          $('.editbox:checked').parent('td').parent('tr').remove();
          $('.btn-group').hide();     
        });
}

/********* TODO ************


function getRandom(min, max) {
  return Math.floor((Math.random() * (max - min) + min));
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
