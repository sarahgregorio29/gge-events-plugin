<?php
include_once str_replace("\\", "/", dirname(dirname(__FILE__)))."/helper/post.php";

class Post_Event extends Helper_Post{
    
    public function __construct(){  
        //PROPERTIES//
        $this->id       = "event";
        $this->name     = "GnGn Event"; 
        $this->pname    = "GnGn Events";
        $this->supports = array( " " );
        
        parent::__construct();

        add_filter('cmb_meta_boxes', array($this, 'metaboxes'));
        add_action('init', array($this, 'metabox_init'), 9999);
        add_filter('manage_edit-event_columns', array($this, 'add_new_event_columns'));
        add_action('manage_event_posts_custom_column', array($this, 'manage_event_columns'), 10, 2);
    }

    function metaboxes(array $meta_boxes) {
        
        $prefix = 'event_';
        $meta_boxes[] = array(
            'id'         => 'event',
            'title'      => 'GnGn Event',
            'pages'      => array( 'event', ), // Post type
            'context'    => 'normal',
            'priority'   => 'high',
            'show_names' => true, // Show field names on the left       
            'fields'     => array(
                array(
                    'name' => 'Event Title',
                    'desc' => "The event title",
                    'id'   => 'post_title',
                    'type' => 'text',
                ),
                array(
                    'name' => 'Event Announcement',
                    'desc' => 'Event announcement',
                    'id'   => 'post_content',
                    'type' => 'wysiwyg',
                ),
                array(
                    'name' => 'Event Date',
                    'desc' => 'Date of the event',
                    'id'   => sprintf('%s%s', $prefix, 'date'),
                    'type' => 'wysiwyg',
                ),
                array(
                    'name' => 'Event Theme',
                    'desc' => 'Theme of the event',
                    'id'   => sprintf('%s%s', $prefix, 'theme'),
                    'type' => 'wysiwyg',
                ),
                array(
                    'name' => 'Event Description',
                    'desc' => 'More information about the event',
                    'id'   => sprintf('%s%s', $prefix, 'description'),
                    'type' => 'wysiwyg',
                ),
                array(
                    'name' => 'Event Venue',
                    'desc' => 'Venue of the event',
                    'id'   => sprintf('%s%s', $prefix, 'venue'),
                    'type' => 'wysiwyg',
                ),
                array(
                    'name' => 'Registration Duration',
                    'desc' => 'Start and end date of registration',
                    'id'   => sprintf('%s%s', $prefix, 'start'),
                    'type' => 'wysiwyg',
                ),
                array(
                    'name' => 'Event Thumbnails',
                    'desc' => 'Upload thumbnails using Add Media Button then add images on the textbox above.',
                    'id'   => sprintf('%s%s', $prefix, 'thumbnails'),
                    'type' => 'wysiwyg',
                ),
                array(
                    'name' => 'Event Contest',
                    'desc' => 'Information about the contest.',
                    'id'   => sprintf('%s%s', $prefix, 'contest'),
                    'type' => 'wysiwyg',
                ),
                array(
                    'name' => 'Background Music',
                    'desc' => 'Link of the mp3 file',
                    'id'   => sprintf('%s%s', $prefix, 'music'),
                    'type' => 'text',
                )
            )
        );
        
        return $meta_boxes;
    }

    function metabox_init(){
        if ( ! class_exists( 'cmb_Meta_Box' ) ) require_once str_replace("\\", "/", dirname(dirname(__FILE__)))."/helper/init.php";;
    }

    function add_new_event_columns($event_columns) {
        $new_columns['title'] = __('Event Name');
        $new_columns['view_attendees'] = __('View Attendees');
        $new_columns['date'] = __('Date');
        return $new_columns;
    }

    function manage_event_columns($column_name, $id) {
        global $wpdb;
        switch ($column_name) {
        case 'view_attendees':
            printf('<a href="%s/%s/?event_id=%s">View Attendees</a>', site_url(), 'event-attendees', $id);
            break;
        } // end switch
    }   
}