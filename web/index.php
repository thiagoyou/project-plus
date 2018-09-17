<?php
// ambiente de desenvolvimento
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

try {

    $config = require(__DIR__ . '/../config/web.php');
    (new yii\web\Application($config))->run();
    
} catch (\Exception $e) {
    var_dump($e);
    die;
    throw new yii\web\BadRequestHttpException('<p>Erro ao se conectar com base de dados.</p><b>Erro: </b>'.$e->getMessage());
}