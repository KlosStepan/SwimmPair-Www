<?php
require '../start.php';

$currID = Sanitizer::getGetInt('id');

$nextPost = $postsManager->GetFollowingPost($currID);

if($nextPost!=NULL)
{
	$nextPost->timestamp = date("d-m-Y", strtotime($nextPost->timestamp));

	//If null, "null" means Public Service Announcement
	if ($nextPost->author_user_id == null)
	{
		$nextPost->author_user_id = "null";
	}
	else
	{
		//Else Get user's name and send it
		$nextPost->author_user_id = $usersManager->GetUserFullNameByID($nextPost->author_user_id);
	}

	//Return
	$_serializedPostJSON = $nextPost->Serialize();
	echo $_serializedPostJSON;
}
else
{
	//echo "{\"id\":\"null\",\"timestamp\":\"null\",\"title\":\"null\",\"content\":\"null\",\"display_flag\":\"null\",\"author_user_id\":\"null\",\"signature_flag\":\"null\"}";
	echo "{\"XHRCallResult\":\"null\"}";
}
