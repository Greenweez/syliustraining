<?php

declare(strict_types=1);

namespace App\Form\Extension;

use Sylius\Bundle\CoreBundle\Form\Type\Checkout\ShipmentType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

final class ShipmentTypeExtension extends AbstractTypeExtension
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('pickupTime', ChoiceType::class, [
            'choices' => [
                'morning' => 'morning',
                'afternoon' => 'afternoon',
                'evening' => 'evening',
            ]
        ]);
    }

    public function getExtendedTypes(): iterable
    {
        yield ShipmentType::class;
    }
}
