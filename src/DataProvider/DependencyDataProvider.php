<?php

namespace App\DataProvider;


use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\Dependency;
use Ramsey\Uuid\Uuid;


class DependencyDataProvider implements ContextAwareCollectionDataProviderInterface, ItemDataProviderInterface, RestrictedDataProviderInterface
{

    private $rootPath;


    public function __construct(string $rootPath)
    {
        $this->rootPath = $rootPath;
    }

    public function getCollection(string $resourceClass, string $operationName = null, array $context = [])
    {
        return $this->getDependencies();
    }

    /**
     * @return array
     */
    public function getDependencies(): array
    {
        $items = [];
        $filePath = $this->rootPath . "/composer.json";
        $json = json_decode(file_get_contents($filePath, true));

        foreach ($json->require as $name => $version) {
            $items[] = new Dependency(Uuid::uuid5(Uuid::NAMESPACE_URL, $name), $name, $version);
        }

        return $items;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return $resourceClass === Dependency::class;
    }

    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = [])
    {

        $items = $this->getDependencies();
        /** @var Dependency $item */
        return array_filter($items, function ($item) use ($id) {
            return $item->getUid() === $id;
        });
    }
}