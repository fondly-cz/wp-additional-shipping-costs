<?php
/*
Plugin Name: Additional Shipping Costs
Plugin URI: https://github.com/fondly-cz/wp-additional-shipping-costs
Description: Add specific extra costs per shipping class and method.
Version: 1.0.1
Author: fondly.cz<spoluprace@fondly.cz>
Author URI: https://www.fondly.cz
*/

namespace FondlyCz\AdditionalShippingCosts;

if (!defined('ABSPATH')) {
    exit;
}

require 'vendor/autoload.php';

use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

class WP_Additional_Shipping_Costs
{

    public function __construct()
    {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_filter('woocommerce_package_rates', array($this, 'add_shipping_costs'), 100000, 2);
        add_action('admin_init', array($this, 'register_settings'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_styles'));
        add_action('plugins_loaded', array($this, 'load_plugin_textdomain'));

        $myUpdateChecker = PucFactory::buildUpdateChecker(
            'https://github.com/fondly-cz/wp-additional-shipping-costs',
            __FILE__,
            'wp-additional-shipping-costs'
        );
        $myUpdateChecker->getVcsApi()->enableReleaseAssets();
    }

    public function load_plugin_textdomain()
    {
        load_plugin_textdomain('wp-additional-shipping-costs', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }

    public function enqueue_admin_styles($hook)
    {
        if ($hook != 'woocommerce_page_wp-additional-shipping-costs') {
            return;
        }
        wp_enqueue_style('asc-admin-style', plugin_dir_url(__FILE__) . 'admin-style.css');
    }

    public function add_admin_menu()
    {
        add_submenu_page(
            'woocommerce',
            __('Additional Shipping Costs', 'wp-additional-shipping-costs'),
            __('Shipping Costs', 'wp-additional-shipping-costs'),
            'manage_woocommerce',
            'wp-additional-shipping-costs',
            array($this, 'settings_page_html')
        );
    }

    public function register_settings()
    {
        register_setting('wp_additional_shipping_costs_group', 'wp_additional_shipping_costs');
    }

    public function settings_page_html()
    {
        if (!current_user_can('manage_woocommerce')) {
            return;
        }

        if (!class_exists('WooCommerce')) {
            echo '<div class="error"><p>' . __('WooCommerce is not active.', 'wp-additional-shipping-costs') . '</p></div>';
            return;
        }

        $settings = get_option('wp_additional_shipping_costs', array());
        $tax_included = isset($settings['tax_included']) ? $settings['tax_included'] : false;
        $costs = isset($settings['costs']) ? $settings['costs'] : array();

        $shipping_classes = \WC()->shipping->get_shipping_classes();
        $shipping_methods = \WC()->shipping->get_shipping_methods();

        ?>
        <div class="wrap">
            <h1>
                <?php _e('Additional Shipping Costs Configuration', 'wp-additional-shipping-costs'); ?>
            </h1>
            <form action="options.php" method="post">
                <?php
                settings_fields('wp_additional_shipping_costs_group');
                do_settings_sections('wp_additional_shipping_costs_group');
                ?>

                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <?php _e('Tax Setting', 'wp-additional-shipping-costs'); ?>
                        </th>
                        <td>
                            <label>
                                <input type="checkbox" name="wp_additional_shipping_costs[tax_included]" value="1" <?php checked($tax_included, 1); ?>>
                                <?php _e('The prices entered below include tax', 'wp-additional-shipping-costs'); ?>
                            </label>
                            <p class="description">
                                <?php _e('If checked, we will calculate the net amount before adding to the shipping cost, based on standard shipping tax rates.', 'wp-additional-shipping-costs'); ?>
                            </p>
                        </td>
                    </tr>
                </table>

                <br>
                <h3>
                    <?php _e('Cost Matrix', 'wp-additional-shipping-costs'); ?>
                </h3>

                <?php if (empty($shipping_classes)): ?>
                    <div class="notice notice-warning inline">
                        <p>
                            <?php _e('No shipping classes found in WooCommerce. Please create some shipping classes first.', 'wp-additional-shipping-costs'); ?>
                        </p>
                    </div>
                <?php else: ?>
                    <table class="asc-matrix">
                        <thead>
                            <tr>
                                <th style="text-align: left;">
                                    <?php _e('Shipping Method / Class', 'wp-additional-shipping-costs'); ?>
                                </th>
                                <?php foreach ($shipping_classes as $class): ?>
                                    <th><?php echo esc_html($class->name); ?></th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($shipping_methods as $method_id => $method): ?>
                                <tr>
                                    <td style="text-align: left;">
                                        <strong><?php echo esc_html($method->method_title); ?></strong>
                                        <br><small>(<?php echo esc_html($method_id); ?>)</small>
                                    </td>
                                    <?php foreach ($shipping_classes as $class):
                                        $class_id = $class->term_id;
                                        $val = isset($costs[$class_id][$method_id]) ? $costs[$class_id][$method_id] : '';
                                        ?>
                                        <td>
                                            <input type="number" step="0.01" min="0" class="asc-input"
                                                name="wp_additional_shipping_costs[costs][<?php echo $class_id; ?>][<?php echo $method_id; ?>]"
                                                value="<?php echo esc_attr($val); ?>" placeholder="0.00">
                                        </td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endforeach; ?>
                            <tr>
                                <td style="text-align: left;"><strong>
                                        <?php _e('Options', 'wp-additional-shipping-costs'); ?>
                                    </strong></td>
                                <?php foreach ($shipping_classes as $class):
                                    $class_id = $class->term_id;
                                    $per_piece = isset($costs[$class_id]['per_piece']) ? $costs[$class_id]['per_piece'] : 0;
                                    ?>
                                    <td>
                                        <label>
                                            <input type="checkbox"
                                                name="wp_additional_shipping_costs[costs][<?php echo $class_id; ?>][per_piece]"
                                                value="1" <?php checked($per_piece, 1); ?>>
                                            <br><small>
                                                <?php _e('Add per piece', 'wp-additional-shipping-costs'); ?>
                                            </small>
                                        </label>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                        </tbody>
                    </table>
                <?php endif; ?>

                <?php submit_button(__('Save Settings', 'wp-additional-shipping-costs')); ?>
            </form>
        </div>
        <?php
    }

    public function add_shipping_costs($rates, $package)
    {
        if (!class_exists('WooCommerce'))
            return $rates;

        $settings = get_option('wp_additional_shipping_costs', array());
        if (empty($settings))
            return $rates;

        $tax_included = isset($settings['tax_included']) && $settings['tax_included'] == 1;
        $costs_config = isset($settings['costs']) ? $settings['costs'] : array();

        // Calculate quantity per shipping class in the package
        $class_counts = array();
        foreach ($package['contents'] as $item_key => $values) {
            if ($values['quantity'] > 0) {
                $product = $values['data'];
                $class_id = $product->get_shipping_class_id();
                if ($class_id) {
                    if (!isset($class_counts[$class_id])) {
                        $class_counts[$class_id] = 0;
                    }
                    $class_counts[$class_id] += $values['quantity'];
                }
            }
        }

        if (empty($class_counts))
            return $rates;

        foreach ($rates as $key => $rate) {
            // $rate is an object of WC_Shipping_Rate
            // Method ID usually matches the registered method ID, but rate ID might contain instance ID (e.g. flat_rate:1).
            // We need to match against the generic method ID (e.g. flat_rate).
            $method_id = $rate->method_id;

            $total_add_cost = 0;

            foreach ($class_counts as $class_id => $qty) {
                // Check if we have a cost setting for this class + method
                if (isset($costs_config[$class_id][$method_id]) && is_numeric($costs_config[$class_id][$method_id])) {
                    $cost_val = (float) $costs_config[$class_id][$method_id];
                    if ($cost_val > 0) {
                        $per_piece = isset($costs_config[$class_id]['per_piece']) && $costs_config[$class_id]['per_piece'] == 1;

                        if ($per_piece) {
                            $total_add_cost += $cost_val * $qty;
                        } else {
                            $total_add_cost += $cost_val;
                        }
                    }
                }
            }

            if ($total_add_cost > 0) {
                $current_cost = $rate->cost;

                // Logic for tax included inputs
                if ($tax_included) {
                    // We assume standard shipping tax rates apply.
                    $tax_rates = \WC_Tax::get_shipping_tax_rates();
                    $taxes = \WC_Tax::calc_inclusive_tax($total_add_cost, $tax_rates);
                    $net_add = $total_add_cost - array_sum($taxes);
                    $total_add_cost = $net_add;
                }

                $new_cost = $current_cost + $total_add_cost;
                $rate->set_cost($new_cost);

                // Recalculate taxes on the new cost (WC calculates taxes on net cost)
                if (!empty($rate->taxes)) {
                    $new_taxes = \WC_Tax::calc_shipping_tax($new_cost, \WC_Tax::get_shipping_tax_rates());
                    $rate->set_taxes($new_taxes);
                }
            }
        }

        return $rates;
    }
}

new WP_Additional_Shipping_Costs();
