<?php

/**
 *
 * Plugin Name: MV Slider
 * Description: A simple slider plugin for WordPress
 * Plugin URI: https://wordpress.org
 * Version: 1.0
 * Requires at least: 5.6
 * Author: Mario Reyes
 * Author URI: https://wordpress.org
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: mv-slider
 * Domain Path: /languages
 */

/*
/*
MVSLIDER is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

MVSLIDER is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with MVSLIDER. If not, see {URI to Plugin License}.
*/

if (!defined("ABSPATH")) {
    die("You are not allowed to call this page directly.");
    exit();
}

if (!class_exists("MV_Slider")) {
    class MV_Slider
    {
        function __construct()
        {
            $this->define_constants();

            require_once(MV_SLIDER_PATH . "functions/functions.php");

            add_action('admin_menu', array($this, 'add_menu'));

            require_once(MV_SLIDER_PATH  . "post-types/mv-slider-cpt.php");
            $MV_Slider_Post_Type = new MV_Slider_Post_Type();

            require_once(MV_SLIDER_PATH . "class.mv-slider-settings.php");
            $MV_Slider_Settings = new MV_SLIDER_SETTINGS();

            require_once(MV_SLIDER_PATH . "shortcodes/class.mv-slider-shortcode.php");
            $MV_Slider_Shortcode = new MV_Slider_Shortcode();

            add_action('wp_enqueue_scripts', array($this, 'register_scripts'), 999);
            add_action('admin_enqueue_scripts', array($this, 'register_admin_scripts'));
        }

        // Define constans
        public function define_constants()
        {
            // Define path, URL and version to the plugin
            define("MV_SLIDER_PATH", plugin_dir_path(__FILE__));
            define("MV_SLIDER_URL", plugin_dir_url(__FILE__));
            define("MV_SLIDER_VERSION", "1.0.0");
        }

        public static function activate()
        {
            //rewrite permalinks
            update_option("rewrites_rules", "");
        }

        public static function deactivate()
        {
            flush_rewrite_rules();
            unregister_post_type('mv-slider');
        }

        public static function uninstall()
        {
            delete_option('mv_slider_options');
            $posts = get_posts(
                array(
                    'post_type' => 'mv-slider',
                    'numberposts' => -1,
                    'post_status' => 'any'
                )
            );

            foreach ($posts as $post) {
                wp_delete_post($post->ID, true);
            }
        }

        public function add_menu()
        {
            add_menu_page(
                "MV Slider Options",
                "MV Slider",
                "manage_options",
                "mv_slider_admin",
                array($this, "mv_slider_settings_page"),
                "dashicons-format-gallery",
            );

            add_submenu_page(
                "mv_slider_admin",
                "Manage Slides",
                "Manage Slider",
                "manage_options",
                "edit.php?post_type=mv-slider",
                null,
                null
            );

            add_submenu_page(
                "mv_slider_admin",
                "Add New Slide",
                "Add New Slide",
                "manage_options",
                "post-new.php?post_type=mv-slider",
                null,
                null
            );
        }

        public function mv_slider_settings_page()
        {
            //check if the user has the right permissions
            if (! current_user_can("manage_options")) {
                return;
            }

            //add error/update messages
            if (isset($_GET["settings-updated"])) {
                add_settings_error("mv_slider_options", "mv_slider_message", "Settings Saved", "success");
            }
            settings_errors('mv_slider_options');

            require(MV_SLIDER_PATH . "views/settings-page.php");
        }

        public function register_scripts()
        {
            wp_register_script(
                'mv-slider-main-jq',
                MV_SLIDER_URL . 'vendor/flexslider/jquery.flexslider-min.js',
                array('jquery'),
                MV_SLIDER_VERSION,
                true
            );
            wp_register_style(
                'mv-slider-main-css',
                MV_SLIDER_URL . 'vendor/flexslider/flexslider.css',
                array(),
                MV_SLIDER_VERSION,
                'all'
            );

            wp_register_style('mv-slider-style-css', MV_SLIDER_URL . 'assets/css/frontend.css', array(), MV_SLIDER_VERSION, 'all');
        }

        public function register_admin_scripts()
        {
            global $typenow;

            if ($typenow == 'mv-slider') {
                wp_enqueue_style('mv-slider-admin', MV_SLIDER_URL . 'assets/css/admin.css');
            }
        }
    }
}

if (class_exists("MV_Slider")) {
    register_activation_hook(__FILE__, ["MV_Slider", "activate"]);
    register_deactivation_hook(__FILE__, ["MV_Slider", "deactivate"]);
    register_uninstall_hook(__FILE__, ["MV_Slider", "uninstall"]);
    $mv_slider = new MV_Slider();
}
