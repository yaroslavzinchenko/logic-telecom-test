<?php
declare(strict_types=1);

namespace application\lib;

use PDO;

class Db
{
    protected PDO $db;

    public function __construct() {
        $config = require 'application/config/db.php';
        $this->db = new PDO('mysql:host=' . $config['host'] . ';dbname=' . $config['dbname'], $config['user'], $config['password']);
    }

    /**
     * Выполняет запрос к БД.
     *
     * @param string $sql
     * @param array $params
     * @return false|\PDOStatement
     */
    public function query(string $sql, array $params = []): false|\PDOStatement {
        $stmt = $this->db->prepare($sql);
        if (!empty($params)) {
            foreach ($params as $key => $val) {
                $stmt->bindValue(':'.$key, $val);
            }
        }
        $stmt->execute();
        return $stmt;
    }

    /**
     * Используется для получения данных.
     *
     * @param string $sql
     * @param array $params
     * @return array|false
     */
    public function row(string $sql, array $params = []): array|false {
        $result = $this->query($sql, $params);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Используется для вставки или обновления данных.
     *
     * @param string $sql
     * @param array $params
     * @return int
     */
    public function set(string $sql, array $params = []): int {
        $result = $this->query($sql, $params);
        return $result->rowCount();
    }
}