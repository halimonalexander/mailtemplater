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

use HalimonAlexander\MailTemplater\Exceptions\TemplateEngineNotSet;
use HalimonAlexander\MailTemplater\Exceptions\UnknownTemplateEngine;
use HalimonAlexander\MailTemplater\TemplateEngines\Smarty as SmartyTemplateEngine;
use HalimonAlexander\MailTemplater\TemplateEngines\Twig;
use HalimonAlexander\MailTemplater\TemplateEngines\Twig as TwigTemplateEngine;
use HalimonAlexander\MailTemplater\Exceptions\UnknownTemplate;
use Smarty;
use Twig\Environment;

class TemplateFactory
{
    /**
     * @var Smarty
     */
    private $smarty;

    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var string
     */
    private $baseNamespace;

    /**
     * @var
     */
    private $templatesFolder;

    /**
     * @param string $baseNamespace
     * @param string $templatesFolder
     */
    public function __construct(string $baseNamespace, string $templatesFolder)
    {
        $this->baseNamespace = rtrim($baseNamespace, "\\") . "\\";
        $this->templatesFolder = rtrim($templatesFolder, "\\/") . DIRECTORY_SEPARATOR;
    }

    /**
     * @param Smarty $smarty
     * @return TemplateFactory
     */
    final public function setSmarty(Smarty $smarty): self
    {
        $this->smarty = $smarty;

        return $this;
    }

    /**
     * @param Environment $twig
     * @return TemplateFactory
     */
    final public function setTwig(Environment $twig): self
    {
        $this->twig = $twig;

        return $this;
    }

    /**
     * @param string $templateName
     * @param array $data
     * @param Attachment[] $attachments
     *
     * @return TemplateInterface
     *
     * @throws TemplateEngineNotSet
     * @throws UnknownTemplate
     * @throws UnknownTemplateEngine
     */
    final public function create(
        string $templateName,
        array $data,
        array $attachments = []
    ): TemplateInterface
    {
        $templateClassname = $this->baseNamespace . $templateName;

        if (!class_exists($templateClassname)) {
            throw new UnknownTemplate(sprintf('Template %s does not exists', $templateName));
        }

        $interfaces = class_implements($templateClassname);
        if (!$interfaces || !in_array(TemplateInterface::class, $interfaces)) {
            throw new UnknownTemplate(sprintf('Class %s exists, but is not instance of TemplateInterface', $templateName));
        }

        $templateEngineType = $this->getTemplateEngineType($templateClassname);

        /** @var Template $template */
        $template = new $templateClassname(
            $this->templatesFolder,
            $data,
            $this->getAttachments($attachments)
        );

        switch ($templateEngineType) {
            case Template::TEMPLATE_ENGINE_SMARTY:
                $templateEngine = new SmartyTemplateEngine();
                $templateEngine->set($this->smarty);
                break;
            case Template::TEMPLATE_ENGINE_TWIG:
                $templateEngine = new TwigTemplateEngine();
                $templateEngine->set($this->twig);
                break;
        }

        $template->setTemplateEngine($templateEngine);

        return $template;
    }

    /**
     * @param string|Template $templateClassname
     *
     * @throws TemplateEngineNotSet
     * @throws UnknownTemplateEngine
     */
    private function getTemplateEngineType(string $templateClassname): string
    {
        $templateEngineType = $templateClassname::getTemplateEngineType();

        switch ($templateEngineType) {
            case Template::TEMPLATE_ENGINE_SMARTY:
                if ($this->smarty === null) {
                    throw new TemplateEngineNotSet('Template requires smarty, but was not configured');
                }
                break;
            case Template::TEMPLATE_ENGINE_TWIG:
                if ($this->twig === null) {
                    throw new TemplateEngineNotSet('Template requires twig, but was not configured');
                }
                break;
            default:
                throw new UnknownTemplateEngine("{$templateEngineType} is unknown");
                break;
        }

        return $templateEngineType;
    }

    /**
     * @param $attachments
     *
     * @return Attachment[]
     */
    private function getAttachments($attachments): array
    {
        if (is_array($attachments)) {
            return array_filter($attachments, function ($item) {
                return $item instanceof Attachment;
            });
        }

        if ($attachments instanceof Attachment) {
            return [$attachments];
        }

        return [];
    }
}
