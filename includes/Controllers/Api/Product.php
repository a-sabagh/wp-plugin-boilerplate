<?php

namespace ODT\Controllers\Api;

class Product {
    
    public function __construct($service_provider) {
        
    }

    public function test() {
        wp_send_json(array('key' => 'value'));
    }

}
