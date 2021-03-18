<?php

namespace App\Repository;

use App\Entity\Developer;

class DeveloperRepository
{
    private array $developer = [];

    public function __construct()
    {
        $this->developer[] = new Developer('Chawki Messaoudi', Developer::TYPE_FRONT);
        $this->developer[] = new Developer('Dorian Belhaj', Developer::TYPE_FRONT);
        $this->developer[] = new Developer('Jaber Fares', Developer::TYPE_FRONT);
        $this->developer[] = new Developer('Francois Faucherie', Developer::TYPE_FRONT);
        $this->developer[] = new Developer('Mohamed Ali DRIDI', Developer::TYPE_FRONT);
        $this->developer[] = new Developer('Omar Harras', Developer::TYPE_FRONT);
        $this->developer[] = new Developer('Sinane Si Mohammed', Developer::TYPE_FRONT);
        $this->developer[] = new Developer('Marwa OUNALLI', Developer::TYPE_BACK);
        $this->developer[] = new Developer('Pascal Filipovicz', Developer::TYPE_BACK);
        $this->developer[] = new Developer('Sophie Kontomarkos', Developer::TYPE_BACK);
    }

    public function getAll(): array
    {
        return $this->developer;
    }

    private function getDeveloper(string $type): array
    {
        return array_filter($this->developer, function (Developer $developer) use ($type) {
            return $developer->getType() === $type;
        });
    }

    public function getBackDeveloper(): array
    {
        return $this->getDeveloper(Developer::TYPE_BACK);
    }

    public function getFrontDeveloper(): array
    {
        return $this->getDeveloper(Developer::TYPE_FRONT);
    }
}
