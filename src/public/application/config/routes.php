<?php
declare(strict_types=1);

return [

    /*'' => [
        'controller' => 'documents',
        'action' => 'index',
    ],*/

    'documents/check-attach' => [
        'controller' => 'documents',
        'action' => 'checkAttach',
    ],

    'documents/change-status' => [
        'controller' => 'documents',
        'action' => 'changeStatus',
    ],

    'documents/upload-doc' => [
        'controller' => 'documents',
        'action' => 'uploadDoc',
    ],

];