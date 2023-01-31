<?php
/*
Plugin Name: Country Blocker
Description: Block visitors from specific countries from accessing your website.
Version: 1.1
Author: MD Arif Islam
*/

class Country_Blocker {

	private static $instance;

	private function __construct() {
		add_action( 'template_redirect', array( $this, 'block_visitor' ) );
		add_action( 'admin_menu', array( $this, 'add_options_page' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
	}

	public static function get_instance() {
		if ( ! self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function block_visitor() {
		$allowed_countries = get_option( 'country_blocker_allowed_countries' );
		$visitor_country   = $this->get_visitor_country();

		if ( ! in_array( $visitor_country, $allowed_countries ) ) {
			wp_die( 'Sorry, access to this website is restricted in your country.' );
		}
	}

	private function get_visitor_country() {
		$ip_address = $_SERVER['REMOTE_ADDR'];
		$details    = json_decode( file_get_contents( "http://ipinfo.io/{$ip_address}/json" ) );

		return $details->country;
	}

	public function add_options_page() {
		add_options_page( 'Country Blocker', 'Country Blocker', 'manage_options', 'country-blocker', array(
			$this,
			'options_page'
		) );
	}

	public function options_page() {
		?>
        <div class="wrap">
            <h1>Country Blocker</h1>
            <form action="options.php" method="post">
				<?php
				settings_fields( 'country_blocker_options' );
				do_settings_sections( 'country-blocker' );
				submit_button();
				?>
            </form>
        </div>
		<?php
	}

	public function register_settings() {
		register_setting( 'country_blocker_options', 'country_blocker_allowed_countries' );

		add_settings_section( 'country_blocker_section', '', '', 'country-blocker' );

		add_settings_field( 'country_blocker_allowed_countries', 'Allowed Countries', array(
			$this,
			'allowed_countries_input'
		), 'country-blocker', 'country_blocker_section' );
	}

	public function allowed_countries_input() {
		$countries = array(
			'AF' => 'Afghanistan',
			'AL' => 'Albania',
			'BN' => 'Bangladesh',
			// ...
			'ZW' => 'Zimbabwe',
		);
		?>
        <select name="country_blocker_allowed_countries[]" multiple> <?php
			$allowed_countries = get_option( 'country_blocker_allowed_countries' );
			foreach ( $countries as $code => $name ) {
				$selected = in_array( $code, (array) $allowed_countries ) ? 'selected' : '';
				echo "<option value='{$code}' {$selected}>{$name}</option>";
			}
			?>
        </select>
        <p class="description">
            Select one or more countries from the dropdown list and save changes to allow visitors from those countries
            to access your website. Leave the list empty to allow visitors from all countries.
        </p>
		<?php
	}
}

Country_Blocker::get_instance();