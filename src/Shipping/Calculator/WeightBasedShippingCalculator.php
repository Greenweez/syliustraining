<?php

declare(strict_types=1);

namespace App\Shipping\Calculator;

use Sylius\Component\Shipping\Calculator\CalculatorInterface;
use Sylius\Component\Shipping\Model\ShipmentInterface;

final class WeightBasedShippingCalculator implements CalculatorInterface
{
    public function calculate(ShipmentInterface $subject, array $configuration): int
    {
        $shippingWeight = $subject->getShippingWeight();

//        return (int) (1000 * ceil($shippingWeight / 1000));
        return (int) ($configuration['price'] * ceil($shippingWeight / $configuration['per_grams']));
    }

    public function getType(): string
    {
        return 'weight_based_calculator';
    }
}
