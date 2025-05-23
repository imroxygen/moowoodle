<?php

namespace MooWoodle;

class Admin {
	
	public function __construct() {
		// Register submenu for admin menu
		add_action( 'admin_menu', [ &$this, 'add_submenu' ] );
		add_action( 'admin_enqueue_scripts', [ &$this, 'enqueue_admin_script' ] );
	}

	/**
	 * Add moowoodle menu in admin dashboard.
	 * @return void
	 */
    public static function add_menu() {
        if( is_admin() ) {
            add_menu_page(
                'MooWoodle',
                'MooWoodle',
                'manage_options',
                'moowoodle',
                [ Admin::class, 'create_settings_page' ],
                esc_url(MooWoodle()->plugin_url) . 'src/assets/images/moowoodle.png',
                50
		    );
        }
    }

	/**
	 * Add Option page
	 */
	public function add_submenu() {
		$pro_sticker = apply_filters( 'is_moowoodle_pro_inactive', true ) ? 

		'<span class="mw-pro-tag" style="font-size: 0.5rem; background: #e35047; padding: 0.125rem 0.5rem; color: #F9F8FB; font-weight: 700; line-height: 1.1; position: absolute; border-radius: 2rem 0; right: -0.75rem; top: 50%; transform: translateY(-50%)">Pro</span>' : '';

		// Array contain moowoodle submenu
		$submenus = [
			"courses" => [
				'name' 	 => __("Courses", 'moowoodle'),
				'subtab' => ''
			],
			"cohorts" => [
				'name'   => __("Cohorts", 'moowoodle') . $pro_sticker,
				'subtab' => ''
			],
			"enrolments" => [
				'name'   => __("Enrolments", 'moowoodle') . $pro_sticker,
				'subtab' => ''
			],
			"settings" => [
				'name'   => __("Settings", 'moowoodle'),
				'subtab' => 'general'
			],
			"synchronization" => [
				'name'   => __("Synchronization", 'moowoodle'),
				'subtab' => 'synchronize-course'
			],
		];

		// Register all submenu
		foreach ( $submenus as $slug => $submenu ) {
			// prepare subtab if subtab is exist
			$subtab = '';

			if ( $submenu[ 'subtab' ] ) {
				$subtab = '&sub-tab=' . $submenu[ 'subtab' ];
			}

			add_submenu_page(
				'moowoodle',
				$submenu['name'],
                "<span style='position: relative; display: block; width: 100%;' class='admin-menu'>" . $submenu['name'] . "</span>",
				'manage_options',
				'moowoodle#&tab=' . $slug . $subtab,
				'_-return_null'
			);
		}

		// Register upgrade to pro submenu page.
		if ( ! Util::is_khali_dabba() ) {
			add_submenu_page(
				'moowoodle',
				__("Upgrade to Pro", 'moowoodle'),
				'<style>
					a:has(.upgrade-to-pro){
						background: linear-gradient(-28deg, #f6a091, #bb939c, #5f6eb3) !important;
						color: White !important;
					};
				</style>
				<div class="upgrade-to-pro"><i class="dashicons dashicons-awards"></i>' . esc_html__("Upgrade to Pro", 'moowoodle') . '</div> ',
				'manage_options',
				'',
				array($this, 'handle_external_redirects')
			);
		}
		
		remove_submenu_page('moowoodle', 'moowoodle');
	}

	/**
     * Enqueue JavaScript for admin fronend page and localize script.
     * @return void
     */
	public function enqueue_admin_script() {

		if ( get_current_screen()->id == 'toplevel_page_moowoodle' ) {

			FrontendScripts::admin_load_scripts();
			FrontendScripts::enqueue_script( 'moowoodle-admin-script' );
			FrontendScripts::enqueue_style( 'moowoodle-admin-style' );
			FrontendScripts::localize_scripts( 'moowoodle-admin-script' );
		}
	}

	/**
     * Admin frontend react page.
	 * If plugin is not active it render activation page.
     * @return void
     */
	public static function create_settings_page() {
		
		$page = filter_input(INPUT_GET, 'page', FILTER_DEFAULT) !== null ? filter_input(INPUT_GET, 'page', FILTER_DEFAULT) : '';?>
		<div id="admin-main-wrapper" class="<?php echo $page; ?>">
				<?php
				if (filter_input(INPUT_GET, 'page', FILTER_DEFAULT) == 'moowoodle' && !did_action( 'woocommerce_loaded' ) ) {
					?>
					<a href="javascript:history.back()"><?php echo __("Go Back","moowoodle");?></a>
					<div style="text-align: center; padding: 20px; height: 100%">
						<h2><?php echo __('Warning: Activate WooCommerce and Verify Moowoodle Files', 'moowoodle'); ?></h2>
						<p><?php echo __('To access Moowoodle, please follow these steps:', 'moowoodle'); ?></p>
						<ol style="text-align: left; margin-left: 40px;">
							<li><?php echo __('Activate WooCommerce on your <a href="', 'moowoodle') . home_url() . '/wp-admin/plugins.php'; ?>"><?php echo __('website', 'moowoodle'); ?></a><?php echo __(', if it\'s not already activated.', 'moowoodle'); ?></li>
							<li><?php echo __('Ensure that all Moowoodle files are present in your WordPress installation.', 'moowoodle'); ?></li>
							<li><?php echo __('If you suspect any missing files, consider reinstalling Moowoodle to resolve the issue.', 'moowoodle'); ?></li>
						</ol>
						<p><?php echo __('After completing these steps, refresh this page to proceed.', 'moowoodle'); ?></p>
					</div>
					<?php
					return;
				}
				?>
      	</div>
      	<?php
	}

	/**
	 * Redirct to pro shop url.
	 * @return never
	 */
	public function handle_external_redirects() {
		wp_redirect( esc_url( MOOWOODLE_PRO_SHOP_URL ) );
		die;
	}
}
