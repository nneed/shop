<?php
namespace frontend\tests\unit\forms;

use Yii;
use frontend\forms\ContactForm;

class ContactFormTest extends \Codeception\Test\Unit
{
    public function testSuccess()
    {
        $model = new ContactForm();

        $model->attributes = [
            'name' => 'Tester',
            'email' => 'tester@example.com',
            'subject' => 'very important letter subject',
            'body' => 'body of current message',
        ];

        expect_that($model->validate(['name', 'email', 'subject', 'body']));
    }
}
