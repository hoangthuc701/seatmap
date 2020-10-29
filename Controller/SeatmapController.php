<?php
include_once "Model/SeatmapModel.php";
require_once "smarty/BaseSmarty.php";
require "validators/SeatmapFormModel.php";
include "Controller/BaseController.php";


class SeatmapController extends BaseController
{
    public $breadcrumbs;
    public $message;
    public $modelSeatmap;
    public $smarty;
    public $activePage;

    public function __construct()
    {
        $this->message = "";
        $this->breadcrumbs =
            [
                'Home' => '/seatmap/index.php',
                'Seatmap' => '/seatmap/seatmap/index',
            ];
        $this->modelSeatmap = new SeatmapModel();
        $this->smarty = new BaseSmarty();
        $this->activePage = 'seatmap';
    }

    public function index()
    {
        if (isset($_SESSION['message'])) {
            $this->message = $_SESSION['message'];
            unset($_SESSION['message']);
        }
        $this->render('./seatmap/index.tpl');
    }


    public function addSeatMap()
    {

        $this->breadcrumbs['Add new seat map'] = '#';

        if ($this->isSubmit()) {
            $formModel = new SeatmapFormModel();
            $formModel->load($_POST);

            if (!$formModel->validate() or $_FILES['file']['name'] == "") {
                $this->message = "Please input all field";
                goto end;
            }

            $filePath = $this->uploadImage();
            if ($filePath == "") {
                goto end;
            }

            $insert_id = $this->modelSeatmap->add($formModel->name, $filePath, $formModel->description);
            if ($insert_id) $this->message = 'Add seat map success';
            else $this->message = 'Add seat map fail.';
            $_SESSION['message'] = $this->message;
            header("Location: /seatmap/seatmap/index");
        }
        end:
        $this->render('./seatmap/add.tpl');
        return;
    }


    private function assignField($seatmap)
    {
        $this->smarty->assign('name', $seatmap['name']);
        $this->smarty->assign('description', $seatmap['description']);
        $this->smarty->assign('file', $seatmap['file']);
    }


    public function validateUpdate($seatmap, $formModel)
    {
        $formModel->load($_POST);

        if (!$formModel->validate()) {
            $this->message = $formModel->getFirstError();
            return false;
        }
        if ($_FILES['file']['name'] != "") {
            $filePath = $this->uploadImage();
        } else {
            $filePath = $seatmap['file'];
        };
        if ($filePath == "") {
            return false;
        }
        return $filePath;
    }

    public function update()
    {
        $this->breadcrumbs['Update seatmap'] = '#';
        $formModel = new SeatmapFormModel();

        $seat_id = isset($_GET['id']) ? $_GET['id'] : null;
        if (!$this->isValidId($seat_id)) {
            $this->render('404notfound.tpl');
            return;
        }
        $seatmap = $this->getSeatmap($seat_id);
        if (!$this->isSubmit()) {
            goto end;
        }
        $validate_result = $this->validateUpdate($seatmap, $formModel);
        if (!$validate_result) {
            goto end;
        }
        $insert_id = $this->modelSeatmap->edit($seat_id, $formModel->name, $validate_result, $formModel->description);
        if ($insert_id)
            $this->message = 'Update seatmap success';
        else
            $this->message = 'Update seatmap fail.';
        $seatmap = $this->getSeatmap($seat_id);
        end:
        $this->assignField($seatmap);
        $this->render('./seatmap/edit.tpl');
    }

    public function arrangeSeatmap()
    {
        $id = isset($_GET['map']) ? $_GET['map'] : null;
        if (!$this->isValidId($id)) {
            $this->render('404notfound.tpl');
            return;
        }
        $this->breadcrumbs['Arrange seat map'] = '#';
        $this->render('./seatmap/arrange.tpl');
    }

    public function getSeatmaps()
    {
        $seatmaps = $this->modelSeatmap->getAll();
        echo json_encode($seatmaps);
    }

    private function isValidImage(array $file): string
    {
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        if (!in_array($extension, VALID_IMAGE_TYPES)) {
            return 'Only JPG, PNG file are allowed.';
        }
        $isImage = getimagesize($file['tmp_name']);
        if (!$isImage) {
            return 'Your file is not an image';
        }
        $sizeOfImage = $file['size'];
        if ($sizeOfImage > LIMIT_IMAGE_SIZE) {
            return 'Your file is too large.';
        }
        return "";
    }

    private function uploadImage(): string
    {
        $extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
        $target_file = SEATMAP_DIR . uniqid() . '.' . $extension;
        $validImageMsg = $this->isValidImage($_FILES['file']);
        if ($validImageMsg != "") {
            $this->message = $validImageMsg;
            return "";
        }
        if (!move_uploaded_file($_FILES['file']['tmp_name'], $target_file)) {
            $this->message = 'Upload image failed.';
            return "";
        }
        return $target_file;
    }

    private function getSeatmap(int $id)
    {
        $user = $this->modelSeatmap->findById($id);
        return $user;
    }

    public function getSeatmapInfo()
    {
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        if ($this->isValidId($id)) {
            $data = $this->getSeatmap($id);
            if ($data) {
                echo json_encode($data);
                return;
            }
        }
        $data['message'] = false;
        echo json_encode($data);

    }

    public function deleteSeatmap()
    {
        $id = isset($_POST['id']) ? $_POST['id'] : null;

        if (!$this->isValidId($id)) {
            $data['message'] = false;
            echo json_encode($data);
            return;
        }
        $deleteResult = $this->modelSeatmap->delete($id);
        $this->message = $deleteResult ? 'Success' : 'Fail';
        echo '{"message": ' . $deleteResult . '}';
    }

    private function isSubmit()
    {
        return isset($_POST['btn_submit']);
    }


    private function isValidId($id)
    {
        if (!is_numeric($id)) {
            return false;
        }
        $seatmap = $this->getSeatmap($id);
        if (!isset($seatmap['id'])) {
            return false;
        }
        return $id != null;
    }
}
