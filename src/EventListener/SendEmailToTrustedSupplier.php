<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Entity\Supplier;
use App\Sender\TrustedSupplierEmailSenderInterface;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Webmozart\Assert\Assert;

final class SendEmailToTrustedSupplier
{
    /**
     * @var TrustedSupplierEmailSenderInterface
     */
    private $supplierEmailSender;

    public function __construct(TrustedSupplierEmailSenderInterface $supplierEmailSender)
    {
        $this->supplierEmailSender = $supplierEmailSender;
    }

    public function __invoke(ResourceControllerEvent $controllerEvent): void
    {
        $subject = $controllerEvent->getSubject();

        Assert::isInstanceOf($subject, Supplier::class);

        $this->supplierEmailSender->send($subject);
    }
}
