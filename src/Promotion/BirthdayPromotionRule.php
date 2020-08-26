<?php

declare(strict_types=1);

namespace App\Promotion;

use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Promotion\Checker\Rule\RuleCheckerInterface;
use Sylius\Component\Promotion\Model\PromotionSubjectInterface;

final class BirthdayPromotionRule implements RuleCheckerInterface
{
    public function isEligible(PromotionSubjectInterface $subject, array $configuration): bool
    {
        /** @var OrderInterface $subject */
        $customer = $subject->getCustomer();

        if ($customer === null) {
            return false;
        }

        $birthday = $customer->getBirthday();

        if ($birthday === null) {
            return false;
        }

        return $birthday->format('md') === (new \DateTime('now'))->format('md');
    }
}
