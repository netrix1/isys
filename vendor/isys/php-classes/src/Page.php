<?php

namespace isys;

use Rain\Tpl;

class Page {
    private $tpl;
    private $options;
    private $defaults = array(
        "data"=>[]
    );
    public function __construct($opt=array()){
        $this->options = array_merge($this->defaults,$opt);
        $config = array(
            "tpl_dir"       => $_SERVER['DOCUMENT_ROOT']."/views/",
            "cache_dir"     => $_SERVER['DOCUMENT_ROOT']."/views_cache/",
            "debug"         => false // set to false to improve the speed
        );
        Tpl::configure( $config );
        $this->tpl = new Tpl();

        $this->setData($this->options["data"]);

        $this->tpl->draw("header");
    }

    private function setData($data=array()){
        foreach ($data as $k => $v){
            $this->tpl->assign($k,$v);
        }
    }

    public function setTpl($name, $data = array(), $returnHTML = false){
        $this->setData($data);
        return $this->tpl->draw($name,$returnHTML);
    }

    public function __destruct()
    {
        $this->tpl->draw("footer");
    }
}