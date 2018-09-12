<?php

namespace frontend\tests\unit\forms;

use common\fixtures\UserFixture;
use frontend\forms\ResetPasswordForm;
use frontend\services\auth\PasswordResetService;

class ResetPasswordFormTest extends \Codeception\Test\Unit
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
            ],
        ]);
    }

    public function testResetWrongToken()
    {
        $this->tester->expectException('\DomainException', function() {
            $service = new PasswordResetService();
            $service->validateToken('');
        });

//        $this->tester->expectException('yii\base\InvalidParamException', function() {
//            new ResetPasswordForm('notexistingtoken_1391882543');
//        });
    }

    public function testResetCorrectToken()
    {
        $user = $this->tester->grabFixture('user', 0);
        $form = new ResetPasswordForm(['password' => 'some_password']);
        expect_that($form->validate());
    }

}
