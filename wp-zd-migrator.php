<?php
/*
Plugin Name: Posts and Pages to Zendesk Helpcenter 
Plugin URI: https://github.com/gabemasiulis/wp-zd-migrator
Author: Gabe Masiulis
Author URI: https://masiul.is
Version: 0.1.0
Description: Export your posts and pages as Zendesk Helpcenter articles
License: GPL Version 3.0 - http://www.gnu.org/licenses/gpl.html
*/
function wpzd_init(){
    // register_setting( string $option_group, string $option_name, array $args = array() )
    // https://developer.wordpress.org/reference/functions/register_setting/
    register_setting( 'wpzd', 'wpzd_options' );

    add_settings_section(
        'wpzd_section',
        __('Enter your Zendesk Admin Credentials.', 'wpzd'),
        'wpzd_section_cb',
        'wpzd'
    );

    add_settings_field(
        'wpzd_field_zduser',
        __( 'Zendesk User', 'wpzd'),
        'wpzd_field_zduser_cb',
        'wpzd',
        'wpzd_section',
        [
            'label_for'         => 'wpzd_field_zduser',
            'class'             => 'wpzd_row',
            'wpzd_custom_data'  => 'custom'
        ]
    );
    // add_settings_field(
    //     'wpzd_field_zdpass',
    //     __( 'Zendesk Password', 'wpzd'),
    //     'wpzd_field_zdpass_cb',
    //     'wpzd',
    //     'wpzd_section',
    //     [
    //         'label_for'         => 'wpzd_field_zdpass',
    //         'class'             => 'wpzd_row',
    //         'wpzd_custom_data'  => 'custom'
    //     ]
    // );
}

add_action ( 'admin_init', 'wpzd_init' );

function wpzd_section_cb( $args ){
    ?>
    <p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'Your credentials will not be stored after export, and are not sent anywehre besides Zendesk', 'wpzd' ); ?></p>
    <?php
}

function wpzd_field_zduser_cb( $args ) {

}
function wpzd_options_page() {
    add_menu_page(
        'Posts and Pages to Zendesk Helpcenter',
        'Helpcenter Exporter',
        'manage_options',
        'wpzd',
        'wpzd_page_html',
        'data:image/svg+xml;base64,PHN2ZyBhcmlhLWxhYmVsbGVkYnk9InNpbXBsZWljb25zLXplbmRlc2staWNvbiIgcm9sZT0iaW1nIiB2aWV3Qm94PSIwIDAgMjQgMjQiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PHRpdGxlIGlkPSJzaW1wbGVpY29ucy16ZW5kZXNrLWljb24iPlplbmRlc2sgaWNvbjwvdGl0bGU+PHBhdGggZD0iTTExLjA4NSAyMS4wOTVIMEwxMS4wODUgNy43MTJ2MTMuMzgzem0xMi45MTUgMEgxMi45MTVjMC0zLjA2MyAyLjQ3OS01LjU0MyA1LjU0My01LjU0MyAzLjA2MyAwIDUuNTQyIDIuNDgyIDUuNTQyIDUuNTQzem0tMTEuMDg1LTQuODA0VjIuOTA1SDI0TDEyLjkxNSAxNi4yOTF6bS0xLjgzLTEzLjM4NmMwIDMuMDYxLTIuNDgxIDUuNTQ0LTUuNTQzIDUuNTQ0QzIuNDgyIDguNDQ5IDAgNS45NjggMCAyLjkwN2gxMS4wODV2LS4wMDJ6IiBmaWxsPSJibGFjayIvPjwvc3ZnPg=='
    )
}

add_action( 'admin_menu', 'wpzd_options_page' );

function wpzd_page_html() {
    // check user capabilities
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }
    // add error/update messages
    
    // check if the user have submitted the settings
    // wordpress will add the "settings-updated" $_GET parameter to the url
    if ( isset( $_GET['settings-updated'] ) ) {
    // add settings saved message with the class of "updated"
    add_settings_error( 'wpzd_messages', 'wpzd_message', __( 'Settings Saved', 'wpzd' ), 'updated' );
    }
    // show error/update messages
    settings_errors( 'wpzd_messages' );
    ?>
    <div class="wrap">
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
    <form action="options.php" method="post">
    <?php
    // output security fields for the registered setting "wpzd"
    settings_fields( 'wpzd' );
    // output setting sections and their fields
    // (sections are registered for "wpzd", each field is registered to a specific section)
    do_settings_sections( 'wpzd' );
    // output save settings button
    submit_button( 'Save Settings' );
    ?>
    </form>
    </div>
    <?php
}