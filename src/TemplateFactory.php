<?php
/*
 * This file is part of MailTemplater.
 *
 * (c) Halimon Alexander <vvthanatos@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace HalimonAlexander\MailTemplater;

use Smarty;
use HalimonAlexander\MailTemplater\Exceptions\UnknownTemplate;

class TemplateFactory
{
    protected $language;
    protected $smarty;
    protected $baseNamespace;

    public function __construct(Smarty $smarty, string $language, string $baseNamespace)
    {
        $this->smarty = $smarty;
        $this->language = $language;
        $this->baseNamespace = $baseNamespace;
    }

    public function create(string $templateName, array $data, $attachments = []): TemplateInterface
    {
        $templateClassname = "{$this->baseNamespace}\\$templateName";

        if (!class_exists($templateClassname))
            throw new UnknownTemplate('Template does not exists');

        if (!is_array($attachments)) {
            if ($attachments instanceof Attachment)
                $attachments = [$attachments];
            else
                $attachments = [];
        }

        return new $templateClassname($this->smarty, $this->language, $data, $attachments);
    }
}
