<?php

$lang = array();

// Identifier

$lang[".id_code"] = "en";
$lang[".id_name"] = "English";

// Formats

$lang[".format_time"] = "h:i A";
$lang[".format_date"] = "M d, Y";

// Messages

$lang["date_yesterday"] = "Yesterday";
$lang["search_for"] = "Search for \"%{search_query}\":";
$lang["unknown"] = "Unknown";
$lang["verified"] = "Verified";
$lang["apply_changes"] = "Apply Changes";

$lang["notification_friend_request"] = "You have %{count} new friend request(s).";
$lang["notification_friend_request_view"] = "View";
$lang["notification_messages"] = "You have unread messages.";
$lang["notification_messages_view"] = "Read";

$lang["menu_home"] = "Home";
$lang["menu_profile"] = "Profile";
$lang["menu_friends"] = "My Friends";
$lang["menu_post"] = "Post";
$lang["menu_login"] = "Login";
$lang["menu_logout"] = "Logout";
$lang["menu_register"] = "Register";
$lang["menu_search"] = "Search";
$lang["menu_terms"] = "Terms";

$lang["post_ok"] = "Post";
$lang["post_cancel"] = "Cancel";

$lang["home_allfriends"] = "All my friends";

$lang["login"] = "Login";
$lang["login_username"] = "Username";
$lang["login_password"] = "Password";
$lang["login_proceed"] = "Proceed";

$lang["registration"] = "Registration";
$lang["register_username"] = "Username";
$lang["register_password"] = "Password";
$lang["register_confirm"] = "Confirm";
$lang["register_email"] = "E-mail";
$lang["register_name"] = "Name";
$lang["register_description"] = "A short description about you";
$lang["register_proceed"] = "Proceed";

$lang["stream_updates_new"] = "You have new updates on your stream.";
$lang["stream_updates_display"] = "Display updates";
$lang["stream_loading"] = "Loading stream...";
$lang["stream_my"] = "My Stream";

$lang["friends"] = "Friends";
$lang["friends_my"] = "My Friends";
$lang["friends_requests"] = "Friend Requests";
$lang["friends_whotoadd"] = "Who to Add";
$lang["friends_no"] = "You have no friends yet!";
$lang["friends_norequests"] = "You have no friend requests.";
$lang["friends_pending"] = "Request pending...";
$lang["friends_accept"] = "Accept";
$lang["friends_deny"] = "Deny";
$lang["friends_add"] = "+ Friend";
$lang["friends_remove"] = "- Friend";

$lang["profile_friends"] = "%{name}'s friends";
$lang["profile_myfriends"] = "My Friends";
$lang["profile_edit"] = "Edit Profile";
$lang["profile_edit_note"] = "Note";
$lang["profile_edit_note_info"] = "File size limit: %{size}KB.";

$lang["chat_friends"] = "Chat";

// Errors

$lang["error_invalid_username"] = "Username must be alphanumeric and can't contain spaces.";
$lang["error_invalid_name"] = "You must input a name.";
$lang["error_invalid_password"] = "Passwords didn't match.";
$lang["error_invalid_email"] = "Invalid e-mail address.";
$lang["error_invalid_login"] = "Username and/or password don't match.";
$lang["error_username_taken"] = "An problem happened, this username and/or e-mail address may already be in use.";

// Pages

$lang["page_terms_title"] = "Terms of Service";
$lang["page_terms"] = "<h2>".htmlentities("Terms of Service")."</h2>".
"<p>".htmlentities("By using our services you agree to the following terms:")."</p><ul>".
"<li>".htmlentities("You will be responsible by all and any content you publish, like messages, pictures and profile information.")."</li>".
"<li>".htmlentities("You will be resposible by your account information, passwords cannot be recovered.")."</li>".
"<li>".htmlentities("You will not link or publish any spam, adult, offensive or prohibited related content, for example, piracy.")."</li>".
"<li>".htmlentities("You will not do anything that can break the service or it's users informations integrity.")."</li></ul>".
"<p>".htmlentities("By using our services you also agree that you are an human, and not any type of automated system, for example, an bot.")."</p>".
"<strong>".htmlentities("Not following these terms will result in the suspension of your account with or without any advice.")."</strong>";

?>