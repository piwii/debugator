<?php

namespace App\Services;

use App\Repository\DeveloperRepository;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class Debugator
{
    private CacheInterface $cache;
    private DeveloperRepository $developerRepository;

    public function __construct(DeveloperRepository $developerRepository, CacheInterface $cache)
    {
        $this->developerRepository = $developerRepository;
        $this->cache = $cache;
    }

    public function help(): string
    {
        return 'help';
    }

    public function all(): string
    {
        $response = 'Liste des developpeurs\n';
        foreach ($this->developerRepository->getAll() as $developer) {
            $response .= sprintf('* %s [%s]\n', $developer->getName(), $developer->getType());
        }

        return $response;
    }

    public function list(string $cmd, ?string $password = ''): string
    {
        if ($password === 'zuSUs3qa91000') {
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

            $response = 'Randomisation de la liste :\n';
            for ($i = 0; $i < count($frontDeveloper); $i++) {
                $response .= sprintf('* [%s] *%s* avec *%s*\n', $date->format('Y-m-d'), $frontDeveloper[$i]->getName(), $backDeveloper[$i % count($backDeveloper)]->getName());
                $date->modify('+1 week');
            }

            return $response;
        });
    }
}
