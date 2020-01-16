<?php

namespace ODT\Controllers\Web;

use ODT\Controllers\Web\ProductController;
use ODT\Controllers\Web\OrderController;

class OrderController {
    
    public $order_controller;
    public $product_controller;

    public function __construct($service_provider) {
        $this->product_controller = $service_provider->get(ProductController::class);
    }
    
}
