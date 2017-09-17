<?php
/**
 * Plugged functions
 *
 * @package arcade
 */

/**
* Cart Link
* Plugged to adjust the markup
* @since  1.0.0
*/
if ( ! function_exists( 'storefront_cart_link' ) ) {
	function storefront_cart_link() {
		?>
			<a class="cart-contents" href="<?php echo esc_url( WC()->cart->get_cart_url() ); ?>" title="<?php _e( 'View your shopping cart', 'storefront' ); ?>">
				<span class="count"><?php echo wp_kses_data( WC()->cart->get_cart_contents_count() ); ?></span>
				<span class="amount total"><?php echo wp_kses_data( WC()->cart->get_cart_subtotal() ); ?></span>
			</a>
		<?php
	}
}