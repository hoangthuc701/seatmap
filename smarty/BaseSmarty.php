
<?php
require_once "smarty/Smarty.class.php";
class BaseSmarty extends Smarty
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplateDir('view');
        $this->setCompileDir('view_c');
        $this->error_reporting = E_ALL & ~E_NOTICE;
    }
}