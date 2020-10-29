<?php

require_once "BaseModel.php";

class GroupModel extends BaseModel
{

    public function __construct()
    {
        parent::__construct();
        $this->tablename = 'group';
    }


    public function add(string $name, string $color)
    {
        $query = "INSERT INTO `$this->tablename`( `name`, `color`) VALUES (?,?)";
        $this->prepare($query);
        $this->bind_param('ss', $name, $color);
        $result = $this->insert();
        $this->close();
        return $result;
    }

    public function edit(int $id, string $name, string $color)
    {
        $query = "UPDATE `$this->tablename` SET `name`= ?,`color`=? WHERE id=?";
        echo $query;
        $this->prepare($query);
        $this->bind_param('ssi', $name, $color, $id);
        $result = $this->update();
        $this->close();
        return $result;
    }
}