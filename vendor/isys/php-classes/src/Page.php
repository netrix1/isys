<?php

namespace isys;

use Rain\Tpl;

class Page {
    private $tpl;
    private $options;
    private $defaults = array(
        "data"=>array()
    );
    public function __construct($opts=array(),$tpl_dir="/views/"){
        $this->options = array_merge($this->defaults,$opts);
        $config = array(
            "tpl_dir"       => $_SERVER['DOCUMENT_ROOT'].$tpl_dir,
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