<?php

namespace App\Classes;

use Symfony\Component\HttpFoundation\RequestStack;

class Cart
{
    public function __construct(private RequestStack $requestStack)
    {
        
    }

    /*
     * add()
     * fonction d'ajout d'un produit dans un panier
     */
    public function add($product)
    {
        $cart = $this->getCart();

        if (isset($cart[$product->getId()])) {
            $cart[$product->getId()] = [
                'item' => $product,
                'qty' => $cart[$product->getId()]['qty'] + 1
            ];
        } else {
            $cart[$product->getId()] = [
                'item' => $product,
                'qty' => 1
            ];
        }
        

        $this->requestStack->getSession()->set('cart', $cart);
    }

    /*
     * decrease()
     * fonction de suppression d'une qté dans un panier
     */
    public function decrease($id)
    {
        $cart = $this->getCart();

        if ($cart[$id]['qty'] > 1) {
            $cart[$id]['qty'] = $cart[$id]['qty'] - 1;
        } else {
            unset($cart[$id]);
        }

        $this->requestStack->getSession()->set('cart', $cart);
    }

    /*
     * getFullQty()
     * fonction de retour du nombre total de produit dans le panier
     */
    public function getFullQty()
    {
        $cart = $this->getCart();
        $qty = 0;
        if (!isset($cart)) {
            return $qty;
        }
        foreach ($cart as $product) {
            $qty = $qty + $product['qty'];
        }
        return $qty;
    }

    /*
     * getTotalWt()
     * fonction de retour du prix total des produit du panier
     */
    public function getTotalWt()
    {
        $cart = $this->getCart();
        $price = 0;
        if (!isset($cart)) {
            return $price;
        }
        foreach ($cart as $product) {
            $price = $price + ($product['qty'] * $product['item']->getPriceWt());
        }
        return $price;
    }

    /*
     * getCart()
     * fonction retournant le panier
     */
    public function getCart()
    {
        return $this->requestStack->getSession()->get('cart');
    }
}