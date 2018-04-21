<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Manager\BasketManager;
use Symfony\Component\HttpFoundation\Request;

class BasketController extends Controller
{
    /**
     * @Route("/basket", name="basket")
     */
    public function index(BasketManager $basketManager)
    {
        return $this->render('basket/basket.html.twig', [
            'basket' => $basketManager->getBasket(),
            'total' => $basketManager->getTotal(),
        ]);
    }

    /**
     * @Route("/basket/add/{id}", name="basketAdd")
     */
    public function basketAdd(BasketManager $basketManager, Request $request, $id)
    {
        $quantity = $request->request->get('quantity') ?: 1;

        $basketManager->addToBasket($id, $quantity);

        return $this->redirectToRoute('basket');
    }

    /**
     * @Route("/basket/remove/{id}", name="basketRemove")
     */
    public function basketRemove(BasketManager $basketManager, $id)
    {
        $basketManager->removeFromBasket($id);

        return $this->redirectToRoute('basket');
    }

    /**
     * @Route("/basket/clear", name="basketClear")
     */
    public function basketClear(BasketManager $basketManager)
    {
        $basketManager->clearBasket();

        return $this->redirectToRoute('basket');
    }
}
