<?php
/**
 * Plugin Name: Staging Prevent Indexing
 * Description: Automatically switches OFF the indexing option if the URL does not match the site URL provided.
 * Version: 1.0
 * Author: tex0gen
 * Author URI: https://freelancedeveloperkent.co.uk/
 * Tested up to: 6.3
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class PI_Prevent_Indexing {
	private $live_site_url;
	private $current_site_url;

	public function __construct() {
		$this->current_site_url = get_site_url();
		$this->live_site_url = rtrim( $this->decode_url( get_option( 'pi_prevent_indexing_live_url', '' ) ), '/' );

		add_action( 'init', [ $this, 'handle_indexing' ] );
		add_action( 'admin_menu', [ $this, 'register_settings_page' ] );
		add_action( 'admin_init', [ $this, 'register_settings' ] );
		add_action( 'admin_notices', [ $this, 'check_for_url_notice' ] );
	}

	public function handle_indexing() {
		$current_blog_public_value = get_option('blog_public');

		// Check if live site URL is defined.
		if ( empty( $this->live_site_url ) ) {
			return;
		}

		if ( $this->current_site_url !== $this->live_site_url && $current_blog_public_value != 0 ) {
			update_option( 'blog_public', 0 );

			// Add action to display admin notice.
			add_action( 'admin_notices', [ $this, 'display_admin_notice' ] );
		}
	}

	public function display_admin_notice() {
		?>
		<div class="notice notice-error is-dismissible">
			<p><?php esc_html_e( 'Warning: The provided live site URL does not match the current site URL. Indexing has been turned OFF!', 'prevent-indexing' ); ?></p>
		</div>
		<?php
	}

	public function check_for_url_notice() {
		if ( empty( $this->live_site_url ) ) {
			?>
			<div class="notice notice-warning is-dismissible">
				<p>
					<?php esc_html_e( 'The live site URL for the Prevent Indexing plugin hasn\'t been set.', 'prevent-indexing' ); ?>
					<a href="<?php echo esc_url( admin_url( 'options-general.php?page=pi_prevent-indexing-settings' ) ); ?>">
						<?php esc_html_e( 'Set it now.', 'prevent-indexing' ); ?>
					</a>
				</p>
			</div>
			<?php
		}
	}

	public function register_settings_page() {
		add_options_page(
			'Prevent Indexing Settings',
			'Prevent Indexing',
			'manage_options',
			'pi_prevent-indexing-settings',
			[ $this, 'settings_page_content' ]
		);
	}

	public function settings_page_content() {
		// Check if the current user has the permissions to manage options.
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Prevent Indexing Settings', 'prevent-indexing' ); ?></h1>
			<form method="post" action="options.php">
				<?php
				settings_fields( 'pi_prevent-indexing-settings-group' );
				do_settings_sections( 'pi_prevent-indexing-settings-group' );
				
				$correct_settings = ( $this->live_site_url && $this->current_site_url === $this->live_site_url ) ? '<div style="color:green;">The live site URL matches the current site URL.</div>' : '<div style="color:red;">The live site URL does not match the current site URL.</div>';
				?>
				<table class="form-table">
					<tr valign="top">
						<th scope="row"><?php esc_html_e( 'Live Site URL', 'prevent-indexing' ); ?><br/><small>Including protocol and trailing slash: https://mysite.com/</small></th>
						<td><input type="text" name="pi_prevent_indexing_live_url" style="width:100%;" value="<?php echo esc_url( $this->live_site_url ); ?>" /></td>
					</tr>
				</table>
				<?php echo $correct_settings; ?>
				<?php submit_button(); ?>
			</form>
		</div>
		<?php
	}

	public function register_settings() {
		register_setting( 'pi_prevent-indexing-settings-group', 'pi_prevent_indexing_live_url', [
			'type'              => 'string',
			'sanitize_callback' => [ $this, 'encode_url' ],
			'default'           => ''
		] );
	}

	public function encode_url( $url ) {
			// Ensure the URL has a protocol. Default to http if none is provided.
			if ( strpos( $url, 'http://' ) === false && strpos( $url, 'https://' ) === false ) {
					$url = 'https://' . $url;
			}

			// Ensure the URL has a trailing slash.
			if ( substr( $url, -1 ) !== '/' ) {
					$url .= '/';
			}

			return base64_encode( $url );
	}

	public function decode_url( $encoded_url ) {
		return base64_decode( $encoded_url );
	}
}

// Initialize the class instance.
new PI_Prevent_Indexing();
