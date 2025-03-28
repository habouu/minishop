<?php

namespace App\Twig\Extension;

use App\Classes\Cart;
use App\Repository\CategoryRepository;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension implements GlobalsInterface
{
    private $categoryRepository;
    private $cart;
    public function __construct(CategoryRepository $categoryRepository, Cart $cart)
    {
        $this->categoryRepository = $categoryRepository;
        $this->cart = $cart;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('price', [$this, 'formatPrice'])
        ];
    }

    public function formatPrice($number)
    {
        return number_format($number, '2', ','). ' €';
    }

    public function getGlobals(): array
    {
        return [
            'categories' => $this->categoryRepository->findAll(),
            'fullQty' => $this->cart->getFullQty()
        ];
    }
}