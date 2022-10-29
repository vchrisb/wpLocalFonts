<?php
/*
Plugin Name: Local Fonts
Description: Plugin to host Fonts (Roboto, Roboto Condensed, Lobster, Montserrat, Open Sans) locally and optionally add to Divi's font menu
Version: 1.1.0
Author: Christopher Banck
Author URI: https://banck.net
*/
/* Start Adding Functions Below this Line */

// Register Style
function LocalFonts_add_style() {
  $LocalFonts_options = get_option( 'LocalFonts_options', array() );
  if (isset($LocalFonts_options['roboto_0']) && $LocalFonts_options['roboto_0'] === "1") {
    wp_enqueue_style( 'LocalFontsPluginRoboto', plugin_dir_url( __FILE__ ) . 'assets/css/roboto.css' );
  }
  if (isset($LocalFonts_options['robotocondensed_1']) && $LocalFonts_options['robotocondensed_1'] === "1") {
    wp_enqueue_style( 'LocalFontsPluginRobotoCondensed', plugin_dir_url( __FILE__ ) . 'assets/css/robotocondensed.css' );  
  }
  if (isset($LocalFonts_options['lobster_2']) && $LocalFonts_options['lobster_2'] === "1") {
    wp_enqueue_style( 'LocalFontsPluginLobster', plugin_dir_url( __FILE__ ) . 'assets/css/lobster.css' );
  }
  if (isset($LocalFonts_options['montserrat_3']) && $LocalFonts_options['montserrat_3'] === "1") {
    wp_enqueue_style( 'LocalFontsPluginMontserrat', plugin_dir_url( __FILE__ ) . 'assets/css/montserrat.css' );

  }
  if (isset($LocalFonts_options['opensans_4']) && $LocalFonts_options['opensans_4'] === "1") {
    wp_enqueue_style( 'LocalFontsPluginOpenSans', plugin_dir_url( __FILE__ ) . 'assets/css/opensans.css' );    
  }
}

// Add to Divi Font Menu
function LocalFonts_add_divi($fonts) {
    $LocalFonts_options = get_option( 'LocalFonts_options', array() );
    $custom_fonts = array();
    if (isset($LocalFonts_options['roboto_0']) && $LocalFonts_options['roboto_0'] === "1") {
        $font = array('Roboto' => array(
                'styles'        => '100italic,300italic,400italic,700italic,900italic,100,300,400,700,900',
                'character_set' => 'latin',
                'type'          => 'sans-serif',
                'standard'      => 1
            ));
        $custom_fonts = array_merge($font,$custom_fonts);  
    }
    if (isset($LocalFonts_options['robotocondensed_1']) && $LocalFonts_options['robotocondensed_1'] === "1") {
        $font = array('Roboto Condensed' => array(
                'styles'        => '300italic,400italic,700italic,300,400,700',
                'character_set' => 'latin',
                'type'          => 'sans-serif',
                'standard'      => 1
            ));
        $custom_fonts = array_merge($font,$custom_fonts);   
    }
    if (isset($LocalFonts_options['lobster_2']) && $LocalFonts_options['lobster_2'] === "1") {
        $font = array('Lobster' => array(
                'styles'        => '400',
                'character_set' => 'latin',
                'type'          => 'sans-serif',
                'standard'      => 1
            ));
        $custom_fonts = array_merge($font,$custom_fonts);
    }
    if (isset($LocalFonts_options['montserrat_3']) && $LocalFonts_options['montserrat_3'] === "1") {
        $font = array('Montserrat' => array(
                'styles'        => '100italic,300italic,400italic,700italic,900italic,100,300,400,700,900',
                'character_set' => 'latin',
                'type'          => 'sans-serif',
                'standard'      => 1
            ));
        $custom_fonts = array_merge($font,$custom_fonts);
    }
    if (isset($LocalFonts_options['opensans_4']) && $LocalFonts_options['opensans_4'] === "1") {
        $font = array('Open Sans' => array(
                'styles'        => '300italic,400italic,700italic,300,400,700',
                'character_set' => 'latin',
                'type'          => 'sans-serif',
                'standard'      => 1
            ));
        $custom_fonts = array_merge($font,$custom_fonts);
    }
    return array_merge($custom_fonts,$fonts);
  }

$LocalFonts_options = get_option( 'LocalFonts_options', array() );
if (isset($LocalFonts_options['divi_0']) && $LocalFonts_options['divi_0'] === "1") {
    add_filter('et_websafe_fonts', 'LocalFonts_add_divi',10,2);
}
add_action('wp_head', 'LocalFonts_add_style');

