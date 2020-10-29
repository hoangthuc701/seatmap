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

    public function isValidImage(array $file): string
    {
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        if (!in_array($extension, VALID_IMAGE_TYPES)) {
            return 'Only JPG, PNG file are allowed.';
        }

        $isImage = getimagesize($file['tmp_name']);
        //var_dump($isImage);
        //die();
        if (!$isImage) {
            return 'Your file is not an image';
        }
        $sizeOfImage = $file['size'];
        if ($sizeOfImage > LIMIT_IMAGE_SIZE) {
            return 'Your file is too large.';
        }
        return "";
    }

    public function uploadAvatar(): array
    {
        $result = array('file_path' => '', 'error' => '');
        $extension = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
        $target_file = AVATAR_USER_DIR . uniqid() . '.' . $extension;
        $validImageMsg = $this->isValidImage($_FILES['avatar']);
        if ($validImageMsg) {
            $result['error'] = $validImageMsg;
            return $result;
        }

        if (!move_uploaded_file($_FILES['avatar']['tmp_name'], $target_file)) {
            $result['error'] = 'Upload image failed.';
            return $result;
        }

        $result['file_path'] = $target_file;

        return $result;
    }
}