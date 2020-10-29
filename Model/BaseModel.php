<?php

abstract class BaseModel
{
    protected $tablename;
    protected $conn;
    protected $stmt;


    public function __construct()
    {
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PWD, DB_NAME, DB_PORT);
        if ($this->conn->connect_error) {
            die('Connect failed: ' . $this->conn->connect_error);
        }
    }

    public function findById($id)
    {
        $query = "SELECT * FROM `$this->tablename` WHERE `id`=?";
        $this->prepare($query);
        $this->bind_param('i',$id);
        $result= $this->query();
        $this->close();
        $data = $result->fetch_assoc();
        return $data;
    }

    public function delete(int $id):bool
    {
        $query = "DELETE FROM `$this->tablename` WHERE id=?";
        $this->prepare($query);
        $this->bind_param('i',$id);
        $result= $this->update();
        $this->close();
        return $result;
    }

    public function countAll()
    {
        $query = "SELECT COUNT(id) AS `count` FROM `$this->tablename`";
        $this->prepare($query);
        $result= $this->query();
        $this->close();
        return $result;
    }

    public function isValidId($id){
        if (!is_numeric($id)) {
            return false;
        }
        $data = $this->findById($id);
        if (!isset($data['id'])) {
            return false;
        }
        return $id != null;
    }

    public function prepare($query)
    {
        $this->stmt = $this->conn->prepare($query);
    }

    public function execute()
    {
        $this->stmt->execute();
    }

    public function query()
    {
        $this->execute();
        $result =  $this->stmt->get_result();
        $data = [];
        if (isset($result)) {
            while ($row = $result->fetch_assoc()) {
                array_push($data, $row);
            }
        }
        return $data;
    }

    public function insert()
    {
        if ($this->execute()) return $this->stmt->insert_id;
        return -1;
    }

    public function update()
    {
        $this->execute();
        $this->stmt->store_result();
        if ( $this->stmt->affected_rows >=1) return 1;
        return 0;
    }

    public function bind_param($format, ...$values)
    {
        $this->stmt->bind_param($format, ...$values);
    }

    public function close()
    {
        $this->stmt->close();
    }

    public function row_count()
    {
        $this->execute();
        $this->stmt->store_result();
        return $this->stmt->num_rows;
    }

    public function pagination(int $page, int $limit)
    {
        $query = null;
        if ($page > 1)
        {
            $offset = $limit*($page - 1);
            $query = "SELECT * FROM `$this->tablename` LIMIT ? OFFSET ?";
            $this->prepare($query);
            $this->bind_param('ii', $limit, $offset);
        }
        else
        {
            $query = "SELECT * FROM `$this->tablename` LIMIT ?";
            $this->prepare($query);
            $this->bind_param('i', $limit);
        }
        $result= $this->query();
        $this->close();
        return $result;
    }

    public function getAll()
    {
        $query = "SELECT * FROM " . $this->tablename;
        $this->prepare($query);
        $result = $this->query();
        $this->close();
        $data = [];
        if (isset($result)) {
            while ($row = $result->fetch_assoc()) {
                array_push($data, $row);
            }
        }
        return $data;
    }
}