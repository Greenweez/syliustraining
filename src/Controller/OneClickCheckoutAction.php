<?php

declare(strict_types=1);

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Sylius\Component\Core\Factory\CartItemFactoryInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Repository\ProductRepositoryInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use Sylius\Component\Order\Factory\OrderItemUnitFactoryInterface;
use Sylius\Component\Order\Modifier\OrderItemQuantityModifierInterface;
use Sylius\Component\Order\Modifier\OrderModifierInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\RouterInterface;

final class OneClickCheckoutAction
{
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;
    /**
     * @var CartContextInterface
     */
    private $cartContext;
    /**
     * @var CartItemFactoryInterface
     */
    private $cartItemFactory;
    /**
     * @var OrderModifierInterface
     */
    private $orderModifier;
    /**
     * @var OrderItemQuantityModifierInterface
     */
    private $orderItemQuantityModifier;
    /**
     * @var ObjectManager
     */
    private $objectManager;
    /**
     * @var EntityManagerInterface
     */
    private $orderItemManager;
    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        CartContextInterface $cartContext,
        CartItemFactoryInterface $cartItemFactory,
        OrderModifierInterface $orderModifier,
        OrderItemQuantityModifierInterface $orderItemQuantityModifier,
        EntityManagerInterface $orderItemManager,
        RouterInterface $router
    ) {
        $this->productRepository = $productRepository;
        $this->cartContext = $cartContext;
        $this->cartItemFactory = $cartItemFactory;
        $this->orderModifier = $orderModifier;
        $this->orderItemQuantityModifier = $orderItemQuantityModifier;
        $this->orderItemManager = $orderItemManager;
        $this->router = $router;
    }

    public function __invoke(Request $request): Response
    {
        /** @var ProductInterface $product */
        $product = $this->productRepository->findOneBy(['code' => $request->request->get('product')]);

        $cartItem = $this->cartItemFactory->createForProduct($product);
        $cart = $this->cartContext->getCart();

        $this->orderItemQuantityModifier->modify($cartItem, 1);

        $this->orderModifier->addToOrder($cart, $cartItem);

        $this->orderItemManager->persist($cartItem);
        $this->orderItemManager->persist($cart);
        $this->orderItemManager->flush();

        return new RedirectResponse($this->router->generate('sylius_shop_cart_summary'));
    }
}
