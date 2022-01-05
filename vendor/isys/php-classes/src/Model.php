<?php
namespace isys;

class Model {
    private $values = array();
    public function __call($name, $arguments){
        $method = substr($name,0,3);
        $field_name = substr($name,3,strlen($name));

        switch ($method){
            case "get":
                $this->values[$field_name];
            break;

            case "set":
                $this->values[$field_name] = $arguments[0];
            break;
        }
    }

    public function setValues($data=array()){
        foreach ($data as $k => $v){
            $this->{"set".$k}($v);
        }
    }

    public function getValues(){
        return $this->values;
    }
}