<?php

namespace tests\MailTemplates;

use HalimonAlexander\MailTemplater\Template;

class TestMail extends Template
{
    protected static $templateEngineType = self::TEMPLATE_ENGINE_TWIG;
    protected $filename = 'testMail';
    protected $subject = 'Test mail example';

    protected function getHtmlData(): array
    {
        $data = $this->getData();
        return [
            'username' => $data['name'] . ' ' . $data['surname'],
            'class' => $data['gender'] == 'male' ? 'male' : 'famale',
        ];
    }

    protected function getPlaintextData(): array
    {
        $data = $this->getData();
        return [
            'username' => $data['name'] . ' ' . $data['surname'],
        ];
    }
}
