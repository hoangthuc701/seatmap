<?php
require "FormModel.php";

class LoginFormModel extends FormModel
{
    public $email;
    public $password;

    public function load($data)
    {
        $this->email = $data['email'];
        $this->password = $data['password'];
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

        if (empty($this->password)) {
            $this->addError('password', 'Password is required.');
            return false;
        }
        return true;
    }
}