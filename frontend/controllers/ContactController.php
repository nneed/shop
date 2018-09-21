<?php
/**
 * Created by PhpStorm.
 * User: НЮКазанков
 * Date: 21.09.2018
 * Time: 16:27
 */

namespace frontend\controllers;

use shop\services\contact\ContactService;
use Yii;
use yii\base\Module;
use yii\web\Controller;
use shop\forms\ContactForm;

class ContactController extends Controller
{
    public function __construct($id, Module $module,ContactService $contactService,array $config = [])
    {
        $this->contactService = $contactService;
        parent::__construct($id, $module, $config);
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $form = new ContactForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try{
                $this->contactService->sendEmail($form);
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
                return $this->goHome();
            }
            catch (\Exception $e){
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }
            return $this->refresh();
        }

        return $this->render('contact', [
            'model' => $form,
        ]);

    }
}