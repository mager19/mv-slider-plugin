<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    <?php
    $active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'main_options';
    ?>
    <h2 class="nav-tab-wrapper">
        <a href="?page=mv_slider_admin&tab=main_options" class="nav-tab <?php echo $active_tab == 'main_options' ? 'nav-tab-active' : ''; ?>">Main Options</a>
        <a href="?page=mv_slider_admin&tab=additional_options" class="nav-tab <?php echo $active_tab == 'additional_options' ? 'nav-tab-active' : ''; ?>">Additional Options</a>
    </h2>
    <form method="post" action="options.php">
        <?php

        if ($active_tab == 'main_options') {
            settings_fields('mv_slider_group'); //name of group
            do_settings_sections('mv_slider_page1'); //name of page
        } else {
            settings_fields('mv_slider_group'); //name of group
            do_settings_sections('mv_slider_page2'); //name of page
        }
        // Output save settings button.
        submit_button('Save Settings');
        ?>
    </form>
</div>