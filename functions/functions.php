<?php

if (!function_exists('mv_slider_options')) {
    function mv_slider_options()
    {
        $show_bullets = isset(MV_Slider_Settings::$options['mv_slider_bullets']) && MV_Slider_Settings::$options['mv_slider_bullets'] == 1 ? true : false;

        wp_enqueue_script(
            'mv-slider-options-js',
            MV_SLIDER_URL . 'vendor/flexslider/flexslider.js',
            array('jquery'),
            MV_SLIDER_VERSION,
            true
        );

        wp_localize_script('mv-slider-options-js', 'SLIDER_OPTIONS', array(
            'controlNav' => $show_bullets
        ));
    }
}
