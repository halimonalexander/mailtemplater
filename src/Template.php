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

use HalimonAlexander\MailTemplater\Exceptions\InvalidMarkup;
use HalimonAlexander\MailTemplater\Exceptions\UnknownTemplateEngine;
use HalimonAlexander\MailTemplater\TemplateEngines\TemplateEngineInterface;

abstract class Template implements TemplateInterface
{
    public const TEMPLATE_ENGINE_SMARTY = 'smarty';
    public const TEMPLATE_ENGINE_TWIG   = 'twig';

    /**
     * @var string|null
     */
    protected static $templateEngineType = null;

    /**
     * @var Attachment[]
     */
    private $attachments;

    /**
     * @var array
     */
    private $data;

    /**
     * @var string
     */
    protected $filename;

    /**
     * @var string
     */
    protected $subject;

    /**
     * @var TemplateEngineInterface
     */
    private $templateEngine;

    /**
     * @var string
     */
    private $templateFolder;

    abstract protected function getHtmlData(): array;

    abstract protected function getPlaintextData(): array;

    /**
     * @return string
     *
     * @throws UnknownTemplateEngine
     */
    final public static function getTemplateEngineType(): string
    {
        if (
            static::$templateEngineType === null ||
            !in_array(
                static::$templateEngineType,
                [
                    self::TEMPLATE_ENGINE_SMARTY,
                    self::TEMPLATE_ENGINE_TWIG,
                ]
            )
        ) {
            throw new UnknownTemplateEngine('');
        }

        return static::$templateEngineType;
    }

    /**
     * @param string $templateFolder
     * @param array $data
     * @param Attachment[] $attachments
     */
    public function __construct(string $templateFolder, array $data, array $attachments)
    {
        $this->templateFolder = $templateFolder;
        $this->data = $data;
        $this->attachments = $attachments;
    }

    /**
     * @return Attachment[]
     */
    final public function getAttachments(): array
    {
        return $this->attachments;
    }

    /**
     * @return array
     */
    final public function getData(): array
    {
        return $this->data;
    }

    /**
     * @return string
     * @throws InvalidMarkup
     */
    final public function getHtmlBody(): string
    {
        return $this->templateEngine->fetch(
            sprintf(
                '%s%s.%s.%s',
                $this->templateFolder,
                $this->filename,
                'html',
                $this->templateEngine->getExtension()
            ),
            $this->getHtmlData()
        );
    }

    /**
     * @return string
     * @throws InvalidMarkup
     */
    final public function getPlaintextBody(): string
    {
        return $this->templateEngine->fetch(
            sprintf(
                '%s%s.%s.%s',
                $this->templateFolder,
                $this->filename,
                'txt',
                $this->templateEngine->getExtension()
            ),
            $this->getPlaintextData()
        );
    }

    /**
     * @return string
     */
    final public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @param TemplateEngineInterface $templateEngine
     */
    final public function setTemplateEngine(TemplateEngineInterface $templateEngine)
    {
        $this->templateEngine = $templateEngine;
    }
}