class LocalFonts {
	private $LocalFonts_options;

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'LocalFonts_add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'LocalFonts_page_init' ) );
	}

	public function LocalFonts_add_plugin_page() {
		add_options_page(
			'Local Fonts', // page_title
			'Local Fonts', // menu_title
			'manage_options', // capability
			'local-fonts', // menu_slug
			array( $this, 'LocalFonts_create_admin_page' ) // function
		);
	}

	public function LocalFonts_create_admin_page() {
        $default = array('divi_0' => '', 'roboto_0' => '', 'robotocondensed_1' => '', 'lobster_2' => '','montserrat_3' => '','opensans_4' => '');
		$this->LocalFonts_options = get_option( 'LocalFonts_options', $default ); ?>

		<div class="wrap">
			<h2>Local Fonts</h2>
			<p>Select which fonts should be loaded and added to Divi</p>

			<form method="post" action="options.php">
				<?php
					settings_fields( 'LocalFonts_option_group' );
					do_settings_sections( 'LocalFonts-admin' );
					submit_button();
				?>
			</form>
		</div>
	<?php }

	public function LocalFonts_page_init() {
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
			'divi_0', // id
			'Divi', // title
			array( $this, 'divi_0_callback' ), // callback
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
			'roboto_0', // id
			'Roboto', // title
			array( $this, 'roboto_0_callback' ), // callback
			'LocalFonts-admin', // page
			'LocalFonts_fonts_section' // section
		);

		add_settings_field(
			'robotocondensed_1', // id
			'Roboto Condensed', // title
			array( $this, 'robotocondensed_1_callback' ), // callback
			'LocalFonts-admin', // page
			'LocalFonts_fonts_section' // section
		);

		add_settings_field(
			'lobster_2', // id
			'Lobster', // title
			array( $this, 'lobster_2_callback' ), // callback
			'LocalFonts-admin', // page
			'LocalFonts_fonts_section' // section
		);

		add_settings_field(
			'montserrat_3', // id
			'Montserrat', // title
			array( $this, 'montserrat_3_callback' ), // callback
			'LocalFonts-admin', // page
			'LocalFonts_fonts_section' // section
		);

		add_settings_field(
			'opensans_4', // id
			'Open Sans', // title
			array( $this, 'opensans_4_callback' ), // callback
			'LocalFonts-admin', // page
			'LocalFonts_fonts_section' // section
		);
	}

	public function LocalFonts_sanitize($input) {
		$sanitary_values = array('divi_0' => '', 'roboto_0' => '', 'robotocondensed_1' => '', 'lobster_2' => '','montserrat_3' => '','opensans_4' => '');
		if ( isset( $input['divi_0'] ) ) {
			$sanitary_values['divi_0'] = $input['divi_0'];
		}

		if ( isset( $input['roboto_0'] ) ) {
			$sanitary_values['roboto_0'] = $input['roboto_0'];
		}

		if ( isset( $input['robotocondensed_1'] ) ) {
			$sanitary_values['robotocondensed_1'] = $input['robotocondensed_1'];
		}

		if ( isset( $input['lobster_2'] ) ) {
			$sanitary_values['lobster_2'] = $input['lobster_2'];
		}

        if ( isset( $input['montserrat_3'] ) ) {
			$sanitary_values['montserrat_3'] = $input['montserrat_3'];
		}

        if ( isset( $input['opensans_4'] ) ) {
			$sanitary_values['opensans_4'] = $input['opensans_4'];
		}
		return $sanitary_values;
	}

	public function LocalFonts_section_info() {
		
	}

	public function divi_0_callback() {
		printf(
			'<input type="checkbox" name="LocalFonts_options[divi_0]" id="divi_0" value="1" %s> <label for="divi_0">Add fonts to Divi fonts menu</label>',
			checked( $this->LocalFonts_options['divi_0'], 1, FALSE )
		);
	}

	public function roboto_0_callback() {
		printf(
			'<input type="checkbox" name="LocalFonts_options[roboto_0]" id="roboto_0" value="1" %s> <label for="roboto_0">Add Roboto with weights: 100italic, 300italic, 400italic, 700italic, 900italic, 100, 300, 400, 700, 900</label>',
			checked( $this->LocalFonts_options['roboto_0'], 1, FALSE )
		);
	}

	public function robotocondensed_1_callback() {
		printf(
			'<input type="checkbox" name="LocalFonts_options[robotocondensed_1]" id="robotocondensed_1" value="1" %s> <label for="robotocondensed_1">Add Roboto Condensed with weights: 300italic, 400italic, 700italic, 300, 400, 700</label>',
			checked( $this->LocalFonts_options['robotocondensed_1'], 1, FALSE )
		);
	}

	public function lobster_2_callback() {
		printf(
			'<input type="checkbox" name="LocalFonts_options[lobster_2]" id="lobster_2" value="1" %s> <label for="lobster_2">Add Lobster with weights: 400</label>',
			checked( $this->LocalFonts_options['lobster_2'], 1, FALSE )
		);
	}

    public function montserrat_3_callback() {
		printf(
			'<input type="checkbox" name="LocalFonts_options[montserrat_3]" id="montserrat_3" value="1" %s> <label for="montserrat_3">Add Montserrat with weights: 100italic, 300italic, 400italic, 700italic, 900italic, 100, 300, 400, 700, 900</label>',
			checked( $this->LocalFonts_options['montserrat_3'], 1, FALSE )
		);
	}

    public function opensans_4_callback() {
		printf(
			'<input type="checkbox" name="LocalFonts_options[opensans_4]" id="opensans_4" value="1" %s> <label for="opensans_4">Add Montserrat with weights: 300italic, 400italic, 700italic, 300, 400, 700</label>',
			checked($this->LocalFonts_options['opensans_4'], 1, FALSE )
		);
	}
}
if ( is_admin() )
	$local_fonts = new LocalFonts();

?>