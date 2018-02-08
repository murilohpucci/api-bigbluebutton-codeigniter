<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\CreateMeetingParameters;
/*
* Validate api interface at: https://github.com/bigbluebutton/bigbluebutton-api-php
*/
class Api extends CI_Controller {

	public function index($securitySalt, $serverUrl)
	{
		putenv("BBB_SECURITY_SALT = ");
		putenv("BBB_SERVER_BASE_URL = ");

		$bbb                 = new BigBlueButton();
		$createMeetingParams = new CreateMeetingParameters('bbb-meeting-uid-65', 'BigBlueButton API Meeting');
		$response            = $bbb->createMeeting($createMeetingParams);
		
		echo "Created Meeting with ID: " . $response->getMeetingId();
		die();
	}
}
