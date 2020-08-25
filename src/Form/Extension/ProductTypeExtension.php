<?php

declare(strict_types=1);

namespace App\Form\Extension;

use App\Entity\Supplier;
use Sylius\Bundle\ProductBundle\Form\Type\ProductType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;

final class ProductTypeExtension extends AbstractTypeExtension
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('supplier', EntityType::class, [
            'class' => Supplier::class,
            'choice_label' => 'name',
            'choice_value' => 'id',
        ]);
    }

    public function getExtendedTypes(): iterable
    {
        yield ProductType::class;
    }

}
