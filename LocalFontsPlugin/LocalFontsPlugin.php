<?php
/*
Plugin Name: Local Fonts
Plugin URI: https://github.com/vchrisb/wpLocalFonts
Description: Plugin to host Fonts (Roboto, Roboto Condensed, Lobster, Montserrat, Open Sans) locally and optionally add to Divi's font menu
Version: 1.1.2
Author: Christopher Banck
Author URI: https://banck.net
Requires PHP: 7.4
Requires at least: 5.8
License: MIT License
License URI: https://github.com/vchrisb/wpLocalFonts/blob/main/LICENSE
*/

/* Start Adding Functions Below this Line */

$LocalFonts_options_defaults = array(
    'divi' => '',
    'roboto' => '',
    'robotocondensed' => '',
    'lobster' => '',
    'montserrat' => '',
    'opensans' => '',
  );

// Register Style
function LocalFonts_add_style()
{
    global $LocalFonts_options_defaults;
    $LocalFonts_options = wp_parse_args(get_option('LocalFonts_options'), $LocalFonts_options_defaults);
    if ($LocalFonts_options['roboto'] === "1") {
        wp_enqueue_style('LocalFontsPluginRoboto', plugin_dir_url(__FILE__) . 'assets/css/roboto.css');
    }
    if ($LocalFonts_options['robotocondensed'] === "1") {
        wp_enqueue_style('LocalFontsPluginRobotoCondensed', plugin_dir_url(__FILE__) . 'assets/css/robotocondensed.css');
    }
    if ($LocalFonts_options['lobster'] === "1") {
        wp_enqueue_style('LocalFontsPluginLobster', plugin_dir_url(__FILE__) . 'assets/css/lobster.css');
    }
    if ($LocalFonts_options['montserrat'] === "1") {
        wp_enqueue_style('LocalFontsPluginMontserrat', plugin_dir_url(__FILE__) . 'assets/css/montserrat.css');
    }
    if ($LocalFonts_options['opensans'] === "1") {
        wp_enqueue_style('LocalFontsPluginOpenSans', plugin_dir_url(__FILE__) . 'assets/css/opensans.css');
    }
}

// Add to Divi Font Menu
function LocalFonts_add_divi($fonts)
{
    global $LocalFonts_options_defaults;
    $LocalFonts_options = wp_parse_args(get_option('LocalFonts_options'), $LocalFonts_options_defaults);
    $custom_fonts = array();
    if ($LocalFonts_options['roboto'] === "1") {
        $font = array('Roboto' => array(
                'styles'        => '100italic,300italic,400italic,700italic,900italic,100,300,400,700,900',
                'character_set' => 'latin',
                'type'          => 'sans-serif',
                'standard'      => 1
            ));
        $custom_fonts = array_merge($font, $custom_fonts);
    }
    if ($LocalFonts_options['robotocondensed'] === "1") {
        $font = array('Roboto Condensed' => array(
                'styles'        => '300italic,400italic,700italic,300,400,700',
                'character_set' => 'latin',
                'type'          => 'sans-serif',
                'standard'      => 1
            ));
        $custom_fonts = array_merge($font, $custom_fonts);
    }
    if ($LocalFonts_options['lobster'] === "1") {
        $font = array('Lobster' => array(
                'styles'        => '400',
                'character_set' => 'latin',
                'type'          => 'sans-serif',
                'standard'      => 1
            ));
        $custom_fonts = array_merge($font, $custom_fonts);
    }
    if ($LocalFonts_options['montserrat'] === "1") {
        $font = array('Montserrat' => array(
                'styles'        => '100italic,300italic,400italic,700italic,900italic,100,300,400,700,900',
                'character_set' => 'latin',
                'type'          => 'sans-serif',
                'standard'      => 1
            ));
        $custom_fonts = array_merge($font, $custom_fonts);
    }
    if ($LocalFonts_options['opensans'] === "1") {
        $font = array('Open Sans' => array(
                'styles'        => '300italic,400italic,700italic,300,400,700',
                'character_set' => 'latin',
                'type'          => 'sans-serif',
                'standard'      => 1
            ));
        $custom_fonts = array_merge($font, $custom_fonts);
    }
    return array_merge($custom_fonts, $fonts);
}

$LocalFonts_options = wp_parse_args(get_option('LocalFonts_options'), $LocalFonts_options_defaults);
if ($LocalFonts_options['divi'] === "1") {
    add_filter('et_websafe_fonts', 'LocalFonts_add_divi', 10, 2);
}
add_action('wp_head', 'LocalFonts_add_style');

class LocalFonts
{
    private $LocalFonts_options;

    public function __construct()
    {
        add_action('admin_menu', array( $this, 'LocalFonts_add_plugin_page' ));
        add_action('admin_init', array( $this, 'LocalFonts_page_init' ));
    }

    public function LocalFonts_add_plugin_page()
    {
        add_options_page(
            'Local Fonts', // page_title
            'Local Fonts', // menu_title
            'manage_options', // capability
            'local-fonts', // menu_slug
            array( $this, 'LocalFonts_create_admin_page' ) // function
        );
    }

