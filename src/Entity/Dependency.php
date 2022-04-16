<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     paginationEnabled=false,
 *     collectionOperations={"get"},
 *     itemOperations={"get"}
 * )
 */
class Dependency
{
    /**
     * @var string
     * @Assert\Length(min=10)
     * @ApiProperty(identifier=true)
     */
    private $uid;

    /**
     * @var string
     * @Assert\Length(min=3)
     * @ApiProperty(description="Name of dependency")
     */
    private $name;

    /**
     * @var string
     * @Assert\Length(min=3)
     * @ApiProperty(description="Version of dependency", example="5.2.*")
     */
    private $version;

    public function __construct(string $uid, string $name, string $version)
    {
        $this->uid = $uid;
        $this->name = $name;
        $this->version = $version;
    }


    /**
     * @return string
     */
    public function getUid(): string
    {
        return $this->uid;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }
}