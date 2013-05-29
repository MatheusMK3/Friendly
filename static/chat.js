var chatBoxes = [];

Array.prototype.removeData = function(data) {
	var obj = this;
	$.each(obj, function(i, v) {
		if(v == data) obj.splice(i, 1);
	});
}

function setCookie(c_name,value,exdays) {
	var exdate=new Date();
	exdate.setDate(exdate.getDate() + exdays);
	var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
	document.cookie=c_name + "=" + c_value;
}
function getCookie(c_name) {
	var i,x,y,ARRcookies=document.cookie.split(";");
	for (i=0;i<ARRcookies.length;i++)
	{
		x=ARRcookies[i].substr(0,ARRcookies[i].indexOf("="));
		y=ARRcookies[i].substr(ARRcookies[i].indexOf("=")+1);
		x=x.replace(/^\s+|\s+$/g,"");
		if (x==c_name) return unescape(y);
	}
}

$(document).ready(function() {
	$(".chatNormal form").live("submit", function(e) {
		var message = $(this).children(".chatTextMessage").val();
		var id = $(this).children(".chatTextID").val();
		postChat(id, message);
		$(this).children(".chatTextMessage").val("")
		return false;
	});
	
	loadChat();
	chatFriends(true);
	
	getNewMessages();
});

function getInfo(uid, type, func) {
	var url = "info.php?opt=userinfo&info=" + type + "&user=" + uid;
	$.ajax({
		url: url,
		context: document,
		cache: false,
		success: function(data) {
			func(data);
		}
	});
}

function getNewMessages(id) {
	var url = "chat.php?mode=check";
		$.ajax({
			url: url,
			context: $("#null"),
			cache: false,
			success: function(data) {
				$("#null").html(data);
				setTimeout(getNewMessages, 1000);
			}
		});
}
function chatNew(id) {
	if(($.inArray(uid, chatBoxes) === -1))
		$(".__chat_friend_" + id).addClass("importantButton");
	else
		$(".__chat_friend_" + id).removeClass("importantButton");
	console.debug(chatBoxes);
}
function getChat(uid, func) {
	if($.inArray(uid, chatBoxes) !== -1) {
		var url = "chat.php?mode=get&uid=" + uid;
		$.ajax({
			url: url,
			context: $(".__chat_id_" + uid),
			cache: false,
			success: function(data) {
				var code = ".__chat_id_" + uid + " .chatBody";
				var child = code + " .inner";
				
				var height = $(child).outerHeight() - $(code).height();
				var scroll = $(code).scrollTop();
				
				$(code).children(".inner").html(data);
				if(height==scroll) $(code).animate({scrollTop:$(child).outerHeight()}, 250);
				
				if(func != null && func != false) func();
				
				setTimeout(function(){getChat(uid);}, 1250);
			}
		});	
	}
}
function postChat(uid, message) {
	var url = "chat.php?mode=post&uid=" + uid;
	$.post(url, {body:message}, function(data) {});
}
function openChat(uid) {
	if($.inArray(uid, chatBoxes) === -1) {
		chatBoxes.push(uid);
		
		getInfo(uid, "name", function(name) {
			var html = '';
			html += '<div class="chatNormal __chat_id_' + uid + '">';
			html += '<div class="chatBar"><strong>' + name + '</strong><a href="#" onclick="closeChat(' + uid + ');">X</a></div>';
			html += '<div class="chatBody"><div class="inner"></div></div>';
			html += '<div><form action="chat.php" method="post"><input type="text" class="textbox chatTextMessage" style="width:176px;border:1px solid #AAA;" /><input class="chatTextID" type="hidden" value="' + uid + '" /></form></div>';
			html += '</div>';
			
			$("#chatBar").append(html);
			
			getChat(uid, function() {			
				var code = ".__chat_id_" + uid + " .chatBody";
				var child = code + " .inner";
				
				$(code).scrollTop($(child).outerHeight());
			});
			
			$(".__chat_friend_" + uid).removeClass("importantButton");
			
			saveChat();
		});
	}
}
function closeChat(uid) {
	chatBoxes.removeData(uid);
	$(".__chat_id_" + uid).remove();
	saveChat();
}
function saveChat() {
	setCookie("chat_open", chatBoxes.join(","), 365);
}
function loadChat() {
	var cookie = getCookie("chat_open");
	if(cookie != null && cookie != "") {
		$.each(cookie.split(","), function(i, v) {
			openChat(v);
		});
	}
}
function chatFriends(load) {
	var cookie = getCookie("friends_open");
	if(cookie == "" || cookie == null) cookie = false;
	if(load !== true) {
		if(cookie == "1") cookie = "0";
		else cookie = "1";
		setCookie("friends_open", cookie, 365);
	}
	if(cookie == "1") $("#chatUsers").height(24).css({marginTop:252});
	else $("#chatUsers").height(276).css({marginTop:0});
}