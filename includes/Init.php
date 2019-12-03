<?php

namespace ODT;

use ODT\ServiceProviders\WebServiceProvider;
use ODT\ServiceProviders\ApiServiceProvider;

defined('ABSPATH') || exit;

class Init {
    
    const first_flush_option = "first_flush_permalinks";

    public $version;
    public $web_slug;
    public $api_slug;

    public function __construct($version, $web_slug, $api_slug) {
        $this->version = $version;
        $this->web_slug = $web_slug;
        $this->api_slug = $api_slug;
        add_action("init", array($this, "add_rewrite_rule"));
        add_action("admin_notices", array($this, "first_flush_notice"));
        add_action("update_option_permalink_structure", function() {
            update_option(self::first_flush_option, true);
        });
        $this->boot_loader();
        $this->boot_service_provider();
    }

    public function add_rewrite_rule() {
        add_rewrite_rule("^{$this->api_slug}/([^/]*)/?([^/]*)/?([^/]*)/?$", 'index.php?odt_module=$matches[1]&odt_action=$matches[2]&odt_params=$matches[3]', "top");
        add_rewrite_tag("%odt_module%", "([^/]*)");
        add_rewrite_tag("%odt_action%", "([^/]*)");
        add_rewrite_tag("%odt_params%", "([^/]*)");
    }

    public function boot_loader() {
        require_once trailingslashit(__DIR__) . "Models/Product.php";
        require_once trailingslashit(__DIR__) . "Databases/ProductDB.php";
    }

    public function first_flush_notice() {
        if (get_option(self::first_flush_option)) {
            return;
        }
        ?>
        <div class="error">
            <p>
                <?php esc_html_e("To make the api-boilerplate plugin worked Please first "); ?>
                <a href="<?php echo get_admin_url(); ?>/options-permalink.php" title="<?php esc_attr_e("Permalink Settings") ?>" >
                    <?php esc_html_e("Flush rewrite rules"); ?>
                </a>
            </p>
        </div>
        <?php
    }

    public function boot_service_provider() {
        # Web Services
        $web_services = array(
            \ODT\Controllers\Web\ProductLogic::class => trailingslashit(__DIR__) . "Controllers/Web/ProductLogic.php",
            \ODT\Controllers\Web\ProductController::class => trailingslashit(__DIR__) . "Controllers/Web/ProductController.php",
            \ODT\Controllers\Web\CartController::class => trailingslashit(__DIR__) . "Controllers/Web/CartController.php",
            \ODT\Controllers\Web\OrderController::class => trailingslashit(__DIR__) . "Controllers/Web/OrderController.php",
        );
        # Api Services
        $api_services = array(
            \ODT\Controllers\Api\Product::class => trailingslashit(__DIR__) . "Controllers/Api/Product.php",
            \ODT\Controllers\Api\Cart::class => trailingslashit(__DIR__) . "Controllers/Api/Cart.php"
        );
        $api_mapper = array(
            "Product" => \ODT\Controllers\Api\Product::class,
            "Cart" => \ODT\Controllers\Api\Cart::class
        );
        # BootServices
        $this->app_service_providers($web_services, $api_services, $api_mapper);
    }

    public function app_service_providers($web_services, $api_services, $api_mapper) {
        require_once trailingslashit(__DIR__) . "ServiceProviders/WebServiceProvider.php";
        require_once trailingslashit(__DIR__) . "ServiceProviders/ApiServiceProvider.php";
        new WebServiceProvider($web_services);
        new ApiServiceProvider($api_services, $api_mapper);
    }

}
