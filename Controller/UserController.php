<?php
include_once "Model/UserModel.php";
require_once "smarty/BaseSmarty.php";
require "validators/UserFormModel.php";
require "Controller/BaseController.php";

class UserController extends BaseController
{

    public function __construct()
    {

        $this->breadcrumbs =
            [
                'Home' => '/seatmap/index.php',
                'User' => '/seatmap/user/index',
            ];
        $this->model = new UserModel();
        $this->activePage = 'user';
    }


    public function getUsers()
    {
        $page = isset($_GET['p']) ? intval($_GET['p']) : 1;
        $number_of_user = $this->countAllUser();
        $totalPage = $number_of_user % ITEM_PER_PAGE == 0 ? intdiv($number_of_user, ITEM_PER_PAGE) : intdiv($number_of_user, ITEM_PER_PAGE) + 1;
        $res_data = [];
        $res_data['totalPage'] = $totalPage;
        $res_data['users'] = $this->model->pagination($page, ITEM_PER_PAGE);;
        echo json_encode($res_data);
    }




    public function addUser()
    {
        $this->breadcrumbs['Add new user'] = '#';

        if (!$this->isSubmit()) {
            $this->render('./user/add_new.tpl');
            return;
        }

        $formModel = new UserFormModel();
        $formModel->load($_POST);

        if (!$formModel->validate() or $_FILES['avatar']['name'] == "") {
            $this->message = "Please input all field";
            $formModel->addError();
        }

        $avatarPath = $this->uploadAvatar();
        if ($avatarPath == "") {
            $this->render('./user/add_new.tpl');
            return;
        }

        if ($this->model->findByEmail($formModel->email)) {
            $this->message = 'Email is exist.';
            $this->render('./user/add_new.tpl');
            return;
        }

        $insert_id = $this->model->add($formModel->username, $formModel->email, $avatarPath);
        if ($insert_id) {
            $this->message = 'Add user success';
        } else {
            $this->message = 'Add user fail.';
        }
        $_SESSION['message'] = $this->message;
        header("Location: /seatmap/user/index");
    }


    private function assignMessage($user)
    {
        $this->smarty->assign('email', $user['email']);
        $this->smarty->assign('username', $user['username']);
        $this->smarty->assign('avatar', $user['avatar']);
    }

    public function updateUser()
    {
        $this->breadcrumbs['Update user'] = '#';
        $user_id = isset($_GET['id']) ? $_GET['id'] : null;
        if (!$this->isValidId($user_id)) {
            $this->render('404notfound.tpl');
            return;
        }

        $user = $this->getUser($user_id);
        if (!$this->isSubmit()) {
            goto end;
        }

        $formModel = new UserFormModel();
        $formModel->load($_POST);

        if (!$formModel->validate()) {
            $this->message = $formModel->getFirstError();
            goto end;
        }

        if ($_FILES['avatar']['name'] != "") $avatarPath = $this->uploadAvatar();
        else $avatarPath = $user['avatar'];

        if ($avatarPath == "") {
            goto end;
        }

        $insert_id = $this->model->edit($user_id, $formModel->username, $formModel->email, $avatarPath);
        if ($insert_id)
            $this->message = 'Update user success';
        else
            $this->message = 'Update user fail.';

        $user = $this->getUser($user_id);

        end:
        $this->assignMessage($user);
        $this->render('./user/edit_user.tpl');
    }

    public function deleteUser()
    {
        $result = [];
        $userId = isset($_POST['id']) ? $_POST['id'] : null;
        if (!$this->isValidId($userId)) {
            $result['message'] = "0";
            echo json_encode($result);
            return;
        }
        $result['message'] = $this->model->delete($userId);
        echo json_encode($result);
    }

    public function index()
    {
        if (isset($_SESSION['message'])) {
            $this->message = $_SESSION['message'];
            unset($_SESSION['message']);
        }
        $this->render('./user/index.tpl');
    }

    private function render(string $template)
    {
        $this->smarty->assign("breadcrumbs", $this->breadcrumbs);
        $this->smarty->assign('activePage', $this->activePage);
        $this->smarty->assign('message', $this->message);
        $this->smarty->display($template);
    }

    private function countAllUser(): int
    {
        $data = $this->model->countAll();
        if (is_object($data)) {
            $row = $data->fetch_assoc();
            return $row['count'];
        }
        return 0;
    }

    private function getUser(int $id)
    {
        $user = $this->model->get($id);
        if (is_object($user)) {
            $row = $user->fetch_assoc();
            return $row;
        }
        return null;
    }

    public function getUserInfo()
    {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            echo json_encode($this->getUser($id));
        }
    }

    private function isValidId($id)
    {
        if (!is_numeric($id)) {
            return false;
        }
        $user = $this->getUser($id);
        if (!isset($user['id'])) {
            return false;
        }
        return $id != null;
    }

    private function isSubmit()
    {
        return (isset($_POST['btn_submit']));
    }
}