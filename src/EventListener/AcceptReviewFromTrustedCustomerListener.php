<?php

declare(strict_types=1);

namespace App\EventListener;

use SM\Factory\FactoryInterface;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Core\ProductReviewTransitions;
use Sylius\Component\Review\Model\ReviewInterface;
use Webmozart\Assert\Assert;

final class AcceptReviewFromTrustedCustomerListener
{
    /**
     * @var FactoryInterface
     */
    private $factory;

    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function __invoke(ResourceControllerEvent $controllerEvent): void
    {
        /** @var ReviewInterface|mixed $subject */
        $subject = $controllerEvent->getSubject();

        Assert::isInstanceOf($subject, ReviewInterface::class);

        $customer = $subject->getAuthor();

        if (false !== strpos($customer->getEmail(), 'trust')) {
            $stateMachine = $this->factory->get($subject, ProductReviewTransitions::GRAPH);

            $stateMachine->apply(ProductReviewTransitions::TRANSITION_ACCEPT);
        }
    }
}
