<?php
require "FormModel.php";
require "utils/Uploader.php";
require "Model/UserModel.php";

class UserFormModel extends FormModel
{
    public $email;
    public $username;
    public $avatar;
    public $id;

    public function load($data)
    {
        $this->email = $data['email'];
        $this->username = $data['username'];
        $this->avatar = $_FILES['avatar']['name'];
        if ($this->mode=='update'){
            $id = intval($_GET['id']);
        }
    }

    public function validate()
    {
        if (empty($this->email)) {
            $this->addError('email', 'Email is required.');
            return false;
        }

        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $this->addError('email', 'Invalid email format');
            return false;
        }

        if (empty($this->username)) {
            $this->addError('username', 'Username is required.');
            return false;
        }

        if (strlen($this->username) > MAXIMUM_TEXT_FIELD_LENGTH) {
            $this->addError('name', 'The maximum length of username is ' . MAXIMUM_TEXT_FIELD_LENGTH);
            return false;
        }

        $model = new UserModel();
        if (!($this->mode == 'update' and !empty($this->avatar))) {
            //validate image
            $uploader = new Uploader();
            $uploader->setDir(AVATAR_USER_DIR);
            $uploader->setExtensions(VALID_IMAGE_TYPES);
            $uploader->setMaxSize(LIMIT_IMAGE_SIZE_MB);
            if ($uploader->uploadFile('avatar')) {
                $this->avatar = $uploader->getUploadName();
            } else {
                $this->addError('avatar', $uploader->getMessage());
                return false;
            }
        }
        else
        {
            $this->avatar = $model->findById(id)['avatar'];
        }
        //validate email is available
        if ($model->findByEmail($this->email)) {
            $this->addError('email', 'Email is exist.');
            return false;
        }

        return true;
    }
}