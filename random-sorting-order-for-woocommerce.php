<?php
/**
 * Plugin Name: Random Sorting Order for WooCommerce
 * Plugin URI: https://1stphp.wordpress.com/
 * Description: Add random sorting. Add an order option for "random"
 * Version: 1.0
 * Author: Prashant Baldha
 * Author URI: https://profiles.wordpress.org/pmbaldha#content-plugins
 * Requires at least: 3.8
 * Tested up to: 4.9.4
 *
 * Text Domain: random-sorting-order-for-woocommerce
 * Domain Path: /languages/
 * WC requires at least: 3.0.0
 * WC tested up to: 3.3.3
 */
 
/**
 * @package Random Sorting for WooCommerce
 * @category Core
 * @author pmbaldha
 */

// Create a helper function for easy SDK access.
function rsofw() {
    global $rsofw;

    if ( ! isset( $rsofw ) ) {
        // Include Freemius SDK.
        require_once dirname(__FILE__) . '/freemius/start.php';

        $rsofw = fs_dynamic_init( array(
            'id'                  => '1815',
            'slug'                => 'random-sorting-order-for-woocommerce',
            'type'                => 'plugin',
            'public_key'          => 'pk_8ca53f90a6e196cfaefe5d5dac5db',
            'is_premium'          => false,
            'has_addons'          => false,
            'has_paid_plans'      => false,
            'menu'                => array(
                'first-path'     => 'plugins.php',
            ),
        ) );
    }

    return $rsofw;
}

// Init Freemius.
rsofw();
// Signal that SDK was initiated.
do_action( 'rsofw_loaded' );

class Rsofw_Random_Sorting {
	
	/**
	 * Holds Plugin instance
	 */ 
	private static  $instance;
	
	/**
	 * Get Rsofw_Random_Sorting class instance
	 * 
	 * @return object Rsofw_Random_Sorting class instance
	 */
	public static function get_instance() {
		if ( !isset( self::$instance ) ) {
			self::$instance = new Rsofw_Random_Sorting();
		}
		return self::$instance;
	}
     
	public function __construct() {
		add_action( 'plugins_loaded', array($this, 'load_textdomain') );
		
		add_action( 'init', array($this, 'init'), 99 );

		// add_menu_page( __("Random Sorting Order for WooCommerce", 'random-sorting-order-for-woocommerce'), __("Random Sorting Order", 'random-sorting-order-for-woocommerce'), 'manage_options', 'random-sorting-order-for-woocommerce', array($this, 'menu_page'), 'dashicons-controls-repeat', 55);	
	}
	
	/**
	 * Load plugin textdomain.
	 *
	 * @since 1.0.0
	 */
	public function load_textdomain() {
	  load_plugin_textdomain( 'random-sorting-order-for-woocommerce', false, basename( dirname( __FILE__ ) ) . '/languages' ); 
	}
	
	public function init() {		
		add_filter( 'woocommerce_default_catalog_orderby_options', array($this, 'woocommerce_default_catalog_orderby_options') );
		add_filter( 'woocommerce_catalog_orderby', array($this, 'woocommerce_catalog_orderby') );
		add_filter( 'woocommerce_get_catalog_ordering_args', array( $this,'woocommerce_get_catalog_ordering_args') );		
	}

	public function woocommerce_get_catalog_ordering_args( $args ) {
	  $orderby_value = isset( $_GET['orderby'] ) ? wc_clean( $_GET['orderby'] ) : apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby' ) );
		if ( 'random_list' == $orderby_value ) {
			$args['orderby'] = 'rand';
			$args['order'] = '';
			$args['meta_key'] = '';
		}
		return $args;
	}

	public function woocommerce_catalog_orderby( $sortby ) {
		$sortby['random_list'] = apply_filters('rsofw_random_sorting_label', __('Random', 'random-sorting-order-for-woocommerce'));
		return $sortby;
	}
	
	public function menu_page( $sortby ) {
		?>
			<div class="wrap">
				<h1 class="wp-heading-inline"><?php echo __("Featured Product First For WooCommerce", 'random-sorting-order-for-woocommerce');?></h1>				
				<a href="https://wordpress.org/plugins/email-tracker/" target="_blank"><?php echo __("Track Emails for view and click by Email Tracker free wordpress plugin", 'random-sorting-order-for-woocommerce');?></a>				
				<div style="width:800px;">
					<h2><?php echo __('Getting started', 'random-sorting-order-for-woocommerce'); ?></h2>
					<p>
						<?php printf(__('To configure Random Soring Order For WooCommerce, Please %s click here%s (%sAdmin Dashboard > WooCommerce > Settings > Random Sorting Order%s). You will find screen as below:', 'random-sorting-order-for-woocommerce'), '<a href="'.WFF_SETTING_PAGE_URL.'">', '</a>', '<strong>', '</strong>'); ?>
						<br/><br/>
						<img src="<?php echo plugins_url( 'assets/images/settings.png', __FILE__ ); ?>" style="width: 800px; max-width: 100%;" />
					</p>
				
					<p><?php echo __('Thank You for using Random Soring Order For WooCommerce', 'random-sorting-order-for-woocommerce');?></p>
					<?php
					if ( wff()->is_not_paying() ) {
					?>
					<p>
						<strong>
						<?php
						printf(__("Free version of plugin displays featured product first on shop, archive and search page with woocommerce default sorting. If you would like to sorting everyewhere, Please %sUpgrade Now!%s", 'random-sorting-order-for-woocommerce'), '<a href="' . wff()->get_upgrade_url() . '">', '</a>');
						?>
						</strong>
					</p>
					<p>
						<strong>
						<?php
						printf(__('%sPro version%s of plugin can change the label of random sorting option in sort dropdown and can change the position in sorting dropdown', 'random-sorting-order-for-woocommerce'), '<a href="' . wff()->get_upgrade_url() . '">', '</a>');
						?>
						</strong>
					</p>
					
					<?php
					}
					?>
					
					
					<p>
						<strong>
						<?php
						printf(__('If you face any trouble, Please feel free to email me on %s any time. I am always happy to help you.', 'random-sorting-order-for-woocommerce'), '<a href="mailto:pmbaldha@gmail.com">pmbaldha@gmail.com</a>');
						echo '<br/>';
						printf(__('Please feel free to open support ticket on %s, If you found any issue.', 'random-sorting-order-for-woocommerce'), '<a href="https://wordpress.org/support/plugin/random-sorting-order-for-woocommerce">'.__('Support Forum', 'random-sorting-order-for-woocommerce').'</a>');
						?>
						</strong>
					</p>
				</p>
			</div>
			
		<?php
	}	
	
}
Rsofw_Random_Sorting::get_instance();