<?php
defined('BASEPATH') or exit('No direct script access allowed');

use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\CreateMeetingParameters;
use BigBlueButton\Parameters\JoinMeetingParameters;
use BigBlueButton\Parameters\EndMeetingParameters;
use BigBlueButton\Parameters\GetMeetingInfoParameters;
use BigBlueButton\Parameters\DeleteRecordingsParameters;

/*
* Validate api interface at: https://github.com/bigbluebutton/bigbluebutton-api-php
*/
class Webconference extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function create()
    {
        try {
            $data = json_decode(file_get_contents('php://input'));
            if (is_null($data) || empty($data)) {
                return show_error('Data not found!', 404);
            }

            putenv("BBB_SECURITY_SALT = ");
            putenv("BBB_SERVER_BASE_URL = ");

            $bbb = new BigBlueButton();
            $createMeetingParams = new CreateMeetingParameters($meetingID, $meetingName);
            $createMeetingParams->setAttendeePassword($attendee_password);
            $createMeetingParams->setModeratorPassword($moderator_password);
            $createMeetingParams->setDuration($duration);
            $createMeetingParams->setLogoutUrl($urlLogout);

            if ($isRecordingTrue) {
                $createMeetingParams->setRecord(true);
                $createMeetingParams->setAllowStartStopRecording(true);
                $createMeetingParams->setAutoStartRecording(true);
            }

            $response = $bbb->createMeeting($createMeetingParams);

            if ($response->getReturnCode() == 'FAILED') {
                return 'Can\'t create room! please contact our administrator.';
            } else {
                // process after room created
            }
        } catch (\Exception $e) {
            return show_error('Internal error '.$e, 500);
        }
    }
}
