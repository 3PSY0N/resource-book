<?php

namespace App\Service\CommonMark;

use League\CommonMark\Util\HtmlElement;
use Ueberdosis\CommonMark\Embed;

class YouTube extends \Ueberdosis\CommonMark\Services\YouTube
{
    public function render(Embed $node): HtmlElement
    {
        return new HtmlElement(
            'iframe',
            [
                'class' => 'video',
//                'width' => '100%',
//                'height' => '100%',
                'src' => 'https://www.youtube-nocookie.com/embed/' . $this->getId($node->getUrl()),
                'title' => 'YouTube video player',
                'frameborder' => '0',
                'allow' => 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture',
                'allowfullscreen' => '',
            ],
        );
    }
}