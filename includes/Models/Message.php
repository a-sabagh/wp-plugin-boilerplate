<?php

use WOAP\Libraries\Model;

namespace WOAP\Models;

class Product extends Model {

    protected $data = array();

    const table = "woap_messages";

    public static function create($data=null){}

    public static function get($id){}

    public static function update($id,$data){}

    public static function delete($id){}

}
