<?php

namespace App\DTO;

final class ServicePopularityStat
{
    public function __construct(
        private int $serviceId,
        private int $interactionCount,
        private ?float $averageRating = null,
    ) {
    }

    public function getServiceId(): int
    {
        return $this->serviceId;
    }

    public function getInteractionCount(): int
    {
        return $this->interactionCount;
    }

    public function getAverageRating(): ?float
    {
        return $this->averageRating;
    }
}