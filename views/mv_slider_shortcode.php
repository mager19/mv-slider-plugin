<h3><?php
    echo (!empty($content)) ? esc_html($content) : esc_html(MV_SLIDER_SETTINGS::$options['mv_slider_title']);
    ?></h3>

<div class="mv-slider flexslider <?php echo (isset(MV_Slider_Settings::$options['mv_slider_style'])) ? esc_attr(MV_Slider_Settings::$options['mv_slider_style']) : 'style-1';  ?>">
    <ul class="slides">
        <?php
        $args = array(
            'post_type' => 'mv-slider',
            'post_status' => 'publish',
            'post__in' => $id,
            'orderby' => $orderby,
        );

        $myquery = new WP_Query($args);

        if ($myquery->have_posts()):
            while ($myquery->have_posts()) : $myquery->the_post();

                $button_text = get_post_meta(get_the_ID(), 'mv_slider_link_text', true);
                $button_url = get_post_meta(get_the_ID(), 'mv_slider_link_url', true);
        ?>
                <li>
                    <?php
                    if (has_post_thumbnail()) {
                        the_post_thumbnail('full', array('class' => 'img-fluid')); ?>
                    <?php } else {
                        echo '<img src="' . MV_SLIDER_URL . 'assets/images/default.jpg' . '" alt="' . esc_attr(get_the_title()) . '" class="img-fluid wp-post-image" />';
                    } ?>
                    <div clast="mvs-container">
                        <div class="slider-details-container">
                            <div class="wrapper">
                                <div class="slider-title">
                                    <h2><?php the_title(); ?></h2>
                                </div>
                                <div class="slider-description">
                                    <div class="subtitle">
                                        <?php the_content(); ?>
                                    </div>
                                    <a class="link" href="<?php echo esc_url($button_url); ?>">
                                        <?php echo esc_html($button_text); ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
        <?php
            endwhile;
            wp_reset_postdata();
        endif;
        ?>
    </ul>
</div>