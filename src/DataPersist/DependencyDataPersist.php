<?php

namespace App\DataPersist;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Dependency;
use App\Repository\DependenceRepository;

class DependencyDataPersist implements ContextAwareDataPersisterInterface
{
    private $dependenceRepository;

    public function __construct(DependenceRepository $dependenceRepository)
    {
        $this->dependenceRepository = $dependenceRepository;
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof Dependency;

    }

    public function persist($data, array $context = [])
    {
        $this->dependenceRepository->persist($data);
    }

    public function remove($data, array $context = [])
    {
        $this->dependenceRepository->remove($data);
    }

}
