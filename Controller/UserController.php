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
        $number_of_user = $this->model->countAll();
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
            $formModel = new UserFormModel();
            $formModel->load($_POST);

            if ($formModel->validate()) {
                $insert_id = $this->model->add($formModel->username, $formModel->email, $formModel->avatar);
                $this->message = $insert_id ? 'Add user success' : 'Add user fail.';
                $_SESSION['message'] = $this->message;
                header("Location: /seatmap/user/index");
            }
            $this->message = $formModel->getFirstError();
        }
        $this->render('./user/add_new.tpl');
    }


    private function assignField($user)
    {
        $this->smarty->assign('email', $user['email']);
        $this->smarty->assign('username', $user['username']);
        $this->smarty->assign('avatar', $user['avatar']);
    }

    public function updateUser()
    {
        $this->breadcrumbs['Update user'] = '#';
        $user_id = intval($_GET['id']);

        $user = $this->getUser($user_id);
        if ($this->isSubmit()) {
            $formModel = new UserFormModel();
            $formModel->setMode('update');
            $formModel->load($_POST);

            if ($formModel->validate()) {
                $insert_id = $this->model->edit($user_id, $formModel->username, $formModel->email, $formModel->avatar);
                $this->message = $insert_id ? 'Update user success' : 'Update user fail.';
            }
            else
            {
                $this->message = $formModel->getFirstError();
            }
        }
        $user = $this->getUser($user_id);
        $this->assignField($user);
        $this->render('./user/edit_user.tpl');
    }

    public function deleteUser()
    {
        $result = [];
        $userId = intval($_POST['id']);
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

    private function getUser(int $id)
    {
        $user = $this->model->get($id);
        return $user;
    }

    public function getUserInfo()
    {
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            echo json_encode($this->getUser($id));
        }
    }
}