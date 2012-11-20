<?php
/*
Plugin Name: Apos Hello Bar
Plugin URI:  http://poslavsky.com
Description: Hello Bar inspired barr
Version:     0.1
Author:      plovs
Author URI:  http://poslavsky.com
License:     GPL2


/*  Copyright 2012 Alex Poslavsky  (email: http://poslavsky.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// TODO: rename this class to a proper name for your plugin
class apos_hello {

	/*--------------------------------------------*
	 * Constructor
	 *--------------------------------------------*/

	/**
	 * Initializes the plugin by setting localization, filters, and administration functions.
	 */
	function __construct() {

		// load plugin text domain
		add_action( 'init', array( $this, 'textdomain' ) );

		// Register admin styles and scripts
		add_action( 'admin_print_styles', array( $this, 'register_admin_styles' ) );
		// add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts' ) );

		// Register site styles and scripts
		add_action( 'wp_enqueue_scripts', array( $this, 'register_plugin_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'register_plugin_scripts' ) );

		register_activation_hook( __FILE__, array( $this, 'activate' ) );
		register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );

		add_action('admin_init', array( $this, 'apos_bar_admin_init' ) );

	    /*
	     * TODO:
	     * Define the custom functionality for your plugin. The first parameter of the
	     * add_action/add_filter calls are the hooks into which your code should fire.
	     *
	     * The second parameter is the function name located within this class. See the stubs
	     * later in the file.
	     *
	     * For more information:
	     * http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters
	     */
	    add_action( 'wp_footer', array( $this, 'action_method_name' ) );
	    // add_filter( 'TODO', array( $this, 'filter_method_name' ) );
	    add_action( 'admin_menu', array( $this, 'apos_hello_menu') );

	} // end constructor

	public function apos_bar_admin_init($value='')
	{
		// register_setting( 'apos_bar_options', 'apos_bar_options', array( $this,'apos_bar_options_validate') );
		register_setting( 'apos_bar_options', 'apos_bar_options' );

		add_settings_section('plugin_main', 'Main Settings2', 'plugin_section_text', 'apos-bar');
		add_settings_field('apos_bar_text_string', 'Message or Call to Action', array( $this,'plugin_setting_string'), 'apos-bar', 'plugin_main');
		add_settings_field('apos_bar_text_radio', 'Header / Footer', array( $this,'apos_bar_setting_radio'), 'apos-bar', 'plugin_main');
		add_settings_field('apos_bar_text_string2', 'Button Text', array( $this,'plugin_setting_string2'), 'apos-bar', 'plugin_main');
	}

	public function plugin_setting_string($id) {
		$options = get_option('apos_bar_options');
		echo "<input id='plugin_text_string' name='apos_bar_options[text]' size='120' type='text' value='{$options['text']}' />";
	}

	public function plugin_setting_string2($id) {
		$options = get_option('apos_bar_options');
		echo "<input id='plugin_text_string' name='apos_bar_options[button]' size='40' type='text' value='{$options['button']}' />";
	}

	public function apos_bar_setting_radio($id) {
		$options = get_option('apos_bar_options');
		if (! $options["apos_bar_radio"]) { $options["apos_bar_radio"]="header"; }
		?>
		<label>
			<input name="apos_bar_options[load]" type="radio" value="header" <?php checked('one', $options['load']); ?> /> Header
		</label><br />
		<label>
			<input name="apos_bar_options[load]" type="radio" value="footer" <?php checked('two', $options['load']); ?> /> Footer
		</label><br />
		<br>
		<span style="color:#666666;">Load the Apos Bar code in the header or footer</span>
		<?php
	}

	public function apos_bar_options_validate($input) {
		$newinput['text_string'] = trim($input['text_string']);
		if(!preg_match('/^[a-z0-9]{32}$/i', $newinput['text_string'])) {
		$newinput['text_string'] = '';
		}
		return $newinput;
	}

	public function apos_hello_menu() {
		add_options_page( 'My Plugin Options', 'Apos Bar', 'manage_options', 'apos-bar', array($this, 'apos_bar_options_page') );
	}

	function apos_bar_options_page() {
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
		?>
		<div class="wrap">
		<h2>My custom plugin</h2>
		Options relating to the Custom Plugin.
		<form action="options.php" method="post">

		<?php settings_fields('apos_bar_options'); ?>
		<?php do_settings_sections('apos-bar'); ?>
		 
		<?php submit_button(); ?>

		</form></div>
		 
		<h2>Preview</h2>
		<?php $options = get_option('apos_bar_options'); ?>
		<div class="apos_hello">
		    <span style="font-family: 'Arial, Helvetica, sans-serif;"><?php echo $options["text"]; ?>&nbsp;&nbsp;
		        <a class="apos_hello-link" href="#"><?php echo $options["button"]; ?></a> </span>
		        <a class="close-notify">
		            <img class="images/apos_hello-up-arrow" src="<?php echo plugins_url( '/apos_hello/img/apos_hello-up-arrow.png');  ?>" />
		        </a>
	        	</div>
		<div class="apos_hello-stub" style="display: none;">
		    <a class="show-notify">
		        <img class="apos_hello-down-arrow" src="<?php echo plugins_url('/apos_hello/img/apos_hello-down-arrow.png'); ?>" />
		    </a>
		</div>

		<?php
	}

	/**
	 * Fired when the plugin is activated.
	 *
	 * @params	$network_wide	True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog
	 */
	public function activate( $network_wide ) {
		// TODO define activation functionality here
	} // end activate

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @params	$network_wide	True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog
	 */
	public function deactivate( $network_wide ) {
		// TODO define deactivation functionality here
	} // end deactivate

	/**
	 * Loads the plugin text domain for translation
	 */
	public function textdomain() {
		// TODO: replace "apos_hello-locale" with a unique value for your plugin
		load_plugin_textdomain( 'apos_hello-locale', false, dirname( plugin_basename( __FILE__ ) ) . '/lang' );
	}

	/**
	 * Registers and enqueues admin-specific styles.
	 */
	public function register_admin_styles() {
		wp_enqueue_style( 'apos_hello-admin-styles', plugins_url( 'apos_hello/css/admin.css' ) );
	} // end register_admin_styles

	/**
	 * Registers and enqueues admin-specific JavaScript.
	 */
	public function register_admin_scripts() {

		// TODO change 'apos_hello' to the name of your plugin
		wp_enqueue_script( 'apos_hello-admin-script', plugins_url( 'apos_hello/js/admin.js' ) );

	} // end register_admin_scripts

	/**
	 * Registers and enqueues plugin-specific styles.
	 */
	public function register_plugin_styles() {

		// TODO change 'apos_hello' to the name of your plugin
		wp_enqueue_style( 'apos_hello-styles', plugins_url( 'apos_hello/css/display.css' ) );

	} // end register_plugin_styles

	/**
	 * Registers and enqueues plugin-specific scripts.
	 */
	public function register_plugin_scripts() {

		// TODO change 'apos_hello' to the name of your plugin
		wp_enqueue_script( 'apos_hello-plugin-script', plugins_url( 'apos_hello/js/display.js' ) , array('jquery', 'jquery-ui-core', 'jquery-effects-core', 'jquery-effects-bounce'));

	} // end register_plugin_scripts

	/*--------------------------------------------*
	 * Core Functions
	 *---------------------------------------------*/

	/**
 	 * Note:  Actions are points in the execution of a page or process
	 *        lifecycle that WordPress fires.
	 *
	 *		  WordPress Actions: http://codex.wordpress.org/Plugin_API#Actions
	 *		  Action Reference:  http://codex.wordpress.org/Plugin_API/Action_Reference
	 *
	 */
	function action_method_name() {
		$options = get_option('apos_bar_options');
		?>
	<div class="apos_hello" style="display: none;">
	    <span style="font-family: 'Arial, Helvetica, sans-serif;"><?php echo $options["text"]; ?>&nbsp;&nbsp;
	        <a class="apos_hello-link" href="#"><?php echo $options["button"]; ?></a> </span>
	        <a class="close-notify">
	            <img class="images/apos_hello-up-arrow" src="<?php echo plugins_url( '/apos_hello/img/apos_hello-up-arrow.png');  ?>" /></a>
	        </div>
	<div class="apos_hello-stub" style="display: none;">
	    <a class="show-notify">
	        <img class="apos_hello-down-arrow" src="<?php echo plugins_url('/apos_hello/img/apos_hello-down-arrow.png'); ?>" /></a></div>

	<?php
	} // end action_method_name

	/**
	 * Note:  Filters are points of execution in which WordPress modifies data
	 *        before saving it or sending it to the browser.
	 *
	 *		  WordPress Filters: http://codex.wordpress.org/Plugin_API#Filters
	 *		  Filter Reference:  http://codex.wordpress.org/Plugin_API/Filter_Reference
	 *
	 */
	function filter_method_name() {
	    // TODO define your filter method here
	} // end filter_method_name

} // end class

$plugin_name = new apos_hello();