<?php
declare(strict_types=1);

namespace application\models;

use application\core\Model;

class Documents extends Model
{
    /**
     * Проверяет, прикреплён ли файл.
     *
     * @param int $documentId
     * @param int $actorId
     * @return array|false
     */
    public function checkAttach(int $documentId, int $actorId): array|false {
        // В тестовом примере просто выбиралась строчка из БД по определённым id.
        // Я изменил запрос, чтобы была проверка на наличие прикреплённого файла в записи.

        $result = $this->db->row(
            'SELECT id, actor_id FROM documents 
                    WHERE id = :document_id 
                      AND actor_id = :actor_id 
                      AND doc_content IS NOT NULL
                      AND file_name IS NOT NULL',
            ['document_id' => $documentId, 'actor_id' => $actorId]
        );
        return $result;
    }

    /**
     * Обновляет статус.
     *
     * @param int $documentId
     * @param int $actorId
     * @param int $statusId
     * @return int
     */
    public function changeStatus(int $documentId, int $actorId, int $statusId): int {
        // Как мне показалось, в тестовом примере неправильно написан запрос.
        // Во-первых происходит обновление всех передаваемых полей, а не только статуса.
        // Во-вторых, нет оператора WHERE, то есть запрос построен некорректно.
        // Я изменил запрос, чтобы обновлялся только статус, добавил WHERE.

        $result = $this->db->set(
            'UPDATE documents SET status_id = :status_id WHERE id = :document_id AND actor_id = :actor_id',
            ['status_id' => $statusId, 'document_id' => $documentId, 'actor_id' => $actorId]
        );
        return $result;
    }

    /**
     * Загружает данные.
     *
     * @param int $actorId
     * @param string $docName
     * @param string $fileName
     * @param string $docContent
     * @return int
     */
    public function uploadDocument(int $actorId, string $docName, string $fileName, string $docContent): int {
        $result = $this->db->set(
            'INSERT INTO documents (actor_id, doc_name, file_name, doc_content, status_id) 
                VALUES (:actor_id, :doc_name, :file_name, :doc_content, 1)',
            [
                'actor_id' => $actorId,
                'doc_name' => $docName,
                'file_name' => $fileName,
                'doc_content' => $docContent
            ]
        );
        return $result;
    }
}