<?php

namespace App\Service\CommonMark\AdmonitionExtension;

use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Extension\ExtensionInterface;

class AdmonitionExtension implements ExtensionInterface
{
    private array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function register(EnvironmentBuilderInterface $environment): void
    {
        $environment->addBlockStartParser(AdmonitionParser::blockStartParser($this->config));
        $environment->addRenderer(AdmonitionBlock::class, new AdmonitionRenderer($this->config));
    }
}