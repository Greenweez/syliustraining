<?php

declare(strict_types=1);

namespace App\Sender;

use App\Entity\Supplier;

interface TrustedSupplierEmailSenderInterface
{
    public function send(Supplier $supplier): void;
}
