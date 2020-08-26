<?php

declare(strict_types=1);

namespace App\Entity\Shipping;

use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Core\Model\Shipment as BaseShipment;

/**
 * @ORM\Entity
 * @ORM\Table(name="sylius_shipment")
 */
class Shipment extends BaseShipment
{
    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    private $pickupTime;

    public function getPickupTime(): ?string
    {
        return $this->pickupTime;
    }

    public function setPickupTime(?string $pickupTime): void
    {
        $this->pickupTime = $pickupTime;
    }
}
