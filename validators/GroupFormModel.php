<?php
require "FormModel.php";

class GroupFormModel extends FormModel
{
    public $name;
    public $color;

    public function load($data)
    {
        $this->name = $data['name'];
        $this->color = $data['color'];
    }

    public function validate()
    {
        if (empty($this->name)) {
            $this->addError('name', 'Name is required.');
            return false;
        }


        if (empty($this->color)) {
            $this->addError('color', 'Color is required.');
            return false;
        }

        if (!$this->isHexColor($this->color)){
            $this->addError('color', 'Invalid color format.');
            return false;
        }
        return true;
    }

    private function isHexColor(string $color)
    {
        return preg_match('/^#[a-f0-9]{6}$/i', $color);
    }
}