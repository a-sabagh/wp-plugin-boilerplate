<?php

namespace WOAP\Packages;

class Request {

    public function input($key,$sanitize_type='string'){
        $value = $_POST[$key] ?? null;
        return (isset($sanitize_type))? $this->sanitize($value,$sanitize_type) : $value;
    }

    public function only($keys,$sanitize_types=[]){
        for($i=0;$i<count($keys);$i++){
            $key = $keys[$i];
            $sanitize_type = $sanitize_types[$i] ?? 'string';
            $values[] = $this->input($key,$sanitize_type);
        }
        return $values ?? [];
    }

	public function except($key = null){
		if(isset($_POST[$key])){
			unset($_POST[$key]);
		}
		$keys = array_keys($_POST);
		return $this->only($keys);
	}

    protected function sanitize($value,$sanitize_type){
        switch($sanitize_type) {
            case 'string':
                return sanitize_text_field( $value );
            case 'integer':
                return intval($value);
            default:
                return $value;
        }
    }

}
