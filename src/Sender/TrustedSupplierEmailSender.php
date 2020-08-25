<?php

declare(strict_types=1);

namespace App\Sender;

use App\Entity\Supplier;
use Sylius\Component\Mailer\Sender\SenderInterface;

final class TrustedSupplierEmailSender implements TrustedSupplierEmailSenderInterface
{
    /**
     * @var SenderInterface
     */
    private $sender;

    public function __construct(SenderInterface $sender)
    {
        $this->sender = $sender;
    }

    public function send(Supplier $supplier): void
    {
        $this->sender->send('trust_supplier', [$supplier->getEmail()], ['supplier' => $supplier]);
    }
}
