<?php

class BaseController
{
    public $breadcrumbs;
    public $model;
    public $smarty;
    public $activePage;
    public $formModel;
    public $message;

    public function __construct()
    {
        $this->smarty = new BaseSmarty();
    }

    public function isSubmit()
    {
        return (isset($_POST['btn_submit']));
    }

    public function render(string $template)
    {
        $this->smarty->assign("breadcrumbs", $this->breadcrumbs);
        $this->smarty->assign('activePage', $this->activePage);
        $this->smarty->assign('message', $this->message);
        $this->smarty->display($template);
    }
}