<?php
/**
 * Created by PhpStorm.
 * User: НЮКазанков
 * Date: 14.08.2018
 * Time: 17:06
 */

namespace common\bootstrap;

use frontend\services\auth\PasswordResetService;
use frontend\services\contact\ContactService;
use yii\base\BootstrapInterface;
use yii;

class SetUp implements BootstrapInterface
{
    public function bootstrap($app) : void
    {
        $container = \Yii::$container;
//Так тоже можно
//        $container->setSingleton(PasswordResetService::class, function () use ($app){
//            return new PasswordResetService([Yii::$app->params['adminEmail'] => Yii::$app->name . ' robot']);
//        });

        $container->setSingleton(PasswordResetService::class, [], [
            $app->mailer
        ]);
        $container->setSingleton(ContactService::class, [], [
            $app->params['adminEmail'],
            $app->mailer
        ]);
    }
}