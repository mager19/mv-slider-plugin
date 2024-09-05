<?php
if (!class_exists("MV_Slider_Post_Type")) {
    class MV_Slider_Post_Type
    {
        function __construct()
        {
            add_action("init", array($this, "create_post_type"));
            add_action("add_meta_boxes", array($this, "add_meta_boxes"));
            add_action("save_post", array($this, "save_post"), 10, 2);
            add_filter('manage_mv-slider_posts_columns', array($this, 'mv_slider_cpt_columns'));
            add_action('manage_mv-slider_posts_custom_column', array($this, 'mv_slider_custom_columns'), 10, 2);
            add_filter('manage_edit-mv-slider_sortable_columns', array($this, 'mv_slider_sortable_columns'));
        }

        public function create_post_type()
        {
            register_post_type("mv-slider", [
                "label" => "Slider",
                "description" => "Slider post",
                "labels" => array(
                    "name" => "Sliders",
                    "singular_name" => "Slider",
                ),
                "public" => true,
                "supports" => array("title", "editor", "thumbnail"),
                "hierarchical" => false,
                "show_ui" => true,
                "show_in_menu" => false,
                "menu_position" => 5,
                "show_in_admin_bar" => true,
                "show_in_nav_menus" => true,
                "can_export" => true,
                "has_archive" => false,
                "exclude_from_search" => false,
                "publicly_queryable" => true,
                "show_in_rest" => true,
                'menu_icon' => 'data:image/svg+xml;base64,' . base64_encode('<svg width="20" height="20" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path fill="black" d="M1591 1448q56 89 21.5 152.5t-140.5 63.5h-1152q-106 0-140.5-63.5t21.5-152.5l503-793v-399h-64q-26 0-45-19t-19-45 19-45 45-19h512q26 0 45 19t19 45-19 45-45 19h-64v399zm-779-725l-272 429h712l-272-429-20-31v-436h-128v436z"/></svg>')
            ]);
        }

        public function mv_slider_cpt_columns($columns)
        {
            $columns['mv_slider_link_text'] = esc_html__('Link Text', 'mv-slider');
            $columns['mv_slider_link_url'] = esc_html__('Link URL', 'mv-slider');

            return $columns;
        }

        public function mv_slider_custom_columns($columns, $post_id)
        {
            switch ($columns) {
                case 'mv_slider_link_text':
                    echo esc_html(get_post_meta($post_id, 'mv_slider_link_text', true));
                    break;
                case 'mv_slider_link_url':
                    echo esc_url(get_post_meta($post_id, 'mv_slider_link_url', true));
                    break;
            }
        }

        public function mv_slider_sortable_columns($columns)
        {
            $columns['mv_slider_link_text'] = 'mv_slider_link_text';
            return $columns;
        }

        public function add_meta_boxes()
        {
            add_meta_box(
                "mv_slider_meta_box",
                "Link Options",
                array($this, "add_inner_meta_boxes"),
                "mv-slider",
                "normal",
                "high"
            );
        }

        public function add_inner_meta_boxes($post)
        {
            require_once(MV_SLIDER_PATH  . "views/mv-slider_metabox.php");
        }

        public function save_post($post_id)
        {
            //verificamos nonce 
            // verificamos si existe y no es null
            if (!isset($_POST['mv_slider_nonce'])) {
                //verificamos si el valor coincide
                if (!wp_verify_nonce($_POST['mv_slider_nonce'], 'mv_slider_nonce')) {
                    return;
                }
            }

            //verificamos el auto save
            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                return;
            }

            //verificamos el cpt
            if (isset($_POST['post_type']) && $_POST['post_type'] == 'mv-slider') {
                if (!current_user_can('edit_page', $post_id)) {
                    return;
                } elseif (!current_user_can('edit_post', $post_id)) {
                    return;
                }
            }

            // comprueba si el action post es igual a editpost
            if (isset($_POST['action']) && $_POST['action'] == 'editpost') {
                $old_link_text = get_post_meta($post_id, 'mv_slider_link_text', true);
                $new_link_text = sanitize_text_field($_POST['mv_slider_link_text']);
                $old_link_url = get_post_meta($post_id, 'mv_slider_link_url', true);
                $new_link_url = $_POST['mv_slider_link_url'];

                if (empty($new_link_text)) {
                    update_post_meta($post_id, 'mv_slider_link_text', 'Add some text');
                } else {
                    update_post_meta($post_id, 'mv_slider_link_text', $new_link_text, $old_link_text);
                }

                if (empty($new_link_url)) {
                    update_post_meta($post_id, 'mv_slider_link_url', '#');
                } else {
                    update_post_meta($post_id, 'mv_slider_link_url', esc_url_raw($new_link_url), $old_link_url);
                }
            }
        }
    }
}
