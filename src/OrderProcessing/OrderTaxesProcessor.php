<?php

declare(strict_types=1);

namespace App\OrderProcessing;

use App\Entity\Customer\Customer;
use Sylius\Component\Order\Model\OrderInterface;
use Sylius\Component\Order\Processor\OrderProcessorInterface;

final class OrderTaxesProcessor implements OrderProcessorInterface
{
    /**
     * @var OrderProcessorInterface
     */
    private $delegatedOrderProcessor;

    public function __construct(OrderProcessorInterface $delegatedOrderProcessor)
    {
        $this->delegatedOrderProcessor = $delegatedOrderProcessor;
    }

    public function process(OrderInterface $order): void
    {
        /** @var Customer $customer */
        $customer = $order->getCustomer();

        if ($customer !== null && $customer->getTaxNumber() !== null) {
            return;
        }

        $this->delegatedOrderProcessor->process($order);
    }
}
