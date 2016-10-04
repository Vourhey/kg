$(function() {
  console.log("Document is loaded!");

  currentXHR = null;

  loadAll();
  //console.log($('.pagination').data('num_pages'));

  $('#searchinput').on("change paste keyup", searchFilm);
  $('#tablebody').on('click', '.plusbutton', moveFromTempToFilmlist);
  $('#tablebody').on('click', '.editbox', function() {
    if($('.editbox:checked').length == 0) {
      $('#editButtons').hide();
      if($('#checkallbtn').data('checked')) {
        toggleCheckAll();
      }
    } else {
      $('#editButtons').show();
    }
  });
  $('#movebtn').click(moveFilms);
  $('#deletebtn').click(deleteFilms);
  $('.pagination').on('click', 'li > a', changePage);
  $('#scrollUp').click(scrollUp);
  $('#scrollDown').click(scrollDown);
  $('#checkallbtn').click(function() {
    console.log("hey, i'm here");
    $('.editbox').click();
    toggleCheckAll();
  });
});

function scrollUp() {
  $('body').animate({scrollTop: 0}, 300);
}

function scrollDown() {
  $('body').animate({scrollTop: $(document).height()}, 300);
}

function toggleCheckAll() {
  var i = $("#checkallbtn");
  var child = i.children('span');
  console.log(i.data('checked'));
  var c = i.data('checked', !i.data('checked')).data('checked');
  console.log(c);
  if(c) {
    i.removeClass('btn-default').addClass('btn-primary');
    child.removeClass('glyphicon-unchecked').addClass('glyphicon-check');
  } else {
    i.removeClass('btn-primary').addClass('btn-default');
    child.removeClass('glyphicon-check').addClass('glyphicon-unchecked');
  }
}

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
  si.data('oldVal', '');
  currentXHR = null;
  if($('#checkallbtn').data('checked')) {
    toggleCheckAll();
  }

  $.get("search.php?table="+si.data('table'), function(data) {
    //console.log(data);
    var data = JSON.parse(data);
    //console.log(answer.tbody);
    console.log(data);
    $('#tablebody').html(data.tbody);
    $('.pagination').html(data.pagination);
    $('.loader').hide();
    scrollUp();
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

      if($('#checkallbtn').data('checked')) {
        toggleCheckAll();
      }

      console.log(getTimestamp() + " " + s.val());
      currentXHR =
        $.get("search.php?query=" + encodeURIComponent(s.val()) + "&table="+s.data('table'), function(data) {
          var answer = JSON.parse(data);
          //console.log(answer.tbody);
          console.log(answer);
          $('#tablebody').html(answer.tbody);
          $('.pagination').html(answer.pagination);
          $('.loader').hide();
          scrollUp();
          currentXHR = null;
        });
      console.log(getTimestamp() + " set oldVal as " + s.val());
      s.data('oldVal', s.val());
    }
  } else if(s.data('oldVal')) {
    console.log('load all from searchFilm');
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
    $('#editButtons').hide();
    if($('.editbox').length == 0) { // there is no rows, and we should show all from db
      loadAll();
    }
  });
}

function deleteFilms(e) {
  e.preventDefault();
  console.log("deleting films");
  var request = "edit.php?table=" + $('#searchinput').data('table') + "&method=delete&ids=" + getIds();
  $.get(request, function(){
    $('.editbox:checked').parent('td').parent('tr').remove();
    $('#editButtons').hide();
    if($('.editbox').length == 0) { // there is no rows, and we should show all from db
      loadAll();
    }
  });
}

function changePage(e) {
  e.preventDefault();
  $('#tablebody').empty();
  $('.loader').show();
  if($('#checkallbtn').data('checked')) {
    toggleCheckAll();
  }

  console.log("Page: " + $(this).data('page'));
  var i = $(this);
  var request = "search.php?table=" + i.data('table') + "&page=" + i.data('page');
  if(i.data('query')) {
    request += "&query=" + i.data('query');
  }

  $.get(request, function(data) {
    //console.log(data);
    var answer = JSON.parse(data);
    //console.log(answer.tbody);
    console.log(answer);
    $('#tablebody').html(answer.tbody);
    $('.pagination').html(answer.pagination);
    $('.loader').hide();
    scrollUp();

  });
}
