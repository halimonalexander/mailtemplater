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

interface TemplateEngineInterface
{
    public function getExtension(): string;

    /**
     * @param string $templateName
     * @param array $data
     *
     * @return string
     *
     * @throws InvalidMarkup
     */
    public function fetch(string $templateName, array $data = []): string;

    /**
     * @param $engine
     *
     * @return void
     *
     * @throws \InvalidArgumentException
     */
    public function set($engine): void;
}