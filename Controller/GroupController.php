<?php
include_once "Model/GroupModel.php";
require_once "smarty/BaseSmarty.php";
require "validators/GroupFormModel.php";
include "Controller/BaseController.php";

class GroupController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->breadcrumbs =
            [
                'Home' => '/seatmap/index.php',
                'Group' => '/seatmap/group/index',
            ];
        $this->model = new GroupModel();
        $this->activePage = 'group';
        $this->formModel = new GroupFormModel();
    }


    private function isValidId($id)
    {
        if (!is_numeric($id)) {
            return false;
        }
        $group = $this->getGroup($id);
        if (!isset($group['id'])) {
            return false;
        }
        return $id != null;
    }



    public function index()
    {
        if (isset($_SESSION['message'])) {
            $this->message = $_SESSION['message'];
            unset($_SESSION['message']);
        }
        $this->render('./group/index.tpl');
    }

    public function add()
    {
        $this->breadcrumbs['Add new group'] = '#';
        if (!$this->isSubmit()) {
            $this->render('./group/add_new.tpl');
            return;
        }

        $this->formModel->load($_POST);
        if (!$this->formModel->validate()) {
            $this->message = $this->formModel->getFirstError();
            $this->render('./group/add_new.tpl');
            return;
        }
        $insert_id = $this->model->add($this->formModel->name, $$this->formModel->color);
        if ($insert_id) {
            $this->message = 'Add group success';
        } else {
            $this->message = 'Add group fail.';
        }
        $_SESSION['message'] = $this->message;
        header("Location: /seatmap/group/index");
    }

    public function update()
    {
        $this->breadcrumbs['Update group'] = '#';
        $formModel = new GroupFormModel();
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        if (!$this->isValidId($id)) {
            $this->render('404notfound.tpl');
            return;
        }
        $group = $this->getGroup($id);
        if (!$this->isSubmit()) {
            goto end;
        }
        $formModel->load($_POST);
        if (!$formModel->validate()) {
            $this->message = $formModel->getFirstError();
            goto end;
        }
        $insert_id = $this->model->edit($id, $formModel->name, $formModel->color);
        if ($insert_id)
            $this->message = 'Update group success';
        else
            $this->message = 'Update group fail.';
        $group = $this->getGroup($id);
        end:
        $this->assignField($group);
        $this->render('./group/edit.tpl');
    }

    public function delete()
    {
        $id = isset($_POST['id']) ? $_POST['id'] : null;
        $result['message'] = $this->model->delete($id);
        echo json_encode($result);
    }

    public function getGroups()
    {
        $groups = $this->model->getAll();
        echo json_encode($groups);
    }

    public function getGroup($id)
    {
        $group = $this->model->findById($id);
        return $group;
    }

    private function assignField($group)
    {
        $this->smarty->assign('name', $group['name']);
        $this->smarty->assign('color', $group['color']);
    }

}