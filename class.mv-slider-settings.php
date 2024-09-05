<?php

if (! class_exists('MV_SLIDER_SETTINGS')) {
    class MV_SLIDER_SETTINGS
    {
        public static $options;

        public function __construct()
        {
            self::$options = get_option('mv_slider_options');

            add_action('admin_init', array($this, 'admin_init'));
        }

        public function admin_init()
        {
            //Create a group to join all settings together
            register_setting('mv_slider_group', 'mv_slider_options', array($this, 'mv_slider_validate'));

            //First Section
            add_settings_section(
                'mv_slider_main_section', //id for the section
                'How does it work?', //Section title,
                null, //callback functions
                'mv_slider_page1' //page to add this section                  
            );

            add_settings_section(
                'mv_slider_second_section', //id for the section
                'Other Plugin Options', //Section title,
                null, //callback functions
                'mv_slider_page2' //page to add this section                  
            );

            add_settings_field(
                'mv_slider_shortcode', //id for the field
                'Shortcode', //Title of the field
                array($this, 'mv_slider_shortcode_callback'), //callback function
                'mv_slider_page1', //page to add this field,
                'mv_slider_main_section', //section to add this field
            );

            add_settings_field(
                'mv_slider_title', //id for the field
                'Slider Title', //Title of the field
                array($this, 'mv_slider_title_callback'), //callback function
                'mv_slider_page2', //page to add this field,
                'mv_slider_second_section', //section to add this field
            );

            add_settings_field(
                'mv_slider_bullets', //id for the field
                'Display Bullets', //Title of the field
                array($this, 'mv_slider_bullets_callback'), //callback function
                'mv_slider_page2', //page to add this field,
                'mv_slider_second_section', //section to add this field
            );

            add_settings_field(
                'mv_slider_style', //id for the field
                'Slider Style', //Title of the field
                array($this, 'mv_slider_style_callback'), //callback function
                'mv_slider_page2', //page to add this field,
                'mv_slider_second_section', //section to add this field
                array(
                    'items' => array(
                        'style-1' => 'style-1',
                        'style-2' => 'style-2'
                    ),
                    'label_for' => 'mv_slider_style'
                )
            );
        }

        public function mv_slider_shortcode_callback()
        { ?>
            <span>
                Use the shortcode [mv_slider] to display the slider in your posts or pages.
            </span>
        <?php
        }

        public function mv_slider_title_callback()
        {
        ?>
            <input
                type="text"
                name="mv_slider_options[mv_slider_title]"
                id="mv_slider_title"
                value="<?php echo isset(self::$options['mv_slider_title']) ? esc_attr(self::$options['mv_slider_title']) : '';  ?>">
        <?php
        }

        public function mv_slider_bullets_callback()
        {
        ?>
            <input
                type="checkbox"
                name="mv_slider_options[mv_slider_bullets]"
                id="mv_slider_bullets"
                value="1"
                <?php
                checked(1, isset(self::$options['mv_slider_bullets']) ? esc_attr(self::$options['mv_slider_bullets']) : '', true); ?>>
            <label for="mv_slider_bullets">Display bullets</label>
        <?php
        }

        public function mv_slider_style_callback($args)
        {
        ?>
            <select
                id="mv_slider_style"
                name="mv_slider_options[mv_slider_style]">

                <?php
                foreach ($args['items'] as $item) { ?>
                    <option value="<?php echo esc_attr($item); ?>"
                        <?php
                        isset(self::$options['mv_slider_style']) ? selected($item, self::$options['mv_slider_style'], true) : '';
                        ?>>
                        <?php echo esc_html(ucfirst($item)); ?>
                    </option>
                <?php
                }
                ?>
            </select>
<?php
        }

        public function mv_slider_validate($input)
        {
            $new_input = array();

            foreach ($input as $key => $value) {
                if (empty($value)) {
                    $value = 'Please type something';
                }
                $new_input[$key] = sanitize_text_field($value);
            }

            return $new_input;
        }
    }
}
