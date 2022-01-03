<?php

namespace isys;

use Rain\Tpl;

class PageAdmin extends Page {
    private $tpl;
    private $options;
    private $defaults = array(
        "data"=>array()
    );

    public function __construct($opts=array(),$tpl_dir="/views_admin/"){
        parent::__construct($opts,$tpl_dir);
    }

}