<?php

namespace App\Repository;

use App\Entity\Dependency;

class DependenceRepository
{
    private $filePath;

    public function __construct(string $rootPath)
    {
        $this->filePath = $rootPath . "/composer.json";
    }

    /**
     * @param Dependency $data
     */
    public function persist(Dependency $data)
    {
        $jsonContent = json_decode(file_get_contents($this->filePath, true), true);

        $jsonContent['require'] [$data->getName()] = $data->getVersion();
        file_put_contents($this->filePath, json_encode($jsonContent, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }

    public function remove($data)
    {
        $jsonContent = json_decode(file_get_contents($this->filePath, true), true);

        unset($jsonContent['require'] [$data->getName()]);
        file_put_contents($this->filePath, json_encode($jsonContent, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }

    /**
     * @param string $uid
     * @return Dependency |null
     */
    public function getOne(string $uid): ?Dependency
    {
        $items = $this->getAll();
        /** @var Dependency $item */
        $arrayFilter = array_filter($items, function ($item) use ($uid) {
            return $item->getUid() === $uid;
        });
        return $arrayFilter ? reset($arrayFilter) : null;
    }

    /**
     * @return Dependency[]
     */
    public function getAll(): array
    {
        $items = [];
        $json = json_decode(file_get_contents($this->filePath, true));

        foreach ($json->require as $name => $version) {
            $items[] = new Dependency($name, $version);
        }

        return $items;
    }
}