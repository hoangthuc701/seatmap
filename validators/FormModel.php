<?php

abstract class FormModel
{
    protected $errors = [];

    public function addError($attribute, $error)
    {
        $this->errors[$attribute] = $error;
    }

    public function getError($attribute)
    {
        return isset($this->errors[$attribute]) ? $this->errors[$attribute] : '';
    }

    public function getErrors(){
        return $this->errors;
    }
    public function getFirstError()
    {
        $errors = $this->getErrors();
        return $errors?array_values($errors)[0]:"";
    }
    public abstract function load($data);
    public abstract function validate();
}