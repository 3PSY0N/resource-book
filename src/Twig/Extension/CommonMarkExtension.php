<?php

namespace App\Twig\Extension;

use App\Twig\Runtime\CommonMarkExtensionRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class CommonMarkExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
            new TwigFilter('markdownify', [CommonMarkExtensionRuntime::class, 'markdownify'], ['is_safe' => ['html']]),
//            new TwigFilter('asciiToHtml', [$this, 'asciiToHtml']),
        ];
    }

    public function getFunctions(): array
    {
        return [
//            new TwigFunction('function_name', [CommonMarkExtensionRuntime::class, 'doSomething']),
        ];
    }


    function asciiToHtml($string): string
    {
        $ansi_foreground_colors = [
            '30' => '#232323', // Noir
            '31' => '#ff000f', // Rouge
            '32' => '#8ce10b', // Vert
            '33' => '#ffb900', // Jaune
            '34' => '#008df8', // Bleu
            '35' => '#6d43a6', // Magenta
            '36' => '#00d8eb', // Cyan
            '37' => '#b7b7b7', // Gris clair
        ];
        $ansi_background_colors = [
            '40' => '#232323', // Noir
            '41' => '#ff000f', // Rouge
            '42' => '#8ce10b', // Vert
            '43' => '#ffb900', // Jaune
            '44' => '#008df8', // Bleu
            '45' => '#6d43a6', // Magenta
            '46' => '#00d8eb', // Cyan
            '47' => '#b7b7b7', // Gris clair
        ];

        $html = '';
        $pieces = preg_split('/(\\033\[[0-9;]*m)/', $string, -1, PREG_SPLIT_DELIM_CAPTURE);
        $foreground_color = null;
        $background_color = null;
        foreach ($pieces as $piece) {
            if (preg_match('/\\033\[(\d+(;\d+)*)m/', $piece, $matches)) {
                $codes = explode(';', $matches[1]);
                foreach ($codes as $code) {
                    if (isset($ansi_foreground_colors[$code])) {
                        $foreground_color = $ansi_foreground_colors[$code];
                    }
                    if (isset($ansi_background_colors[$code])) {
                        $background_color = $ansi_background_colors[$code];
                    }
                }
            } else {
                $style = '';
                if (isset($foreground_color)) {
                    $style .= 'color: '.$foreground_color.';';
                }
                if (isset($background_color)) {
                    $style .= 'background-color: '.$background_color.';';
                }
                $html .= $style ? '<span style="'.$style.'">'.$piece.'</span>' : $piece;
                $foreground_color = null;
                $background_color = null;
            }
        }
        return $html;
    }
}
