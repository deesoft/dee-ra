<?php
$params = array_merge(
    require(__DIR__ . '/params.php'), require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-app',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'app\controllers',
    'modules' => [
        'admin' => [
            'class' => 'mdm\admin\Module',
            'layout' => 'top-menu'
        ]
    ],
    'components' => [
        'user' => [
            'identityClass' => 'app\models\ar\User',
            'loginUrl' => ['user/login'],
            'enableAutoLogin' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'defaultRoles' => ['/*']
        ],
        'session' => [
            'class' => 'yii\web\DbSession'
        ],
        'response' => [
            'formatters' => [
                'js' => [
                    'class' => 'yii\web\HtmlResponseFormatter',
                    'contentType' => 'text/javascript'
                ]
            ]
        ],
        'assetManager'=>[
            'bundles'=>[
                
            ]
        ]
    ],
    'params' => $params,
];
