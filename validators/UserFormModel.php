<?php
require "FormModel.php";

class UserFormModel extends FormModel
{
    public $email;
    public $username;

    public function load($data)
    {
        $this->email = $data['email'];
        $this->username = $data['username'];
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

        if (strlen($this->username)> MAXIMUM_TEXT_FIELD_LENGTH ){
            $this->addError('name', 'The maximum length of username is '.MAXIMUM_TEXT_FIELD_LENGTH);
            return false;
        }

        return true;
    }
}