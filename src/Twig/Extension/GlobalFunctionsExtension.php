<?php

namespace App\Twig\Extension;

use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class GlobalFunctionsExtension extends AbstractExtension
{
    private RequestStack $request;

    public function __construct(RequestStack $request)
    {
        $this->request = $request;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('isPageActive', [$this, 'isPageActive']),
        ];
    }

    public function isPageActive(array $routes, ?string $classes = 'active'): ?string
    {
        $currentRoute = $this->request->getCurrentRequest()->attributes->get('_route');

        foreach ($routes as $route) {
            if ($route === $currentRoute) {
                return 'class="'.$classes.'" aria-current="page"';
            }
        }

        return null;
    }
}