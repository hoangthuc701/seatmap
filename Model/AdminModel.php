<?php

require "BaseModel.php";

class AdminModel extends BaseModel {

    public function __construct()
    {
        parent::__construct();
        $this->tablename='admin';
    }


    public function authenticate(string $email, string $password)
    {
        $hash_password = md5($password);
        $query = "SELECT * FROM $this->tablename WHERE email= ? AND password= ?";
        $this->prepare($query);
        $this->bind_param('ss',$email,$hash_password);
        $result= $this->query();
        $this->close();
        return $result;
    }
}
