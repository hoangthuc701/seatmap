<?php

require "BaseModel.php";

class SeatmapModel extends BaseModel
{

    public function __construct()
    {
        parent::__construct();
        $this->tablename = 'seatmap';
    }

    public function add($name, string $filename, string $description)
    {
        $query = "INSERT INTO `$this->tablename`( `name`, `file`,`description`) VALUES (?,?,?)";
        $this->prepare($query);
        $this->bind_param('sss', $name, $filename, $description);
        $result = $this->insert();
        $this->close();
        return $result;
    }

    public function edit(int $id, string $name, string $filename, string $description)
    {
        $query = "UPDATE `$this->tablename` SET `name`= ?,`file`=?, `description`=? WHERE id=?";
        $this->prepare($query);
        $this->bind_param('sssi', $name, $filename, $description, $id);
        $result = $this->update();
        $this->close();
        return $result;
    }
}