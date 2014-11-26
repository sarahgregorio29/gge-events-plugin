<?php

/*
    OFFICE_BRANCH : 2 => Makati, 1 => Clark
*/

include_once str_replace("\\", "/", dirname(dirname(dirname(dirname(dirname(__FILE__))))))."/wp-load.php";

class Model_Attendee {

    public function __construct() 
    {

    }

    /**
     * This method will register the attendee in the event.
     * This method will check if the attendee is already in the database.
     * If attendee is already registered based on floor name, his data will be updated.
     */
    function register($data)
    {
        global $wpdb;
        $id = "";

        // Check if floor name exist
        $sql = sprintf('Select attendee_id From %sattendee Where floor_name = "%s"', $wpdb->prefix, $data['floor_name']);
        $attendee = $wpdb->get_results($sql, ARRAY_A);

        if(!empty($attendee) && is_array($attendee)):
            // If floor name exist, just update info of attendee
            $wpdb->update(sprintf('%sattendee', $wpdb->prefix), 
                array(
                    'firstname' => $data['fname'],
                    'middlename' => $data['mname'],
                    'lastname' => $data['lname'],
                    'email_address' => $data['email'],
                    'contact_no' => $data['contact'],
                    'office_branch' =>  $data['branch']
                ), 
                array("attendee_id" => $attendee[0]['attendee_id']));
            $id = $attendee[0]['attendee_id'];
        else:
            // If floor name does not exist, create attendee info
            $wpdb->insert( 
                'wp_attendee', 
                array(
                    'firstname' => $data['fname'],
                    'middlename' => $data['mname'],
                    'lastname' => $data['lname'],
                    'email_address' => $data['email'],
                    'contact_no' => $data['contact'],
                    'floor_name' => strtoupper($data['floor_name']),
                    'office_branch' =>  $data['branch']
                ) 
            );
            $id = $wpdb->insert_id;
        endif;

        // Register user to event
        if(!empty($id)):
            $event_id = $data['pid'];
            $wpdb->insert( 
                'wp_registration', 
                array(
                    'event_id' => $event_id,
                    'attendee_id' => $id,
                    'registration_date' => date('Y-m-d H:i:s')
                ) 
            );
        endif;
        return $id;
    }

    /**
     * This method will check if attendee is already 
     * registered in the event.
     */
    function check_if_user_exist($data)
    {
        global $wpdb;
        $sql = sprintf('Select att.floor_name 
                            From %1$sattendee att
                                Inner Join %1$sregistration reg
                                    On att.attendee_id = reg.attendee_id
                                        Where att.floor_name = "%2$s" AND reg.event_id = %3$d', $wpdb->prefix, $data['floor_name'], $data['pid']);
        $attendee = $wpdb->get_results($sql, ARRAY_A);
        return $attendee;
    }

    /**
     * This method will return all the
     * attendees registered in the event.
     */
    function get_all_attendees($event_id, $filter)
    {
        global $wpdb;

        $sort = (!empty($filter['sort'])) ? $filter['sort'] : 'floor_name';
        $name = (!empty($filter['search'])) ? sprintf('And (firstname Like "%%%1$s%%" Or floor_name Like "%%%1$s%%" Or lastname Like "%%%1$s%%")', $filter['search']) : '';

        $sql = sprintf('Select att.attendee_id, att.firstname, att.middlename, att.lastname, att.office_branch, att.floor_name, att.email_address, att.contact_no
                            From %1$sattendee att
                                Inner Join %1$sregistration reg
                                    On att.attendee_id = reg.attendee_id
                                        Where reg.event_id = %2$d %4$s
                                            Order by att.%3$s', $wpdb->prefix, $event_id, $sort, $name);
        $attendees = $wpdb->get_results($sql, ARRAY_A);
        return $attendees;
    }

    /**
     * This method will remove the employee
     * from the event.
     */
    function remove_attendee($data)
    {
        global $wpdb;
        $delete = $wpdb->delete(sprintf('%sregistration', $wpdb->prefix), array('attendee_id' => $data['att'], 'event_id' => $data['pid']));
        return $data['att'];
    }
}