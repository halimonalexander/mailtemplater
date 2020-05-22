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

namespace HalimonAlexander\MailTemplater\TemplateEngines;

use HalimonAlexander\MailTemplater\Exceptions\InvalidMarkup;

class Smarty extends AbstractTemplateEngine
{
    /**
     * @var \Smarty
     */
    private $smarty;

    /**
     * @var string
     */
    protected $extension = 'tpl';

    /**
     * @param \Smarty $smarty
     *
     * @return void
     *
     * @throws \InvalidArgumentException
     */
    public function set($smarty): void
    {
        if (!($smarty instanceof \Smarty)) {
            throw new \InvalidArgumentException('Smarty expected');
        }

        $this->smarty = $smarty;
    }

    /**
     * @param string $templateName
     * @param array $data
     *
     * @return string
     *
     * @throws InvalidMarkup
     */
    public function fetch(string $templateName, array $data = []): string
    {
        $this->smarty->assign($data);

        try {
            $rendered = $this->smarty->fetch($templateName);
        } catch (\SmartyException $exception) {
            throw new InvalidMarkup($exception->getMessage(), $exception->getCode());
        }

        return $rendered;
    }
}
