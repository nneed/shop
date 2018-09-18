<?php
/**
 * Created by PhpStorm.
 * User: НЮКазанков
 * Date: 06.09.2018
 * Time: 11:40
 */

namespace frontend\services\auth;

use common\entities\User;
use frontend\forms\SignupForm;
use yii\mail\MailerInterface;

class SignUpService
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function signup(SignupForm $form)
    {
        if (User::find()->andWhere(['username' => $form->username])->one()){
            throw new \DomainException('Username already exists.');
        }
        if (User::find()->andWhere(['email' => $form->email])->one()){
            throw new \DomainException('Email already exists.');
        }

        $user = User::requestSignup($form->username, $form->email, $form->password);
        if (!$user->save()){
            throw new \RuntimeException('Saving error.');
        }

        $setn = $this
            ->mailer
            ->compose(
                ['html' => 'emailConfirmToken-html', 'text' => 'emailConfirmToken-html-text'],
                ['user' => $user]
            )
            ->setTo($form->email)
            ->setSubject('Sugnup confirm for ' . \Yii::$app->name)
            ->send();

        if (!$setn){
            throw new \RuntimeException('Email sending error.');
        }
    }

    public function confirm($token): void
    {
        if (empty($token)){
            throw new \DomainException('Empty confirm token.');
        }

        $user = User::findOne(['email_confirm_token' => $token]);

        if (!$user) {
            throw new \DomainException('User is not found.');
        }

        $user->confirmSignup();

        if (!$user->save()){
            throw new \RuntimeException('Saving error.');
        }
    }
}