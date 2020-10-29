<?php

require "BaseModel.php";
class UserModel extends  BaseModel {

    public  function __construct()
    {
        parent::__construct();
        $this->tablename='user';
    }

    public function add(string $username, string $email, string $avatar):bool
    {
        $query = "INSERT INTO `$this->tablename`( `username`, `email`,`avatar`) VALUES (?,?,?)";
        $this->prepare($query);
        $this->bind_param('sss', $username, $email, $avatar);
        $result= $this->insert();
        $this->close();
        return $result;
    }

    public function edit(int $id, string $username, string $email, string $avatar):bool
    {
        $query =  "UPDATE `$this->tablename` SET `username`=?,`email`=?,`avatar`=?  WHERE id=?";
        $this->prepare($query);
        $this->bind_param('sssi', $username, $email, $avatar,$id);
        $result= $this->update();
        $this->close();
        return $result;
    }

    public function findByEmail(string $email):bool
    {
        $query = "SELECT * FROM `$this->tablename` WHERE `email`=?";
        $this->prepare($query);
        $this->bind_param('s',$email);
        $result= $this->row_count() !=0;
        $this->close();
        return $result;
    }
}