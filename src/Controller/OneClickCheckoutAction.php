<?php

declare(strict_types=1);

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use SM\Factory\FactoryInterface;
use Sylius\Component\Core\Factory\CartItemFactoryInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\OrderCheckoutTransitions;
use Sylius\Component\Core\Repository\ProductRepositoryInterface;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use Sylius\Component\Order\Factory\OrderItemUnitFactoryInterface;
use Sylius\Component\Order\Modifier\OrderItemQuantityModifierInterface;
use Sylius\Component\Order\Modifier\OrderModifierInterface;
use Sylius\Component\Order\OrderTransitions;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\VarDumper\VarDumper;

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
    /**
     * @var CustomerContextInterface
     */
    private $customerContext;
    /**
     * @var FactoryInterface
     */
    private $stateMachineFactory;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        CartContextInterface $cartContext,
        CartItemFactoryInterface $cartItemFactory,
        OrderModifierInterface $orderModifier,
        OrderItemQuantityModifierInterface $orderItemQuantityModifier,
        EntityManagerInterface $orderItemManager,
        RouterInterface $router,
        CustomerContextInterface $customerContext,
        FactoryInterface $stateMachineFactory
    ) {
        $this->productRepository = $productRepository;
        $this->cartContext = $cartContext;
        $this->cartItemFactory = $cartItemFactory;
        $this->orderModifier = $orderModifier;
        $this->orderItemQuantityModifier = $orderItemQuantityModifier;
        $this->orderItemManager = $orderItemManager;
        $this->router = $router;
        $this->customerContext = $customerContext;
        $this->stateMachineFactory = $stateMachineFactory;
    }

    public function __invoke(Request $request): Response
    {
        /** @var ProductInterface $product */
        $product = $this->productRepository->findOneBy(['code' => $request->request->get('product')]);

        $cartItem = $this->cartItemFactory->createForProduct($product);
        /** @var OrderInterface $cart */
        $cart = $this->cartContext->getCart();

        $this->orderItemQuantityModifier->modify($cartItem, 1);

        $this->orderModifier->addToOrder($cart, $cartItem);

        $this->orderItemManager->persist($cartItem);
        $this->orderItemManager->persist($cart);
        $this->orderItemManager->flush();

        /** @var CustomerInterface $customer */
        $customer = $this->customerContext->getCustomer();

        if ($customer !== null) {
            $address = $customer->getDefaultAddress();

            $cart->setCustomer($customer);
            $cart->setShippingAddress($address);
            $cart->setBillingAddress($address);

            $stateMachine = $this->stateMachineFactory->get($cart, OrderCheckoutTransitions::GRAPH);

            $stateMachine->apply(OrderCheckoutTransitions::TRANSITION_ADDRESS);
            $stateMachine->apply(OrderCheckoutTransitions::TRANSITION_SELECT_SHIPPING);
            $stateMachine->apply(OrderCheckoutTransitions::TRANSITION_SELECT_PAYMENT);

            $this->orderItemManager->flush();

            return new RedirectResponse($this->router->generate('sylius_shop_checkout_complete'));
        }

        return new RedirectResponse($this->router->generate('sylius_shop_cart_summary'));
    }
}
