<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\PublishController;
use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Entity\UserRedactorInterface;
use Symfony\Component\Validator\Constraints\Length;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiSubresource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Controller\CommentController;
use App\Controller\PostImageController;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Vich\Uploadable()
 */
#[ORM\Entity(repositoryClass: PostRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[
    ApiResource(
        collectionOperations: [
            'get' => [
                'normalization_context' => ['groups' => ['read:Post', 'read:Post:collection']],
                "pagination_items_per_page" => 50,
                "pagination_client_items_per_page" => true,
                "order" => ["publishedAt" => "DESC"]
            ],
            'post' => [
                'denormalization_context' => ['groups' => ['write:Post']],

                'openapi_context' => [
                    'security' => [['bearerAuth' => []]]
                ],
                'security' => 'is_granted("ROLE_REDACTOR")',
                "security_message" => "Sorry, but you are not a redactor.",
            ],
        ],
        itemOperations: [
            'get' => [
                'normalization_context' => ['groups' => ['read:Post', 'read:Post:item']],
            ],
            'image' => [
                'method' => 'POST',
                'path' => '/posts/{id}/image',
                'controller' => PostImageController::class,
                'normalization_context' => ['groups' => ['read:Post', 'read:Post:collection']],
                'deserialize' => false,
                'openapi_context' => [
                    'requestBody' => [
                        'content' => [
                            'multipart/form-data' => [
                                'schema' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'file' => [
                                            'type' => 'string',
                                            'format' => 'binary',
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'publish' => [
                'path' => '/posts/{id}/publish',
                'method' => 'POST',
                'deserialize' => false,
                'controller' => PublishController::class,
                'normalization_context' => ['groups' => ['read:Post']],
                'write' => false,
                'openapi_context' => [
                    'security' => [['bearerAuth' => []]]
                ],
                'security' => 'is_granted("ROLE_REDACTOR")',
                "security_message" => "Sorry, but you are not the post redactor.",
            ],
            'comment' => [
                'path' => '/posts/{id}/comments',
                'method' => 'POST',
                'controller' => CommentController::class,
                'write' => false,
                'openapi_context' => [
                    'security' => [['bearerAuth' => []]]

                ],
                "swagger_context" => [],
                'security' => 'is_granted("ROLE_USER")',
                "security_message" => "Sorry, but you are not permission.",
            ],
            'put' => [
                'denormalization_context' => ['groups' => ['write:Post:item']],
                'openapi_context' => [
                    'security' => [['bearerAuth' => []]]
                ],
                'security' => 'is_granted("ROLE_REDACTOR") and object.redactor == user',
                "security_message" => "Sorry, but you are not the post redactor.",
            ],
            'patch' => [
                'openapi_context' => [
                    'security' => [['bearerAuth' => []]]
                ],
                'security' => 'is_granted("ROLE_REDACTOR") and object.redactor == user',
                "security_message" => "Sorry, but you are not the post redactor.",
            ],
            'delete' => [
                'openapi_context' => [
                    'security' => [['bearerAuth' => []]]
                ],
                'security' => 'is_granted("ROLE_ADMIN") or (is_granted("ROLE_REDACTOR") and object.redactor == user)',
                "security_message" => "Sorry, but you are not the post redactor.",
            ]
        ],
    ),
]
#[ApiFilter(OrderFilter::class, properties: ['id', 'title', 'publishedAt'], arguments: ['orderParameterName' => 'order'])]
#[ApiFilter(DateFilter::class, properties: ['createdAt'])]
#[ApiFilter(SearchFilter::class, properties: ['title' => 'partial'])]
class Post implements UserRedactorInterface
{
    #[
        ORM\Id,
        ORM\GeneratedValue,
        ORM\Column(type: Types::INTEGER),
        Groups(['read:Post', 'read:Category'])
    ]
    private $id;

    /**
     * @Vich\UploadableField(mapping="post_image", fileNameProperty="imgPath")
     */
    // #[Assert\NotNull(groups: ['read:Post'])]
    private ?File $img = null;


    #[
        ORM\Column(type: Types::STRING, length: 4096, nullable: true),
    ]
    private $imgPath;

    #[
        ORM\Column(type: Types::STRING, length: 255),
        Groups(['read:Post', 'write:Post', 'read:Category', 'write:Post:item']),
        Length(min: 5, max: 200)
    ]
    private $title;

    #[
        ORM\Column(type: Types::STRING, length: 4096),
        Groups(['read:Post', 'read:Post:item', 'write:Post', 'write:Post:item']),
        Length(min: 5, max: 4096)
    ]
    private $description;

    #[
        ORM\Column(type: Types::TEXT),
        Groups(['read:Post:item', 'write:Post', 'write:Post:item']),
        Length(min: 5, max: 1294967295)
    ]
    private $content;

    #[
        ORM\Column(type: Types::DATETIME_IMMUTABLE)
    ]
    private $createdAt;

    #[
        ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true),
    ]
    private $updatedImageAt;

    #[
        ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true),
        Groups('read:Post', 'read:Post:item')
    ]
    private $updatedAt;

    #[
        ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true),
        Groups('read:Post', 'read:Post:item')
    ]
    private $publishedAt;

    #[
        ORM\Column(type: Types::BOOLEAN),
        Groups(['write:Post:item'])
    ]
    private $isOnline;

    #[
        ORM\ManyToOne(targetEntity: Category::class, inversedBy: "posts"),
        Groups(['read:Post:item', 'write:Post'])
    ]
    private $category;

    #[
        ORM\Column(type: Types::BOOLEAN),
        Groups(['read:Post:collection', 'read:Post:item'])
    ]
    private $isEdited;

    public $contentUrl = null;

    /**
     * @return string|null
     */
    #[
        Groups(['read:Post', 'read:Post:item', 'read:Category']),
    ]
    private $imgUrl;


    private $__isOnPublish = false;


    #[
        ORM\ManyToOne(targetEntity: Redactor::class, inversedBy: "posts"),
        // ORM\JoinColumn(nullable: true),
        Groups(['read:Post'])
    ]
    private $redactor;

    #[
        ORM\OneToMany(targetEntity: Comment::class, mappedBy: "post", orphanRemoval: true),
        ApiSubresource(maxDepth: 1)
    ]
    private $comments;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

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

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getPublishedAt(): ?\DateTime
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(?\DateTime $publishedAt): self
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    public function getIsOnline(): ?bool
    {
        return $this->isOnline;
    }

    public function setIsOnline(bool $isOnline): self
    {
        if ($isOnline === true && $this->getPublishedAt() === null) {
            $this->setPublishedAt(new \DateTime('now'));
        }

        $this->isOnline = $isOnline;
        $this->__isOnPublish = $isOnline;

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

    public function getIsEdited(): ?bool
    {
        return $this->isEdited;
    }

    public function setIsEdited(bool $isEdited): self
    {
        $this->isEdited = $isEdited;

        return $this;
    }

    public function getRedactor(): ?Redactor
    {
        return $this->redactor;
    }

    public function setRedactor(?Redactor $redactor): self
    {
        $this->redactor = $redactor;

        return $this;
    }

    #[ORM\PrePersist]
    public function onCreatedPost(): void
    {
        if ($this->getIsOnline() === null) {
            $this->setIsOnline(false);
        }

        if ($this->getCreatedAt() === null) {
            $this->setCreatedAt(new \DateTimeImmutable('now'));
            $this->setIsEdited(false);
        }
    }

    #[ORM\PreUpdate]
    public function onUpdatedPost(): void
    {
        if ($this->__isOnPublish) return;
        $this->setUpdatedAt(new \DateTime('now'));
        $this->setIsEdited(true);
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setPost($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getPost() === $this) {
                $comment->setPost(null);
            }
        }

        return $this;
    }

    public function getImg(): ?File
    {
        return $this->img;
    }

    public function setImg(?File $img): self
    {
        $this->__isOnPublish = true;
        $this->img = $img;

        return $this;
    }

    public function getImgPath(): ?string
    {
        return $this->imgPath;
    }

    public function setImgPath(?string $url): self
    {
        $this->imgPath = $url;
        return $this;
    }

    public function getImgUrl(): ?string
    {
        return $this->contentUrl;
    }

    public function setImgUrl(?string $imgUrl): self
    {
        $this->imgUrl = $imgUrl;
        return $this;
    }

    /**
     * Get the value of updatedImageAt
     */
    public function getUpdatedImageAt(): ?\DateTime
    {
        return $this->updatedImageAt;
    }

    /**
     * Set the value of updatedImageAt
     *
     * @return  self
     */
    public function setUpdatedImageAt(?\DateTime $updatedImageAt)
    {
        $this->updatedImageAt = $updatedImageAt;

        return $this;
    }
}
