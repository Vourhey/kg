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

function getTimestamp() {
  var now = new Date();
  var h = now.getHours();
  if(h < 10) {
    h = '0' + h;
  } 
  var m = now.getMinutes();
  if(m < 10) {
    m = '0' + m;
  }
  var s = now.getSeconds();
  if(s < 10) { 
    s = '0' + s;
  }
  return h + ":" + m + ":" + s;
}

function loadAll() {
  console.log('Load all');
  var si = $('#searchinput');
  si.val('');
  si.data('oldVal', "");
  currentXHR = null;

  $.get("search.php?table="+si.data('table'), function(data) {
    $('#tablebody').html(data);
    $('.loader').hide();
  });  
}

function searchFilm() {
  console.log(getTimestamp() + " function searchFilm()");
  var s = $(this);

  if(currentXHR && s.val() != s.data('oldVal')) {
    console.log(getTimestamp() + " Canceling previous ajax request");
    console.log(currentXHR);
    currentXHR.abort();
    currentXHR = null;
  }

  console.log('"' + s.val() + '"');
  console.log('"' + s.data('oldVal') + '"');

  if(s.val()) {
    if(s.val() != s.data('oldVal')) {
      $('#tablebody').empty();
      $('.loader').show();
   
      console.log(getTimestamp() + " " + s.val());
      currentXHR = 
        $.get("search.php?query=" + encodeURIComponent(s.val()) + "&table="+s.data('table'), function(data) {
          $('#tablebody').html(data);
          $('.loader').hide();
          currentXHR = null;
        });
      console.log(getTimestamp() + " set oldVal as " + s.val());
      s.data('oldVal', s.val());
    }
  } else {
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
    if($('.editbox').length == 0) { // there is no rows, and we should show all from db
      loadAll();
    }
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
