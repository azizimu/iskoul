<?php

if (!function_exists('initDbConnexion')) {
    function initDbConnexion(): PDO
    {
        try {
            $connection = new PDO(
                'mysql:host=localhost;dbname=iskoul;port=3306;charset=utf8',
                'root',
                ''
            );
        } catch (Exception $exception) {
            echo $exception->getMessage();
        } finally {
            return $connection;
        }
    }
}