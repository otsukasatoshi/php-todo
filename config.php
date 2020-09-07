<?php

// エラーレベル
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// DB設定の定数
define('DSN', 'mysql:dbname=php_lesson;host=localhost;unix_socket=/tmp/mysql.sock');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
