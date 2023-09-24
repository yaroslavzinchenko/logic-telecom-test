<?php
declare(strict_types=1);

namespace application\core;

class View
{
    /**
     * @param string $message
     * @return void
     */
    public function message(string $message): void {
        exit($message);
    }

    /**
     * @param string $message
     * @param int $code
     * @return void
     */
    public static function errorMessage(string $message, int $code): void {
        http_response_code($code);
        exit($message);
    }
}