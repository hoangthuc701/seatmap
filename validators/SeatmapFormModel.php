<?php
require "FormModel.php";

class SeatmapFormModel extends FormModel
{
    public $name;
    public $description;


    public function load($data)
    {
        $this->name = $data['name'];
        $this->description = $data['description'];
    }

    public function validate()
    {
        if (empty($this->name)) {
            $this->addError('name', 'Name is required.');
            return false;
        }

        if (strlen($this->name)> MAXIMUM_TEXT_FIELD_LENGTH ){
            $this->addError('name', 'The maximum length of name is '.MAXIMUM_TEXT_FIELD_LENGTH);
            return false;
        }

        if (empty($this->description)) {
            $this->addError('description', 'Description is required.');
            return false;
        }

        if (strlen($this->description)> MAXIMUM_TEXTAREA_FIELD_LENGTH ){
            $this->addError('name', 'The maximum length of description is '.MAXIMUM_TEXTAREA_FIELD_LENGTH);
            return false;
        }

        return true;
    }
}