<?php

declare(strict_types=1);

namespace App\OrderProcessing;

use Sylius\Component\Order\Factory\AdjustmentFactoryInterface;
use Sylius\Component\Order\Model\OrderInterface;
use Sylius\Component\Order\Processor\OrderProcessorInterface;

final class PaymentFeeProcessor implements OrderProcessorInterface
{
    /**
     * @var AdjustmentFactoryInterface
     */
    private $adjustmentFactory;

    public function __construct(AdjustmentFactoryInterface $adjustmentFactory)
    {
        $this->adjustmentFactory = $adjustmentFactory;
    }

    public function process(OrderInterface $order): void
    {
        $adjustment = $this->adjustmentFactory->createWithData('DONATION_ADJUSTMENT', 'Payment fee', 500);

        $order->addAdjustment($adjustment);
    }
}
