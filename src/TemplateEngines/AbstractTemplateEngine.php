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

abstract class AbstractTemplateEngine implements TemplateEngineInterface
{
    /**
     * @var string
     */
    protected $extension;

    /**
     * @param $entity
     *
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    abstract public function set($entity): void;

    /**
     * @return string
     */
    public function getExtension(): string
    {
        return $this->extension;
    }
}
