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
use Smarty;
use SmartyException;
use Exception;

abstract class Template implements TemplateInterface
{
    protected $attachments;
    protected $data;
    protected $language;
    protected $smarty;

    protected $htmlFilename = 'html.tpl';
    protected $plaintextFilename = 'plaintext.tpl';

    protected $subject;
    protected $templatesFolder;

    abstract protected function getHtmlData(): array;

    abstract protected function getPlaintextData(): array;

    /**
     * @param Smarty $smarty
     * @param string $language
     * @param array $data
     * @param Attachment[] $attachments
     */
    public function __construct(Smarty $smarty, string $language, array $data, array $attachments)
    {
        $this->smarty = $smarty;
        $this->language = $language;
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
     * @return string
     * @throws InvalidMarkup
     */
    final public function getHtmlBody(): string
    {
        $this->smarty->assign($this->getHtmlData());

        return $this->fetch($this->templatesFolder . $this->htmlFilename);
    }

    /**
     * @return string
     * @throws InvalidMarkup
     */
    final public function getPlaintextBody(): string
    {
        $this->smarty->assign($this->getPlaintextData());

        return $this->fetch($this->templatesFolder . $this->plaintextFilename);
    }

    /**
     * @return string
     */
    final public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @param string $template
     *
     * @return string
     * @throws InvalidMarkup
     */
    private function fetch(string $template): string
    {
        try {
            $result = $this->smarty->fetch($template);
        } catch (SmartyException $exception) {
            throw new InvalidMarkup($exception->getMessage());
        } catch (Exception $exception) {
            throw new InvalidMarkup($exception->getMessage());
        }

        return $result;
    }
}
