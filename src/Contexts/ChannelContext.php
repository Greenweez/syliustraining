<?php

declare(strict_types=1);

namespace App\Contexts;

use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Channel\Model\ChannelInterface;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\VarDumper\VarDumper;

final class ChannelContext implements ChannelContextInterface
{
    /** @var RequestStack */
    private $requestStack;
    /** @var ChannelRepositoryInterface */
    private $channelRepository;

    public function __construct(RequestStack $requestStack, ChannelRepositoryInterface $channelRepository)
    {
        $this->requestStack = $requestStack;
        $this->channelRepository = $channelRepository;
    }

    public function getChannel(): ChannelInterface
    {
        return $this->channelRepository->findOneBy([]);
    }
}
