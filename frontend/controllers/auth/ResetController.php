<?php
/**
 * Created by PhpStorm.
 * User: НЮКазанков
 * Date: 21.09.2018
 * Time: 16:27
 */

namespace frontend\controllers\auth;

use shop\useCases\auth\PasswordResetService;
use Yii;
use yii\base\Module;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use shop\forms\auth\PasswordResetRequestForm;
use shop\forms\auth\ResetPasswordForm;

class ResetController extends Controller
{
    private $passwordResetService;

    public function __construct(
        $id,
        Module $module,
        PasswordResetService $passwordResetService,
        array $config = []
    )
    {
        $this->passwordResetService = $passwordResetService;
        parent::__construct($id, $module, $config);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequest()
    {
        $form = new PasswordResetRequestForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {

            try{
                $this->passwordResetService->request($form);
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                return $this->goHome();
            }catch (\Exception $e){
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('request', [
            'model' => $form,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionConfirm($token)
    {
        try {
            $this->passwordResetService->validateToken($token);

        } catch (\DomainException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        $form = new ResetPasswordForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try{
                $this->passwordResetService->reset($token,$form);
                Yii::$app->session->setFlash('success', 'New password saved.');
                return $this->goHome();
            }catch (\DomainException $e){
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('confirm', [
            'model' => $form,
        ]);
    }
}