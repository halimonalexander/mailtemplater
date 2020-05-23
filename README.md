# MailTemplater
Mail templates library

```php
$templateFactory = new TemplateFactory(
    'Templates',
    '\App\Templates'
);

$template =
    $templateFactory
        ->create(
            'template_name',
            $data,
            $attachments
        );
                
// Usage:
echo $template->getSubject();
echo $template->getHtmlBody();
echo $template->getPlaintextBody();
print_r($template->getAttachments());
```