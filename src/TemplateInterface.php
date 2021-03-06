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

interface TemplateInterface
{
    /**
     * @return array
     */
    public function getAttachments(): array;

    /**
     * @return string
     */
    public function getHtmlBody(): string;

    /**
     * @return string
     */
    public function getPlaintextBody(): string;

    /**
     * @return string
     */
    public function getSubject(): string;
}
