<?php

declare(strict_types=1);

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use Sylius\Component\Order\Factory\AdjustmentFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class DonateAction
{
    /**
     * @var AdjustmentFactoryInterface
     */
    private $adjustmentFactory;
    /**
     * @var CartContextInterface
     */
    private $cartContext;
    /**
     * @var EntityManagerInterface
     */
    private $adjustmentManager;

    public function __construct(
        AdjustmentFactoryInterface $adjustmentFactory,
        CartContextInterface $cartContext,
        EntityManagerInterface $adjustmentManager
    ) {
        $this->adjustmentFactory = $adjustmentFactory;
        $this->cartContext = $cartContext;
        $this->adjustmentManager = $adjustmentManager;
    }

    public function __invoke(Request $request): Response
    {
        $amount = $request->request->getInt('amount');

        $adjustment = $this->adjustmentFactory->createWithData('DONATION_ADJUSTMENT', 'Customer donation', $amount, true);

        $cart = $this->cartContext->getCart();

        $cart->addAdjustment($adjustment);

        $this->adjustmentManager->persist($adjustment);
        $this->adjustmentManager->flush();

        return new RedirectResponse($request->headers->get('referer'));
    }
}
