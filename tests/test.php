<?php

use HalimonAlexander\MailTemplater\TemplateFactory;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

require_once __DIR__ . '/../vendor/autoload.php';

$templateFactory = new TemplateFactory(
    '\tests\MailTemplates',
    ''
);

$loader = new FilesystemLoader(__DIR__ . '\Templates\Mails');
$twig = new  Environment($loader);

$templateFactory
    ->setTwig($twig);

$template = $templateFactory
    ->create(
        'TestMail',
        [
            'name' => 'John',
            'surname' => 'Smith',
            'gender' => 'male',
        ]
    );

echo $template->getSubject() . PHP_EOL . PHP_EOL;
echo $template->getHtmlBody() . PHP_EOL . PHP_EOL;
echo $template->getPlaintextBody() . PHP_EOL . PHP_EOL;