<?php
/*
Plugin Name: Local Fonts
Description: Plugin to host Fonts (Roboto, Lobster, Montserrat, Open Sans) locally and add to Divi's font menu
Version: 1.0.13
Author: Christopher Banck
Author URI: https://banck.net
*/
/* Start Adding Functions Below this Line */

// Register Style
function load_divi_custom_font($fonts) {
  $local_fonts_options = get_option( 'local_fonts_option_name' );
  $custom_fonts = array();
  if (isset($local_fonts_options['roboto_0']) && $local_fonts_options['roboto_0'] === 'roboto_0') {
    wp_enqueue_style( 'LocalFontsPluginRoboto', plugin_dir_url( __FILE__ ) . 'assets/css/roboto.css' );
    // Add font to Divi's font menu
    $font = array('Roboto' => array(
            'styles'        => '100italic,300italic,400italic,700italic,900italic,100,300,400,700,900',
            'character_set' => 'latin',
            'type'          => 'sans-serif',
            'standard'      => 1
        ));
        
    $custom_fonts = array_merge($font,$custom_fonts);
  }
  if (isset($local_fonts_options['lobster_1']) && $local_fonts_options['lobster_1'] === 'lobster_1') {
    wp_enqueue_style( 'LocalFontsPluginLobster', plugin_dir_url( __FILE__ ) . 'assets/css/lobster.css' );
    // Add font to Divi's font menu
    $font = array('Lobster' => array(
            'styles'        => '400',
            'character_set' => 'latin',
            'type'          => 'sans-serif',
            'standard'      => 1
        ));
        
    $custom_fonts = array_merge($font,$custom_fonts);
  }
  if (isset($local_fonts_options['montserrat_2']) && $local_fonts_options['montserrat_2'] === 'montserrat_2') {
    wp_enqueue_style( 'LocalFontsPluginMontserrat', plugin_dir_url( __FILE__ ) . 'assets/css/montserrat.css' );
    // Add font to Divi's font menu
    $font = array('Montserrat' => array(
            'styles'        => '100italic,300italic,400italic,700italic,900italic,100,300,400,700,900',
            'character_set' => 'latin',
            'type'          => 'sans-serif',
            'standard'      => 1
        ));
        
    $custom_fonts = array_merge($font,$custom_fonts);
  }
  if (isset($local_fonts_options['opensans_3']) && $local_fonts_options['opensans_3'] === 'opensans_3') {
    wp_enqueue_style( 'LocalFontsPluginOpenSans', plugin_dir_url( __FILE__ ) . 'assets/css/opensans.css' );
    // Add font to Divi's font menu
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

add_filter('et_websafe_fonts', 'load_divi_custom_font',10,2);



/**
 * Generated by the WordPress Option Page generator
 * at http://jeremyhixon.com/wp-tools/option-page/
 */

class LocalFonts {
	private $local_fonts_options;

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'local_fonts_add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'local_fonts_page_init' ) );
	}

	public function local_fonts_add_plugin_page() {
		add_options_page(
			'Local Fonts', // page_title
			'Local Fonts', // menu_title
			'manage_options', // capability
			'local-fonts', // menu_slug
			array( $this, 'local_fonts_create_admin_page' ) // function
		);
	}

	public function local_fonts_create_admin_page() {
		$this->local_fonts_options = get_option( 'local_fonts_option_name' ); ?>

		<div class="wrap">
			<h2>Local Fonts</h2>
			<p>Select which fonts should be loaded and added to Divi</p>
			<?php settings_errors(); ?>

			<form method="post" action="options.php">
				<?php
					settings_fields( 'local_fonts_option_group' );
					do_settings_sections( 'local-fonts-admin' );
					submit_button();
				?>
			</form>
		</div>
	<?php }

	public function local_fonts_page_init() {
		register_setting(
			'local_fonts_option_group', // option_group
			'local_fonts_option_name', // option_name
			array( $this, 'local_fonts_sanitize' ) // sanitize_callback
		);

		add_settings_section(
			'local_fonts_setting_section', // id
			'Settings', // title
			array( $this, 'local_fonts_section_info' ), // callback
			'local-fonts-admin' // page
		);

		add_settings_field(
			'roboto_0', // id
			'Roboto', // title
			array( $this, 'roboto_0_callback' ), // callback
			'local-fonts-admin', // page
			'local_fonts_setting_section' // section
		);

		add_settings_field(
			'lobster_1', // id
			'Lobster', // title
			array( $this, 'lobster_1_callback' ), // callback
			'local-fonts-admin', // page
			'local_fonts_setting_section' // section
		);

		add_settings_field(
			'montserrat_2', // id
			'Montserrat', // title
			array( $this, 'montserrat_2_callback' ), // callback
			'local-fonts-admin', // page
			'local_fonts_setting_section' // section
		);

		add_settings_field(
			'opensans_3', // id
			'Open Sans', // title
			array( $this, 'opensans_3_callback' ), // callback
			'local-fonts-admin', // page
			'local_fonts_setting_section' // section
		);
	}

	public function local_fonts_sanitize($input) {
		$sanitary_values = array();
		if ( isset( $input['roboto_0'] ) ) {
			$sanitary_values['roboto_0'] = $input['roboto_0'];
		}

		if ( isset( $input['lobster_1'] ) ) {
			$sanitary_values['lobster_1'] = $input['lobster_1'];
		}

        if ( isset( $input['montserrat_2'] ) ) {
			$sanitary_values['montserrat_2'] = $input['montserrat_2'];
		}

        if ( isset( $input['opensans_3'] ) ) {
			$sanitary_values['opensans_3'] = $input['opensans_3'];
		}
		return $sanitary_values;
	}

	public function local_fonts_section_info() {
		
	}

	public function roboto_0_callback() {
		printf(
			'<input type="checkbox" name="local_fonts_option_name[roboto_0]" id="roboto_0" value="roboto_0" %s> <label for="roboto_0">Add Roboto with weights: 100italic, 300italic, 400italic, 700italic, 900italic, 100, 300, 400, 700, 900</label>',
			( isset( $this->local_fonts_options['roboto_0'] ) && $this->local_fonts_options['roboto_0'] === 'roboto_0' ) ? 'checked' : ''
		);
	}

	public function lobster_1_callback() {
		printf(
			'<input type="checkbox" name="local_fonts_option_name[lobster_1]" id="lobster_1" value="lobster_1" %s> <label for="lobster_1">Add Lobster with weights: 400</label>',
			( isset( $this->local_fonts_options['lobster_1'] ) && $this->local_fonts_options['lobster_1'] === 'lobster_1' ) ? 'checked' : ''
		);
	}

    public function montserrat_2_callback() {
		printf(
			'<input type="checkbox" name="local_fonts_option_name[montserrat_2]" id="montserrat_2" value="montserrat_2" %s> <label for="montserrat_2">Add Montserrat with weights: 100italic, 300italic, 400italic, 700italic, 900italic, 100, 300, 400, 700, 900</label>',
			( isset( $this->local_fonts_options['montserrat_2'] ) && $this->local_fonts_options['montserrat_2'] === 'montserrat_2' ) ? 'checked' : ''
		);
	}

    public function opensans_3_callback() {
		printf(
			'<input type="checkbox" name="local_fonts_option_name[opensans_3]" id="opensans_3" value="opensans_3" %s> <label for="opensans_3">Add Montserrat with weights: 300italic, 400italic, 700italic, 300, 400, 700</label>',
			( isset( $this->local_fonts_options['opensans_3'] ) && $this->local_fonts_options['opensans_3'] === 'opensans_3' ) ? 'checked' : ''
		);
	}
}
if ( is_admin() )
	$local_fonts = new LocalFonts();

/* 
 * Retrieve this value with:
 * $local_fonts_options = get_option( 'local_fonts_option_name' ); // Array of All Options
 * $roboto_0 = $local_fonts_options['roboto_0']; // Roboto
 * $lobster_1 = $local_fonts_options['lobster_1']; // Lobster 
 */
?>