<?php
require_once "smarty/BaseSmarty.php";
require "validators/LoginFormModel.php";
require 'Model/AdminModel.php';

class AuthenticateController
{
    public $message;

    public function signin()
    {
        if (isset($_SESSION[LOGINED])) {
            header("Location: ./index.php");
        }

        if ($this->isSubmit()) {
            $form_model = new LoginFormModel();
            $form_model->load($_POST);
            if ($form_model->validate()) {
                $is_authenticate = $this->authenticate($form_model->email, $form_model->password);
                if ($is_authenticate) {
                    $_SESSION[LOGINED] = LOGINED;
                    header("Location: /seatmap/index.php");
                }

                $form_model->addError('error', 'User or password is incorrect.');
            }
            $this->message = $form_model->getFirstError();
        }
        $this->render();
    }

    public function render()
    {
        $smarty = new BaseSmarty();
        $smarty->assign("message", $this->message);
        $smarty->display('signin.tpl');
    }

    public function authenticate($email, $password)
    {
        $m_admin = new AdminModel();
        $data = $m_admin->authenticate($email, $password);
        $user = $data->fetch_assoc();
        return isset($user['id']);
    }

    public function isSubmit()
    {
        return isset($_POST['btn_submit']);
    }

    public function signout()
    {
        session_destroy();
        header("Location: /seatmap/authenticate/signin");
    }
}