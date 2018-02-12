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
class Api extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('Api_Helper');
    }

    public function create()
    {
        try {
            $data = getRequest();

            $bbb = new BigBlueButton();
            $createMeetingParams = new CreateMeetingParameters($data->meeting_id, $data->meeting_name);
            $createMeetingParams->setAttendeePassword($data->attendee_password);
            $createMeetingParams->setModeratorPassword($data->moderator_password);

            if (isset($data->is_recording)) {
                $createMeetingParams->setRecord(true);
                $createMeetingParams->setAllowStartStopRecording(true);
                $createMeetingParams->setAutoStartRecording(true);
            }

            $response = $bbb->createMeeting($createMeetingParams);

            if ($response->getReturnCode() == 'FAILED') {
                return 'Can\'t create room! please contact our administrator.';
            } else {
                echo('<pre>');
                print_r($response);
                echo('</pre>');
            }
        } catch (\Exception $e) {
            return show_error('Internal error '.$e, 500);
        }
    }

    public function meeting()
    {
        try {
            $data = getRequest();

            $bbb = new BigBlueButton();
            $response = $bbb->getMeetings();

            if ($response->getReturnCode() == 'SUCCESS') {
                if (empty($response->getRawXml()->meetings)) {
                    echo json_encode($response->getRawXml()->message);
                }
                foreach ($response->getRawXml()->meetings->meeting as $meeting) {
                    echo('<pre>');
                    print_r($meeting);
                    echo('</pre>');
                }
            }
        } catch (\Exception $e) {
            return show_error('Internal error '.$e, 500);
        }
    }

    public function join()
    {
        try {
            $data = getRequest();
            $bbb = new BigBlueButton();
            $joinMeetingParams = new JoinMeetingParameters($data->meeting_id, $data->name, $data->password); // $moderator_password for moderator
            $joinMeetingParams->setRedirect(true);
            $url = $bbb->getJoinMeetingURL($joinMeetingParams);
            echo $url;
        } catch (\Exception $e) {
            return show_error('Internal error '.$e, 500);
        }
    }

    public function config()
    {
        try {
            $data = getRequest();
            putenv("BBB_SECURITY_SALT=$data->security_salt");
            putenv("BBB_SERVER_BASE_URL=$data->server_base_url");
        } catch (\Exception $e) {
            return show_error('Internal error '.$e, 500);
        }
    }
}
