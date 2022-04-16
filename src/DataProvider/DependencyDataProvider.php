<?php

namespace App\DataProvider;


use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\Dependency;
use App\Repository\DependenceRepository;
use Ramsey\Uuid\Uuid;


class DependencyDataProvider implements ContextAwareCollectionDataProviderInterface, ItemDataProviderInterface, RestrictedDataProviderInterface
{

    private $dependenceRepository;

    public function __construct(DependenceRepository $dependenceRepository)
    {
        $this->dependenceRepository = $dependenceRepository;
    }

    public function getCollection(string $resourceClass, string $operationName = null, array $context = [])
    {
        return $this->dependenceRepository->getAll();
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return $resourceClass === Dependency::class;
    }

    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = [])
    {
        return $this->dependenceRepository->getOne($id);
    }
}