<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use App\Controller\PostPublishController;
use App\Controller\PostCountController;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\PostRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PostRepository::class)
 * @ApiResource(
 *     normalizationContext={
 *          "groups"={"Post:collection:read"},
 *          "openapi_definition_name"="Collection"
 *     },
 *     paginationItemsPerPage=5,
 *     paginationMaximumItemsPerPage=5,
 *     paginationClientItemsPerPage=true,
 *     collectionOperations={
 *        "get"={
 *          "normalization_context"={"groups"={"Post:collection:read"}}
 *        },
 *        "post"={
 *          "denormalization_context"={"groups"={"Post:collection:write"}},
 *          "validation_groups"={"Post:collection:write"}
 *       },
 *      "count"={
 *          "method"="Get",
 *          "path"="/posts/count",
 *          "paginationEnabled"=false,
 *          "paginationClientItemsPerPage"=false,
 *
 *          "controller"=PostCountController::class,
 *          "filters"={},
 *          "pagination_enabled"=false,
 *          "openapi_context"={
 *              "summary"="Count total posts.",
 *              "parameters"={
 *                  {
 *                     "name"="publish",
 *                     "description"="Fitler published articles.",
 *                     "in"="query",
 *                     "schema"={
 *                       "type"="integer",
 *                       "minimum"=0,
 *                       "maximum"=1
 *                    }
 *                 }
 *             },
 *              "responses"={
 *                  "200"={
 *                      "description"="Number of posts",
 *                      "content"={
 *                          "application/json"={
 *                              "schema"={
 *                                  "type"="integer",
 *                                  "example"=3
 *                              }
 *                          }
 *                      }
 *                  }
 *             }
 *          }
 *        }
 *     },
 *     itemOperations={
 *       "get"={
 *         "normalization_context"={
 *              "groups"={"Post:collection:read","Post:item:read"},
 *               "openapi_definition_name"="Detail"
 *          }
 *       },
 *       "put"={
 *          "denormalization_context"={"groups"={"Post:item:write"}},
 *           "validation_groups"={"Post:item:write"}
 *       },
 *       "delete",
 *        "patch",
 *        "publish"={
 *          "method"="Put",
 *          "path"="/posts/{id}/publish",
 *          "controller"=PostPublishController::class,
 *          "denormalization_context"={"groups"={"Post:item:publish"}},
 *          "openapi_context"={
 *              "summary"="Publish a Post resource.",
 *              "requestBody"={
 *              }
 *          }
 *        }
 *     }
 *  )
 * @ApiFilter(
 *    SearchFilter::class,
 *     properties={
 *       "id"="exact",
 *       "title"="partial"
 *     }
 * )
 * @ApiFilter(
 *    OrderFilter::class,
 *     properties={"createdAt"="DESC","title"="DESC"},
 *     arguments={"orderParameterName"="order"}
 * )
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

    /**
     * @ORM\Column(type="boolean", options={"default"=0})
     * @Groups({"Post:collection:read", "Post:item:publish"})
     * @ApiProperty(openapiContext={"type"="boolean", "description"="is publish?"})
     */
    private $publish = false;

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

    public function getPublish(): ?bool
    {
        return $this->publish;
    }

    public function setPublish(bool $publish): self
    {
        $this->publish = $publish;

        return $this;
    }
}
