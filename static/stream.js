var stream_last = 0;
var stream_mode = "update";
var stream_interval = 10000;
var stream_show = true;
var stream_first = true;
var stream_buffer = "";
var stream_updates_new = "";
var stream_updates_display = "";

var notification_last = "";

String.prototype.startsWith=function(str){return (this.match("^"+str)==str)}

function stream_update() {
	var url = "stream.php?mode=" + stream_mode + "&user=" + uid + "&last=" + stream_last;
	$.ajax({
		url: url,
		context: $("#stream"),
		cache: false,
		success: function(data) {
			$("#stream_loading").hide();
			if(!data.startsWith("<<NULL>>"))
			{
				stream_buffer = data + stream_buffer;
				if(stream_show || stream_first)
					stream_buffer_show();
				else
				{
					notification_show(stream_updates_new + ' <a class="button" href="#" onclick="stream_buffer_show();notification_close();return false;">' + stream_updates_display + '</a>');
				}
			}
			stream_first = false;
			setTimeout(stream_update, stream_interval);
		}
	});	
}
function stream_buffer_show() {
	$("#stream").prepend(stream_buffer);
	stream_buffer = "";
}
function stream_post_open() {
	$(".publish textarea").hide();
	$(".publish .button").hide();
	$(".publish").animate({width: "480"}, 1000, "swing", function() {
		$(".publish textarea").fadeIn(500);
		$(".publish .button").fadeIn(500);
	});
}
function stream_post_close() {
	$(".publish .button").fadeOut(500);
	$(".publish textarea").fadeOut(500, function() {
		$(".publish").animate({width: "0"}, 1000, "swing");
	});
}
function stream_post() {
	var message = $(".publish textarea").val();
	
	$.post("stream.php?mode=post&rand=" + (Math.random()), {body: message}, function(data) {
			$(".publish textarea").val("");
			stream_first = true;
			stream_post_close();
			stream_update();
		}
	);
}

function notification_show(html) {
	if(notification_last == html) return;
	notification_last = html;
	$("#notifications").html('<div id="notificationsInner">' + html + '</div>');
	$("#notificationsInner").fadeOut(500, function() {
		$("#notifications").animate({height: "50"}, 1000, "swing", function() {
			$("#notificationsInner").fadeIn(500);
		});
	});
}
function notification_close() {
	$("#notificationsInner").fadeOut(500, function() {
		$("#notifications").animate({height: "0"}, 1000, "swing");
	});
}