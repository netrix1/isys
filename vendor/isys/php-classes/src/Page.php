<?php

namespace isys;

use http\Params;
use Rain\Tpl;
use isys\Model\User;

class Page {

    private $tpl;
    private $options = [];
    private $defaults = [
        "header"=>true,
        "footer"=>true,
        "data"=>[],
        "fatal_error"=>""
    ];
    private $page_name = "";

    public function __construct($opts = array(), $tpl_dir = "/views/"){
        $this->options = array_merge($this->defaults, $opts);
        $config = array(
            "tpl_dir"       => $_SERVER['DOCUMENT_ROOT'].$tpl_dir,
            "cache_dir"     => $_SERVER['DOCUMENT_ROOT']."/views_cache/",
            "auto_escape"   => false,
            "debug"         => false // set to false to improve the speed
        );

        Tpl::configure( $config );
        $this->tpl = new Tpl();

        // insert user data
        if (isset($_SESSION[User::SESSION])){
            $this->tpl->assign("user", $_SESSION[User::SESSION]);
        }

        $this->tpl->assign("menu", ISYS_FMENU);
        $this->tpl->assign("const", get_defined_constants(true)['user']);

        if ($this->options["fatal_error"]!=""){
            $this->tpl->assign("fatal_error",$this->options["fatal_error"]);
        }

        $this->setData($this->options["data"]);

        if ($this->options["header"]===true) $this->tpl->draw("header");
    }

    private function setData($data=array()){

        foreach ($data as $k => $v) {

            $this->tpl->assign($k, $v);

        }
    }

    /**
     * @param array $options
     */
    public function setOptions($options)
    {
        $this->options = $options;
    }

    public function setTpl($name, $data = array(), $returnHTML = false){
        $this->page_name = $name;
        $this->setData($data);
        return $this->tpl->draw($name,$returnHTML);
    }

    public function __destruct()
    {
        if ($this->options["footer"]===true){
            $this->tpl->draw("footer");
            if (file_exists($_SERVER['DOCUMENT_ROOT']."/views_admin/".$this->page_name."_js.html")){
                $this->tpl->draw($this->page_name."_js");
            }
            $this->tpl->draw("end");
        }
    }
}