<?php
/**
 * Storefront WooCommerce hooks
 *
 * @package storefront
 */

/**
 * Styles
 *
 * @see  storefront_woocommerce_scripts()
 */

/**
 * Layout
 *
 * @see  storefront_before_content()
 * @see  storefront_after_content()
 * @see  woocommerce_breadcrumb()
 * @see  storefront_shop_messages()
 */

remove_action( 'storefront_footer',                  'storefront_handheld_footer_bar',           999 );
