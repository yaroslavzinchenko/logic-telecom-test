<?php
declare(strict_types=1);

namespace application\controllers;

use application\core\Controller;
use application\core\View;

class DocumentsController extends Controller
{
    /**
     * Проверяет, прикреплён ли файл.
     *
     * @return void
     */
    public function postDocumentsCheckAttach(): void {
        if (!isset($this->authRules['DOC_SEE_ALL'])) {
            View::errorMessage('Forbidden on line ' . __LINE__, 403);
        }
        if (!empty($_POST['document_id']) && !empty($_POST['actor_id'])) {
            $documentId = intval($_POST['document_id']);
            $actorId = intval($_POST['actor_id']);
            $result = $this->model->checkAttach($documentId, $actorId);
            if ($result) {
                $this->view->message('Attached.');
            }
            View::errorMessage('Not attached.', 404);
        } else {
            View::errorMessage('Not enough input data.', 422);
        }
    }

    /**
     * Обновляет статус.
     *
     * @return void
     */
    public function postDocumentsChangeStatus(): void {
        if (!empty($_POST['document_id']) && !empty($_POST['actor_id']) && !empty($_POST['status_id'])) {
            $documentId = intval($_POST['document_id']);
            $actorId = intval($_POST['actor_id']);
            $statusId = intval($_POST['status_id']);
            $result = $this->model->changeStatus($documentId, $actorId, $statusId);
            if ($result > 0) {
                $this->view->message('Changed.');
            }
            View::errorMessage('Did not change.', 422);
        } else {
            View::errorMessage('Not enough input data.', 422);
        }
    }

    /**
     * Загружает данные.
     *
     * @return void
     */
    public function postDocumentsUploadDoc(): void {
        if (
            !empty($_POST['actor_id'])
            && !empty($_POST['doc_name'])
            && !empty($_FILES['file']
            && !empty($_FILES['file']['tmp_name']))
            && !empty($_FILES['file']['name'])
        ) {
            $actorId = intval($_POST['actor_id']);
            $docName = $_POST['doc_name'];
            if ($docContent = file_get_contents($_FILES['file']['tmp_name'])) {
                $fileName = $_FILES['file']['name'];

                $result = $this->model->uploadDocument($actorId, $docName, $fileName, $docContent);
                if ($result > 0) {
                    $this->view->message('Uploaded.');
                }
                View::errorMessage('Error occurred while trying to upload document.', 422);
            }
        } else {
            View::errorMessage('Not enough input data.', 422);
        }
    }
}