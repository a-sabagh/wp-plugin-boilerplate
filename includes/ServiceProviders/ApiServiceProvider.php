<?php

namespace ODT\ServiceProviders;

class ApiServiceProvider {

    public $api_mapper;
    public $modules;

    public function __construct($services, $api_mapper) {
        $this->require_services($services);
        $this->load_services($services);
        $this->api_mapper = $api_mapper;
        add_action("template_include", array($this, "template_redirect"));
    }

    public function template_redirect() {
        $module = get_query_var("odt_module");
        if (empty($module)) {
            return;
        }
        $action = get_query_var("odt_action");
        $params = get_query_var("odt_params");
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        $class = $this->api_mapper[$module];
        $object = $this->get($class);
        if (!is_object($object)) {
            wp_send_json(['error' => "Class {$module} not exist"]);
            return;
        }
        if (!isset($action)) {
            $object->index();
            return;
        }
        $object->{$action}($params);
    }

    public function get($class) {
        return $this->modules[$class];
    }

    public function require_services($services) {
        foreach ($services as $class => $path) {
            require_once $path;
        }
    }

    public function load_services($services) {
        foreach ($services as $class => $path) {
            $this->modules[$class] = new $class($this);
        }
    }

}
