<?php

declare(strict_types=1);

namespace App\Fixtures\Factory;

use App\Entity\Supplier;
use Sylius\Bundle\CoreBundle\Fixture\Factory\AbstractExampleFactory;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class SupplierExampleFactory extends AbstractExampleFactory
{
    /** @var FactoryInterface */
    private $supplierFactory;

    /** @var \Faker\Generator */
    private $faker;

    /** @var OptionsResolver */
    private $optionsResolver;

    public function __construct(FactoryInterface $supplierFactory)
    {
        $this->supplierFactory = $supplierFactory;

        $this->faker = \Faker\Factory::create();
        $this->optionsResolver = new OptionsResolver();

        $this->configureOptions($this->optionsResolver);
    }

    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefault('name', function (Options $options): string {
                /** @var string $words */
                $words = $this->faker->words(3, true);

                return $words;
            })
            ->setDefault('email', function (Options $options): string {
                return $this->faker->email;
            })
        ;
    }

    public function create(array $options = []): Supplier
    {
        $options = $this->optionsResolver->resolve($options);

        /** @var Supplier $supplier */
        $supplier = $this->supplierFactory->createNew();

        $supplier->setName($options['name']);
        $supplier->setEmail($options['email']);

        return $supplier;
    }
}
