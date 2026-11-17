<?php
/**
 * Front controller: semua halaman diakses lewat index.php?r=kontroler/aksi
 * Contoh: index.php?r=user/index
 */
define('BASE_PATH', __DIR__);
define('APP_PATH', BASE_PATH . '/app');

require APP_PATH . '/core/bootstrap.php';

Router::dispatch();
