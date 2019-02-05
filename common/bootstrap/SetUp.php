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
use shop\dispatchers\SimpleEventDispatcher;
use shop\readModels\Shop\CategoryReadRepository;
use shop\useCases\auth\PasswordResetService;
use shop\useCases\auth\SignUpService;
use shop\useCases\ContactService;
use yii\base\BootstrapInterface;
use yii\mail\MailerInterface;
use yii\caching\Cache;
use yii;
use shop\services\yandex\ShopInfo;
use shop\services\yandex\YandexMarket;
use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use yii\rbac\ManagerInterface;

class SetUp implements BootstrapInterface
{
    public function bootstrap($app) : void
    {
        $container = \Yii::$container;
//Так тоже можно
//        $container->setSingleton(PasswordResetService::class, function () use ($app){
//            return new PasswordResetService([Yii::$app->params['adminEmail'] => Yii::$app->name . ' robot']);
//        });

        $container->setSingleton(ManagerInterface::class, function () use ($app) {
            return $app->authManager;
        });

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
                new HybridStorage($app->get('user'), 'cart', 3600 * 24, $app->db),
                new DynamicCost(new SimpleCost())
            );
        });

        $container->setSingleton(YandexMarket::class, [], [
            new ShopInfo($app->name, $app->name, $app->params['frontendHostInfo']),
        ]);

        $container->setSingleton(Newsletter::class, function () use ($app) {
            return new MailChimp(
                new \DrewM\MailChimp\MailChimp($app->params['mailChimpKey']),
                $app->params['mailChimpListId']
            );
        });

        $container->setSingleton(SmsSender::class, function () use ($app) {
            return new LoggedSender(
                new SmsRu($app->params['smsRuKey']),
                \Yii::getLogger()
            );
        });

        $container->setSingleton(EventDispatcher::class, DeferredEventDispatcher::class);

        $container->setSingleton(SimpleEventDispatcher::class, function (Container $container) {
            return new SimpleEventDispatcher($container, [
                UserSignUpRequested::class => [UserSignupRequestedListener::class],
                UserSignUpConfirmed::class => [UserSignupConfirmedListener::class],
                ProductAppearedInStock::class => [ProductAppearedInStockListener::class],
                EntityPersisted::class => [
                    ProductSearchPersistListener::class,
                    CategoryPersistenceListener::class,
                ],
                EntityRemoved::class => [
                    ProductSearchRemoveListener::class,
                    CategoryPersistenceListener::class,
                ],
            ]);
        });


//        $container->set(CategoryUrlRule::class,[],[
//            yii\di\Instance::of(CategoryReadRepository::class),
//            yii\di\Instance::of(yii\caching\Cache::class),
//        ]);
    }
}