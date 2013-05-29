var idHover = 0;
var idLast = 0;

$(document).ready(function() {	
	$("#infoDisplayInner").hide();
});

function timedNameHover(id) {
	if(id == idLast) return;
	idHover = id;
	idLast = id;
	var func = function() { timedNameCheck(id); };
	setTimeout(func, 1500);
}
function timedNameOut() {
	idHover = 0;
}
function timedNameCheck(id) {
	if(idHover == id) showMiniProfile(id);
}

function showMiniProfile(id) {
	var url = "info.php?opt=miniprofile&uid=" + id;
	$.ajax({
		url: url,
		context: $("#infoDisplay"),
		cache: false,
		success: function(data) {
			infodisplay_show(data);
		}
	});	
}
function infodisplay_show(html) {
	$("#infoDisplayInner").hide();
	$("#infoDisplayInner").html(html);
	$("#infoDisplayInner").fadeIn(1000, "swing");
}
function infodisplay_hide() {
	$("#infoDisplayInner").fadeOut(1000, "swing", function() {});
}