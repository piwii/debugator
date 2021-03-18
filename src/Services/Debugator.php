<?php

namespace App\Services;

use App\Repository\DeveloperRepository;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class Debugator
{
    private CacheInterface $cache;
    private DeveloperRepository $developerRepository;
    private string $password;

    public function __construct(DeveloperRepository $developerRepository, CacheInterface $cache, string $password)
    {
        $this->developerRepository = $developerRepository;
        $this->cache = $cache;
        $this->password = $password;
    }

    public function getAvailableCommand(): array
    {
        return [
            'help',
            'all',
            'list'
        ];
    }

    public function help(): string
    {
        $response = "*List of available command* :\n";
        $response .= "* *all* : affiche tous les developeurs\n";
        $response .= "* *list* : affiche les developeurs d'astreinte chaque semaine\n";
        $response .= "* *help* : affiche cet aide :-)\n";

        return $response;
    }

    public function all(): string
    {
        $response = "Liste des developpeurs\n";
        foreach ($this->developerRepository->getAll() as $developer) {
            $response .= sprintf("* %s [%s]\n", $developer->getName(), $developer->getType());
        }

        return $response;
    }

    public function list(string $cmd, ?string $password = ''): string
    {
        if ($password === $this->password) {
            $this->cache->delete('random');
        }

        return $this->cache->get('random', function (ItemInterface $item) {
            $item->expiresAfter(3600);

            $frontDeveloper = $this->developerRepository->getFrontDeveloper();
            shuffle($frontDeveloper);

            $backDeveloper = $this->developerRepository->getBackDeveloper();
            shuffle($backDeveloper);

            $date = new \DateTime();
            $date->modify('next monday');

            $response = "Liste des developeurs d'astreinte chaque semaine :\n";
            for ($i = 0; $i < count($frontDeveloper); $i++) {
                $response .= sprintf("* [%s] *%s* avec *%s*\n", $date->format('Y-m-d'), $frontDeveloper[$i]->getName(), $backDeveloper[$i % count($backDeveloper)]->getName());
                $date->modify('+1 week');
            }

            return $response;
        });
    }
}
