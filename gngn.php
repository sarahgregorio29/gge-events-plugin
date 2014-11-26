<?php
/**
 * @package GnGn
 */
/*
Plugin Name: GnGn Even Registration
Plugin URI: http://gge.co.jp
Description: GnGn Event Registration is a plug-in that provides functions to the web site that was created in WordPress.
Version: 1.0
Author: GnGn Development Team
Author URI: http://gge.co.jp
License: GPLv2 or later

Copyright 2013 GnGn Development Team  (email : sarahgregorio29@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  udm_set_agent_param(agent, var, val)
*/

class GnGn {

    public function __construct()
    {   
        // LOAD SCRIPTS
        add_action( 'init', array( &$this, 'load_scripts' )); 

        // SETUP SHORTCODES FOR ADMIN
        add_action('after_setup_theme', array(&$this, 'actionAfterSetupTheme'));  

        // Create new custom post type for event
        add_action( 'init', array(&$this, 'custom_post_type'));

        // Allow post thumbnails for event
        add_theme_support( 'post-thumbnails', array( 'post', 'event')); 
    }

    /**
     * This method will be return instance from $className
     *
     * How to call:
     * $student = &GnGn::getInstance('Controller_Frontend_Student');
     * $student->mypage();
     */
    public static function &getInstance($className)
    {
        // This like object cache
        static $instances = array();

        if (!isset($instances[$className])):

            $filePath = sprintf('%s/%s.php', dirname(__FILE__), strtolower(str_replace('_', '/', $className)));
            if (!class_exists($className)):
                if (!file_exists($filePath)):
                    throw new Exception(sprintf('File not found. [%s]', $filePath));
                endif;
                include_once $filePath;
            endif;
            $instances[$className] = new $className;
        endif;
        return $instances[$className];
    }

    public function actionAfterSetupTheme()
    {
        $plugin_shortcodes = &self::getInstance('Library_Shortcodes');
        $plugin_shortcodes::activation();
    }

    public function custom_post_type()
    {
        self::getInstance('Post_Event');
    }

    public function load_scripts()
    {
        $ver = '1.0';

        // Register scripts
        wp_register_script('gngn', sprintf('%s/%s', get_bloginfo('template_url'), '/js/gngn.js'), false, null, true);

        // For either a plugin or a theme, you can then enqueue the script:
        wp_enqueue_script('gngn');
    }
}

new GnGn();