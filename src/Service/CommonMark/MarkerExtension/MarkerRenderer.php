<?php

namespace App\Service\CommonMark\MarkerExtension;

use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use League\CommonMark\Util\HtmlElement;
use League\CommonMark\Xml\XmlNodeRendererInterface;
use Stringable;

class MarkerRenderer implements NodeRendererInterface, XmlNodeRendererInterface
{
    public function render(Node $node, ChildNodeRendererInterface $childRenderer): Stringable
    {
        Marker::assertInstanceOf($node);

        $attrs = $node->data->get('attributes');
        $contents = $childRenderer->renderNodes($node->children());

        return new HtmlElement('mark', $attrs, $contents);
    }

    public function getXmlTagName(Node $node): string
    {
        return 'marker';
    }

    public function getXmlAttributes(Node $node): array
    {
        return [];
    }
}