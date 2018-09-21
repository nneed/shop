<?php
/**
 * Created by PhpStorm.
 * User: НЮКазанков
 * Date: 14.08.2018
 * Time: 17:06
 */

namespace common\bootstrap;

use shop\services\auth\PasswordResetService;
use shop\services\auth\SignUpService;
use shop\services\contact\ContactService;
use yii\base\BootstrapInterface;
use yii\mail\MailerInterface;
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

        $container->setSingleton(MailerInterface::class, function () use ($app) {
            return $app->mailer; // так происходит вызов при загрузке
        });
            //так можно не указывать зависимости Yii сам поймет какие нужны классы через конструктор.
        // Вывызов $app->mailer произойдет в момент в момент обращения, а не при регистраиции
//        $container->setSingleton(PasswordResetService::class, [], [
//          $app->mailer
//        ]);
        $container->setSingleton(ContactService::class, [], [
            $app->params['adminEmail'],
        ]);
//        $container->setSingleton(SignUpService::class, [], [
//            $app->mailer
//        ]);
    }
}