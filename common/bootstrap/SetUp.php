<?php
/**
 * Created by PhpStorm.
 * User: НЮКазанков
 * Date: 14.08.2018
 * Time: 17:06
 */

namespace common\bootstrap;

use frontend\urls\CategoryUrlRule;
use shop\cart\Cart;
use shop\cart\cost\calculator\DynamicCost;
use shop\cart\cost\calculator\SimpleCost;
use shop\cart\storage\HybridStorage;
use shop\cart\storage\SessionStorage;
use shop\readModels\Shop\CategoryReadRepository;
use shop\services\auth\PasswordResetService;
use shop\services\auth\SignUpService;
use shop\services\contact\ContactService;
use yii\base\BootstrapInterface;
use yii\mail\MailerInterface;
use yii\caching\Cache;
use yii;
use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;

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

        $container->setSingleton(Cache::class, function () use ($app) {
            return $app->cache;
        });

        $container->setSingleton(Client::class, function () {
            return ClientBuilder::create()->build();
        });

        $container->setSingleton(Cart::class, function () use ($app) {
            return new Cart(
                new SessionStorage('cart', $app->session),
                new DynamicCost(new SimpleCost())
            );
        });


//        $container->set(CategoryUrlRule::class,[],[
//            yii\di\Instance::of(CategoryReadRepository::class),
//            yii\di\Instance::of(yii\caching\Cache::class),
//        ]);
    }
}