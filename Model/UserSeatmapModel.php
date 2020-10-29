<?php

require "BaseModel.php";

class UserSeatmapModel extends BaseModel
{

    public function __construct()
    {
        parent::__construct();
        $this->tablename = 'seatmap_user';
    }

    public function add(int $id_user, int $id_seatmap, float $coordinate_x, float $coordinate_y, float $scale_x, float $scale_y, float $rotation): bool
    {
        $query = "INSERT INTO `$this->tablename`( `user_id`, `seat_id`,`coordinates_x`, `coordinates_y`,`scale_x`, `scale_y`, `rotation` )VALUES (?,?,?,?,?,?,?)";
        $this->prepare($query);
        $this->bind_param('iiddddd', $id_user, $id_seatmap, $coordinate_x, $coordinate_y, $scale_x, $scale_y, $rotation);
        $result = $this->insert();
        $this->close();
        return $result;
    }

    public function edit(int $id_user, int $id_seatmap, float $coordinate_x, float $coordinate_y, float $scale_x, float $scale_y, float $rotation): bool
    {
        $query = "UPDATE `$this->tablename` SET `coordinates_x`=?,`coordinates_y`=?,`scale_x`=?, `scale_y`=?, `rotation`=?   WHERE user_id=? AND seat_id=?";
        $this->prepare($query);
        $this->bind_param('dddddii', $coordinate_x, $coordinate_y, $scale_x, $scale_y, $rotation, $id_user, $id_seatmap);
        $result = $this->update();
        $this->close();
        return $result;
    }

    public function delete(int $id_user): bool
    {
        $query = "DELETE FROM `$this->tablename` WHERE user_id= ?";
        $this->prepare($query);
        $this->bind_param('i', $id_user);
        $result = $this->update();
        $this->close();
        return $result;
    }

    public function getUserWithoutPosision(string $keyword)
    {
        if ($keyword == "") {
            $query = "SELECT * FROM `user` WHERE `user`.id NOT IN (SELECT `seatmap_user`.user_id FROM  `seatmap_user`)";
            $this->prepare($query);
        } else {
            $query = "SELECT * FROM `user` WHERE (`user`.id NOT IN (SELECT `seatmap_user`.user_id FROM  `seatmap_user`)) AND (`user`.username LIKE ? OR `user`.email LIKE ?) ";
            $this->prepare($query);
            $this->bind_param('ss', '%' . $keyword . '%', '%' . $keyword . '%');
        }
        $result = $this->query();
        $this->close();
        return $result;
    }

    public function isUserHasPosition(int $user_id, int $seatmap_id)
    {
        $query = "SELECT COUNT(user_id) AS `count` FROM `seatmap_user` WHERE  user_id = ? AND seat_id=?";
        $this->prepare($query);
        $this->bind_param('ii', $user_id, $seatmap_id);
        $result = $this->query();
        $this->close();
        return $result;
    }

    public function findById(int $id)
    {
        $query = "SELECT * FROM `seatmap_user` LEFT JOIN `group` ON  `seatmap_user`.group = `group`.id  WHERE seat_id = ?";
        $this->prepare($query);
        $this->bind_param('i', $id);
        $result = $this->query();
        $this->close();
        return $result;
    }

    public function changeUserGroup(int $user_id, int $group_id)
    {
        $query = "UPDATE `$this->tablename` SET `group`=? WHERE user_id=?";
        $this->prepare($query);
        $this->bind_param('ii', $group_id, $user_id);
        $result = $this->update();
        $this->close();
        return $result;
    }
}