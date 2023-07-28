<?php

namespace App\Service\CommonMark\AdmonitionExtension;

use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use League\CommonMark\Util\HtmlElement;

class AdmonitionRenderer implements NodeRendererInterface
{
    private array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @param AdmonitionBlock $node
     */
    public function render(Node $node, ChildNodeRendererInterface $childRenderer): \Stringable
    {
        $title = '';

        if ($node->getTitle()->hasChildren()) {
            $node->getTitle()->data->set('attributes.class', $this->config['title_tag']);

            $title = $childRenderer->renderNodes([$node->getTitle()]);
        }

        $children = $childRenderer->renderNodes($node->children());

        return new HtmlElement(
            $this->config['html_tag'],
            ['class' => $this->config['class_prefix'] . ' ' . $node->getType()],
            $title . $children
        );
    }
}