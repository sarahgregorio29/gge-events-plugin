<?php

include_once str_replace("\\", "/", dirname(dirname(dirname(dirname(dirname(__FILE__))))))."/wp-load.php";
include_once str_replace("\\", "/", dirname(dirname(dirname(dirname(dirname(__FILE__))))))."/wp-includes/pluggable.php";

class Controller_Attendee {

    public function __construct()
    {
        $function = $_POST['type'];
        $this->$function();
    }

    private function register()
    {
        parse_str($_POST['data'], $data);
        $model_attendee = &GnGn::getInstance('Model_Attendee');
        $register_attendee = $model_attendee->register($data);

        if($register_attendee):
            $message['type'] = 'success';
        else:
            $message['type'] = 'error';
        endif;
        echo json_encode($message);
    }

    private function check_if_user_exist()
    {
        parse_str($_POST['data'], $data);
        $model_attendee = &GnGn::getInstance('Model_Attendee');
        $check_attendee = $model_attendee->check_if_user_exist($data);

        if(!empty($check_attendee) && is_array($check_attendee)):
            echo json_encode(true);
        else:
            echo json_encode(false);
        endif;
    }

    private function search_attendee()
    {
        $model_attendee = &GnGn::getInstance('Model_Attendee');
        $search_attendee = $model_attendee->get_all_attendees($_POST['data']['pid'], array('sort' => $_POST['data']['sort'], 'search' => $_POST['data']['search']));
        echo json_encode($search_attendee);
    }

    private function remove_attendee()
    {
        $model_attendee = &GnGn::getInstance('Model_Attendee');
        $remove_attendee = $model_attendee->remove_attendee($_POST['data']);
        echo json_encode($remove_attendee);
    }
}

new Controller_Attendee;