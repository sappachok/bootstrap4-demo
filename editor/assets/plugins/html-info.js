$(document).ready(function() {
	var moveLeft = 20;
	var moveDown = 10;

	$("p, div, span, a, table, tr, th, td, h1, h2, h3, h4, input, select, form, ul, li").mouseover(function(e) {
	  var target = $(e.target);
	  var id = target.prop("id");
	  var tagname = target.prop("tagName");
	  var classname = target.prop("className");
	  var info = "<b>" + tagname + "</b><br>" +
		  "<b>id:</b> '" + id + "'<br><b>class:</b> '" + classname +"'";
	  $('div#pop-up').html(info);
	  $('div#pop-up').show();
	  console.log($(target));
	});

	$(document).mousemove(function(e) {
	  $("div#pop-up").css('top', e.pageY + moveDown).css('left', e.pageX + moveLeft);
	});

	$(document).blur(function() {
	  $('div#pop-up').hide();
	});
});