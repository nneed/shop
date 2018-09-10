<?php
/**
 * Created by PhpStorm.
 * User: НЮКазанков
 * Date: 06.09.2018
 * Time: 11:40
 */

namespace frontend\services\auth;

use common\entities\User;
use frontend\models\SignupForm;

class SignUpService
{
    public function sighUp(SignupForm $form) : User
    {
        if (User::find()->andWhere(['username' => $form->username])->one()){
            throw new \DomainException('Username already exists.');
        }
        if (User::find()->andWhere(['email' => $form->email])->one()){
            throw new \DomainException('Email already exists.');
        }

        $user = User::signUp($form->username, $form->email, $form->password);
        if (!$user->save()){
            throw new \RuntimeException('Saving error.');
        }
        return $user->save() ? $user : null;
    }
}