<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PostRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PostRepository::class)
 * @ApiResource(
 *     paginationItemsPerPage=5,
 *     paginationMaximumItemsPerPage=5,
 *     paginationClientItemsPerPage=true,
 *     normalizationContext={"groups"={"Post:collection:read"}},
 *     collectionOperations={
 *        "get"={
 *          "normalization_context"={"groups"={"Post:collection:read"}}
 *        },
 *        "post"={
 *          "denormalization_context"={"groups"={"Post:collection:write"}},
 *          "validation_groups"={"Post:collection:write"}
 *       }
 *     },
 *     itemOperations={
 *       "get"={
 *         "normalization_context"={"groups"={"Post:collection:read","Post:item:read"}}
 *       },
 *       "put"={
 *          "denormalization_context"={"groups"={"Post:item:write"}},
 *           "validation_groups"={"Post:item:write"}
 *       },
 *       "delete"
 *     }
 *  )
 */
class Post
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"Post:collection:read", "Post:collection:write", "Post:item:write"})
     * @Assert\Length(min=5, groups={"Post:collection:write", "Post:item:write"})
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"Post:collection:read", "Post:collection:write", "Post:item:write"})
     */
    private $slug;

    /**
     * @ORM\Column(type="text")
     * @Groups({"Post:item:read", "Post:collection:write", "Post:item:write"})
     */
    private $content;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @Groups({"Post:item:read"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @Groups({"Post:item:read"})
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="posts", cascade={"persist"})
     * @Groups({"Post:item:read", "Post:collection:read", "Post:collection:write"})
     * @Assert\Valid()
     */
    private $category;

    public function __construct()
    {
        $this->updatedAt = new \DateTimeImmutable();
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }
}
