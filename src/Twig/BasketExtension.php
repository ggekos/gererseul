<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use App\Manager\BasketManager;

class BasketExtension extends AbstractExtension
{
    private $basketManager;

    public function __construct(BasketManager $basketManager)
    {
        $this->basketManager = $basketManager;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('basketTotal', [$this, 'getBasketTotal']),
        ];
    }

    public function getBasketTotal()
    {
        return $this->basketManager->getTotalQuantity();
    }
}
