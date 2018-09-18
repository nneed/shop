<?php
namespace frontend\tests\unit\forms;

use common\fixtures\UserFixture;
use frontend\forms\SignupForm;
use frontend\services\auth\SignUpService;

class SignupTest extends \Codeception\Test\Unit
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

    public function testCorrectSignup()
    {
        $form = new SignupForm([
            'username' => 'some_username1',
            'email' => 'some_email@example.com1',
            'password' => 'some_password',
        ]);
//
//        if ($form->validate()) {
//            $user = (new SignUpService())->signup($form);
//            $this->assertTrue(\Yii::$app->getUser()->login($user));
//        }
        expect_that($form->validate());

    }


}