    public function LocalFonts_create_admin_page()
    {
        global $LocalFonts_options_defaults;
        $this->LocalFonts_options = wp_parse_args(get_option('LocalFonts_options'), $LocalFonts_options_defaults); ?>

		<div class="wrap">
			<h2>Local Fonts</h2>
			<p>Select which fonts should be loaded and added to Divi</p>

			<form method="post" action="options.php">
				<?php
                    settings_fields('LocalFonts_option_group');
        do_settings_sections('LocalFonts-admin');
        submit_button();
        ?>
			</form>
		</div>
	<?php }

    public function LocalFonts_page_init()
    {
        register_setting(
            'LocalFonts_option_group', // option_group
            'LocalFonts_options', // option_name
            array( $this, 'LocalFonts_sanitize' ) // sanitize_callback
        );

        add_settings_section(
            'LocalFonts_setting_section', // id
            'Settings', // title
            array( $this, 'LocalFonts_section_info' ), // callback
            'LocalFonts-admin' // page
        );

        add_settings_field(
            'divi', // id
            'Divi', // title
            array( $this, 'divi_callback' ), // callback
            'LocalFonts-admin', // page
            'LocalFonts_setting_section' // section
        );

        add_settings_section(
            'LocalFonts_fonts_section', // id
            'Fonts', // title
            array( $this, 'LocalFonts_section_info' ), // callback
            'LocalFonts-admin' // page
        );

        add_settings_field(
            'roboto', // id
            'Roboto', // title
            array( $this, 'roboto_callback' ), // callback
            'LocalFonts-admin', // page
            'LocalFonts_fonts_section' // section
        );

        add_settings_field(
            'robotocondensed', // id
            'Roboto Condensed', // title
            array( $this, 'robotocondensed_callback' ), // callback
            'LocalFonts-admin', // page
            'LocalFonts_fonts_section' // section
        );

        add_settings_field(
            'lobster', // id
            'Lobster', // title
            array( $this, 'lobster_callback' ), // callback
            'LocalFonts-admin', // page
            'LocalFonts_fonts_section' // section
        );

        add_settings_field(
            'montserrat', // id
            'Montserrat', // title
            array( $this, 'montserrat_callback' ), // callback
            'LocalFonts-admin', // page
            'LocalFonts_fonts_section' // section
        );

        add_settings_field(
            'opensans', // id
            'Open Sans', // title
            array( $this, 'opensans_callback' ), // callback
            'LocalFonts-admin', // page
            'LocalFonts_fonts_section' // section
        );
    }

    public function LocalFonts_sanitize($input)
    {
        $sanitary_values = array();
        if (isset($input['divi'])) {
            $sanitary_values['divi'] = $input['divi'];
        }

        if (isset($input['roboto'])) {
            $sanitary_values['roboto'] = $input['roboto'];
        }

        if (isset($input['robotocondensed'])) {
            $sanitary_values['robotocondensed'] = $input['robotocondensed'];
        }

        if (isset($input['lobster'])) {
            $sanitary_values['lobster'] = $input['lobster'];
        }

        if (isset($input['montserrat'])) {
            $sanitary_values['montserrat'] = $input['montserrat'];
        }

        if (isset($input['opensans'])) {
            $sanitary_values['opensans'] = $input['opensans'];
        }
        return $sanitary_values;
    }

    public function LocalFonts_section_info()
    {
    }

    public function divi_callback()
    {
        printf(
            '<input type="checkbox" name="LocalFonts_options[divi]" id="divi" value="1" %s> <label for="divi">Add fonts to Divi fonts menu</label>',
            checked($this->LocalFonts_options['divi'], 1, false)
        );
    }

    public function roboto_callback()
    {
        printf(
            '<input type="checkbox" name="LocalFonts_options[roboto]" id="roboto" value="1" %s> <label for="roboto">Add Roboto with weights: 100italic, 300italic, 400italic, 700italic, 900italic, 100, 300, 400, 700, 900</label>',
            checked($this->LocalFonts_options['roboto'], 1, false)
        );
    }

    public function robotocondensed_callback()
    {
        printf(
            '<input type="checkbox" name="LocalFonts_options[robotocondensed]" id="robotocondensed" value="1" %s> <label for="robotocondensed">Add Roboto Condensed with weights: 300italic, 400italic, 700italic, 300, 400, 700</label>',
            checked($this->LocalFonts_options['robotocondensed'], 1, false)
        );
    }

    public function lobster_callback()
    {
        printf(
            '<input type="checkbox" name="LocalFonts_options[lobster]" id="lobster" value="1" %s> <label for="lobster">Add Lobster with weights: 400</label>',
            checked($this->LocalFonts_options['lobster'], 1, false)
        );
    }

    public function montserrat_callback()
    {
        printf(
            '<input type="checkbox" name="LocalFonts_options[montserrat]" id="montserrat" value="1" %s> <label for="montserrat">Add Montserrat with weights: 100italic, 300italic, 400italic, 700italic, 900italic, 100, 300, 400, 700, 900</label>',
            checked($this->LocalFonts_options['montserrat'], 1, false)
        );
    }

    public function opensans_callback()
    {
        printf(
            '<input type="checkbox" name="LocalFonts_options[opensans]" id="opensans" value="1" %s> <label for="opensans">Add Montserrat with weights: 300italic, 400italic, 700italic, 300, 400, 700</label>',
            checked($this->LocalFonts_options['opensans'], 1, false)
        );
    }
}
if (is_admin()) {
    $local_fonts = new LocalFonts();
}

?>