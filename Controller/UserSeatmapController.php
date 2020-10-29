<?php
include_once "Model/UserSeatmapModel.php";


class UserSeatmapController
{
    public $modelUserSeatmap;

    public function __construct()
    {
        $this->modelUserSeatmap = new UserSeatmapModel();
    }

    public function getUserAvailable()
    {
        $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : "";
        $data = $this->modelUserSeatmap->getUserWithoutPosision($keyword);
        $users = [];
        if (isset($data)) {
            while ($user = $data->fetch_assoc()) {
                array_push($users, $user);
            }
        }
        echo json_encode($users);
    }

    private function isUserInSeatmap(int $id_user, int $id_seatmap): bool
    {
        $result = $this->modelUserSeatmap->isUserHasPosition($id_user, $id_seatmap);
        $row = $result->fetch_assoc();
        if ($row['count'] == 0) return false;
        return true;
    }

    public function changeUserPosition()
    {
        $id_seatmap = $_POST['seatmap_id'];
        $id_user = $_POST['user_id'];
        $coordinate_x = $_POST['x'];
        $coordinate_y = $_POST['y'];
        $scale_x = $_POST['scale_x'];
        $scale_y = $_POST['scale_y'];
        $rotation = $_POST['rotation'];

        $result = [];
        if ($this->isUserInSeatmap($id_user, $id_seatmap)) {
            $result['message'] = $this->modelUserSeatmap->edit($id_user, $id_seatmap, $coordinate_x, $coordinate_y, $scale_x, $scale_y, $rotation);
        } else $result['message'] = $this->modelUserSeatmap->add($id_user, $id_seatmap, $coordinate_x, $coordinate_y, $scale_x, $scale_y, $rotation);
        echo json_encode($result);
    }

    public function deleteUserPosition()
    {
        $id_user = $_POST['user_id'];
        $result = [];
        $result['message'] = $this->modelUserSeatmap->delete($id_user);
        echo json_encode($result);
    }

    public function getSeatmap()
    {
        $seat_id = $_GET['seat_id'];
        $data = $this->modelUserSeatmap->findById($seat_id);
        $userseats = [];
        if (isset($data)) {
            while ($userseat = $data->fetch_assoc()) {
                array_push($userseats, $userseat);
            }
        }
        echo json_encode($userseats);
    }

    public function changeGroup()
    {
        $user_id = $_POST['user_id'];
        $group_id = $_POST['group_id'];
        $result = [];
        $result['message'] = $this->modelUserSeatmap->changeUserGroup($user_id, $group_id);
        echo json_encode($result);
    }
}