<?php
/*
Plugin Name: Apos Top Bar
Plugin URI:  http://poslavsky.com
Description: Hello Bar inspired bar
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

class apos_bar {

public $defaults = array(
	'text' => "Changeme!",
	'buttontxt' => "Changeme too!",
	'placement' => 'top',
	'load_apos_bar_in' => 'footer',
);

	function __construct() {
		add_action( 'init', array( $this, 'textdomain' ) );
		register_activation_hook( __FILE__, array( $this, 'activate' ) );
		register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );

		if( is_admin() ) {
			add_action( 'admin_print_styles', array( $this, 'register_admin_styles' ) );
			// add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts' ) );
			add_action('admin_init', array( $this, 'apos_bar_admin_init' ) );
		    	add_action( 'admin_menu', array( $this, 'apos_bar_menu') );
		} else {
		add_action( 'wp_enqueue_scripts', array( $this, 'register_plugin_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'register_plugin_scripts' ) );
		//TODO
		add_action( 'wp_footer', array( $this, 'action_method_name' ) );
		}
	} // end constructor

	public function apos_bar_admin_init($value='')
	{
		register_setting( 'apos_bar_plugin_options', 'apos_bar_options', 'posk_validate_options' );
	}

	public function apos_bar_menu() {
		add_options_page( 'My Plugin Options', 'Apos Bar', 'manage_options', 'apos-bar', array($this, 'apos_bar_options_page') );
	}

	function apos_bar_options_page() {
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
	?>
	<div class="wrap">
		<div class="icon32" id="icon-options-general"><br></div>
		<h2>Apos Bar Options</h2>

		<h2>Preview</h2>
		<?php
		$options = wp_parse_args(get_option('apos_bar_options'), $this->defaults);
		print_r($options);
		?>
		<div class="apos_bar">
		    <span style="font-family: 'Arial, Helvetica, sans-serif;"><?php echo $options["text"]; ?>&nbsp;&nbsp;
		        <a class="apos_bar-link" href="#"><?php echo $options["buttontxt"]; ?></a> </span>
		        <a class="close-notify">
		            <img class="images/apos_bar-up-arrow" src="<?php echo plugins_url( '/apos_bar/img/up.png');  ?>" />
		        </a>
	        	</div>
		<div class="apos_bar-stub" style="display: none;">
		    <a class="show-notify">
		        <img class="apos_bar-down-arrow" src="<?php echo plugins_url('/apos_bar/img/down.png'); ?>" />
		    </a>
		</div>

		<?php submit_button(); ?>

		<!-- Beginning of the Plugin Options Form -->
		<form method="post" action="options.php">
			<?php settings_fields('apos_bar_plugin_options'); ?>

			<table class="form-table">

				<!-- Bar Content -->
				<tr valign="top" style="border-top:#dddddd 1px solid;">
					<th scope="row">Sample Text Area</th>
					<td>
						<textarea name="apos_bar_options[text]" rows="2" cols="80" type='textarea'><?php echo $options['text']; ?></textarea>
						<br /><span style="color:#666666;margin-left:2px;">Add a comment here to give extra information to Plugin users</span>
					</td>
				</tr>

				<tr>
					<th scope="row">Enter Some Information</th>
					<td>
						<input type="text" size="40" name="apos_bar_options[buttontxt]" value="<?php echo $options['buttontxt']; ?>" />
					</td>
				</tr>

				<tr>
					<th scope="row">Placement</th>
					<td>
						<select name='apos_bar_options[placement]'>
							<option value='top' <?php selected('top', $options['placement']); ?>>Display at the top</option>
							<option value='bottom' <?php selected('bottom', $options['placement']); ?>>Display at the bottom</option>
						</select>
						<span style="color:#666666;margin-left:2px;">Add a comment here to explain more about how to use the option above</span>
					</td>
				</tr>

<!-- 				<tr>
					<th scope="row">Link Display</th>
					<td>
						<select name='apos_bar_options[drp_select_box]'>
							<option value='one' <?php selected('one', $options['drp_select_box']); ?>>Display as a hyperlink</option>
							<option value='two' <?php selected('two', $options['drp_select_box']); ?>>Display as a button</option>
						</select>
						<span style="color:#666666;margin-left:2px;">Add a comment here to explain more about how to use the option above</span>
					</td>
				</tr>
 -->
				<!-- Apos Bar uninstall -->
				<tr><td colspan="2"><div style="margin-top:10px;"></div></td></tr>
				<tr valign="top" style="border-top:#dddddd 1px solid;">
					<th scope="row">Database Options</th>
					<td>
						<label><input name="apos_bar_options[chk_default_options_db]" type="checkbox" value="1" <?php if (isset($options['chk_default_options_db'])) { checked('1', $options['chk_default_options_db']); } ?> /> Restore defaults upon plugin deactivation/reactivation</label>
						<br /><span style="color:#666666;margin-left:2px;">Only check this if you want to reset plugin settings upon Plugin reactivation</span>
					</td>
				</tr>


			</table>
			<?php submit_button(); ?>

		</form>

	</div>
	<?php
	}

	public function activate( $network_wide ) {
	} // end activate

	public function deactivate( $network_wide ) {
		delete_option('apos_bar_options');
	} // end deactivate

	public function textdomain() {
		load_plugin_textdomain( 'apos_bar-locale', false, dirname( plugin_basename( __FILE__ ) ) . '/lang' );
	}

	public function register_admin_styles() {
		wp_enqueue_style( 'apos_bar-admin-styles', plugins_url( 'apos_bar/css/admin.css' ) );
	} // end register_admin_styles

	public function register_admin_scripts() {
		wp_enqueue_script( 'apos_bar-admin-script', plugins_url( 'apos_bar/js/admin.js' ) );
	} // end register_admin_scripts

	public function register_plugin_styles() {
		wp_enqueue_style( 'apos_bar-styles', plugins_url( 'apos_bar/css/display.css' ) );
	} // end register_plugin_styles

	public function register_plugin_scripts() {
		wp_enqueue_script( 'apos_bar-plugin-script', plugins_url( 'apos_bar/js/display.js' ) , array('jquery', 'jquery-ui-core', 'jquery-effects-core', 'jquery-effects-bounce'));
		wp_enqueue_script( 'cookie', plugins_url( 'apos_bar/js/jquery.cookie.js' ) , array('jquery'));
	} // end register_plugin_scripts

	/*--------------------------------------------*
	 * Core Functions
	 *---------------------------------------------*/

	function action_method_name() {
		$options = wp_parse_args(get_option('apos_bar_options'), $this->defaults);
		?>
		<div class="apos_bar <?php echo ($options["placement"]=="top") ?  "apos_bar_top" : "apos_bar_bottom" ?>" style="display: none;">
			<span style="font-family: 'Arial, Helvetica, sans-serif;"><?php echo $options["text"]; ?>&nbsp;&nbsp;
			    <a class="apos_bar-link" href="#"><?php echo $options["buttontxt"]; ?></a>
			</span>
			<a class="close-notify">
			    <img class="apos_bar-up-arrow" src="<?php $img=(($options["placement"]=="top") ?  'up.png' : 'upb.png'); echo plugins_url('/apos_bar/img/' . $img); ?>" />
			</a>
		 </div>
		<div class="apos_bar-stub <?php echo ($options["placement"]=="top") ?  "apos_bar-stub_top" : "apos_bar-stub_bottom" ?>" style="display: none;">
			<a class="show-notify">
			    <img class="apos_bar-down-arrow"
			    	src="<?php $img=(($options["placement"]=="top") ?  'down.png' : 'downb.png'); echo plugins_url('/apos_bar/img/' . $img); ?>" />
			</a>
		</div>
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

$plugin_name = new apos_bar();