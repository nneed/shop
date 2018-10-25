<?php
/**
 * Created by PhpStorm.
 * User: НЮКазанков
 * Date: 21.09.2018
 * Time: 16:27
 */

namespace frontend\controllers\auth;

use Yii;
use yii\base\Module;
use yii\filters\AccessControl;
use yii\web\Controller;
use shop\forms\auth\SignupForm;
use shop\services\auth\SignUpService;

class SignupController extends Controller
{
    private $signupService;

    public function __construct($id, Module $module, SignUpService $signupService, array $config = [])
    {
        $this->signupService = $signupService;
        parent::__construct($id, $module, $config);
    }

    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['request', 'confirm'],
                'rules' => [
                    [
                        'actions' => ['request', 'confirm'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionRequest()
    {
        $form = new SignupForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try{
                $this->signupService->signup($form);
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                return $this->goHome();
            }catch (\DomainException $e){
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('request', [
            'model' => $form,
        ]);
    }

    /**
     * @param $token
     * @return \yii\web\Response
     */
    public function actionConfirm($token)
    {
        try{
            $this->signupService->confirm($token);
            Yii::$app->session->setFlash('success', 'Your email is confirmed.');
            return $this->redirect(['auth/auth/login']);
        }catch (\DomainException $e){
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
            return $this->goHome();
        }
    }
}