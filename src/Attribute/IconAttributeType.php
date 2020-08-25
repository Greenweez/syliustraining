<?php

declare(strict_types=1);

namespace App\Attribute;

use Sylius\Component\Attribute\AttributeType\AttributeTypeInterface;
use Sylius\Component\Attribute\Model\AttributeValue;
use Sylius\Component\Attribute\Model\AttributeValueInterface;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

final class IconAttributeType implements AttributeTypeInterface
{
    public function getStorageType(): string
    {
        return AttributeValue::STORAGE_JSON;
    }

    public function getType(): string
    {
        return 'icon';
    }

    public function validate(
        AttributeValueInterface $attributeValue,
        ExecutionContextInterface $context,
        array $configuration
    ): void {
    }
}
