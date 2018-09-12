<?php

namespace frontend\tests\unit\forms;

use frontend\services\auth\PasswordResetService;
use Yii;
use frontend\forms\PasswordResetRequestForm;
use common\fixtures\UserFixture as UserFixture;
use common\entities\User;

class PasswordResetRequestFormTest extends \Codeception\Test\Unit
{
    /**
     * @var \frontend\tests\UnitTester
     */
    protected $tester;


    public function _before()
    {
        $this->tester->haveFixtures([
            'user' => [
                'class' => UserFixture::className(),
                'dataFile' => codecept_data_dir() . 'user.php'
            ]
        ]);
    }

    public function testSendMessageWithWrongEmailAddress()
    {
        $form = new PasswordResetRequestForm();
        $form->email = 'not-existing-email@example.com';
        expect_not($form->validate());
    }

    public function testNotSendEmailsToInactiveUser()
    {
        $user = $this->tester->grabFixture('user', 1);
        $form = new PasswordResetRequestForm();
        $form->email = $user['email'];
        expect_not($form->validate());
    }

    public function testSendEmailSuccessfully()
    {
        $userFixture = $this->tester->grabFixture('user', 0);
        $model = new PasswordResetRequestForm();
        $model->email = $userFixture['email'];
        $user = User::findOne(['password_reset_token' => $userFixture['password_reset_token']]);
        expect_that($model->validate());
    }
}
