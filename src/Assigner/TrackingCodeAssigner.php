<?php

declare(strict_types=1);

namespace App\Assigner;

use Sylius\Component\Core\Model\ShipmentInterface;

final class TrackingCodeAssigner
{
    public function __construct()
    {
        $this->faker = \Faker\Factory::create();
    }

    public function assignTrackingCode(ShipmentInterface $shipment): void
    {
        $shipment->setTracking((string) $this->faker->randomNumber(5));
    }
}
