<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="app_supplier")
 */
class Supplier
{
    /**
     * @var int|null
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=false)
     */
    private $name;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=false)
     */
    private $email;

}
