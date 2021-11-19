<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CommentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[
    ORM\Entity(repositoryClass: CommentRepository::class),
    ORM\HasLifecycleCallbacks,
    ApiResource(
        collectionOperations: [
            'get' => [
                'normalization_context' => ['groups' => ['read:Comment', 'read:Comment:collection']],
                "pagination_items_per_page" => 50,
                "pagination_client_items_per_page" => true,
                "order" => ["createdAt" => "ASC"]
            ],
            'post' => [
                'denormalization_context' => ['groups' => ['write:Comment', 'POST:write:Comment']],
                'openapi_context' => [
                    'security' => [['bearerAuth' => []]],
                ],
                'security' => 'is_granted("ROLE_USER")',
                "security_message" => "Sorry, but you are not permission.",
            ],
        ],
        itemOperations: [
            'get' => [
                'normalization_context' => ['groups' => ['read:Comment', 'read:Comment:item']],
            ],
            'put' => [
                'denormalization_context' => ['groups' => ['write:Comment']],
                'openapi_context' => [
                    'security' => [['bearerAuth' => []]]
                ],
                'security' => 'is_granted("ROLE_USER") and object.user == user',
                "security_message" => "Sorry, but you are not permission.",
            ],
            'patch' => [
                'denormalization_context' => ['groups' => ['write:Comment']],
                'openapi_context' => [
                    'security' => [['bearerAuth' => []]]
                ],
                'security' => 'is_granted("ROLE_USER") and object.user == user',
                "security_message" => "Sorry, but you are not permission.",
            ],
            'delete' => [
                'openapi_context' => [
                    'security' => [['bearerAuth' => []]]
                ],
                'security' => 'is_granted("ROLE_ADMIN") or (is_granted("ROLE_USER") and object.user == user)',
                "security_message" => "Sorry, but you are not permission.",
            ]
        ],
    )
]
class Comment
{
    #[
        ORM\Id,
        ORM\GeneratedValue,
        ORM\Column(type: Types::INTEGER),
    ]
    private $id;

    #[
        ORM\ManyToOne(targetEntity: Post::class, inversedBy: "comments"),
        ORM\JoinColumn(nullable: false),
        Groups(['POST:write:Comment', 'read:Comment:item'])
    ]
    private $post;

    #[
        ORM\ManyToOne(targetEntity: User::class, inversedBy: "comments"),
        Groups(['read:Comment'])
    ]
    private $user;

    #[
        ORM\Column(type: Types::TEXT),
        Groups(['read:Comment', 'write:Comment'])
    ]
    private $content;

    #[
        ORM\Column(type: Types::DATETIME_IMMUTABLE),
        Groups(['read:Comment'])
    ]
    private $createdAt;

    #[
        ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true),
        Groups(['read:Comment'])
    ]
    private $updatedAt;

    #[
        ORM\Column(type: Types::BOOLEAN),
        Groups(['read:Comment'])
    ]
    private $isEdited;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): self
    {
        $this->post = $post;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

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

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

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

    #[ORM\PrePersist]
    public function onCreatedPost(): void
    {
        if ($this->getCreatedAt() === null) {
            $this->setCreatedAt(new \DateTimeImmutable('now'));
            $this->setIsEdited(false);
        }
    }

    #[ORM\PreUpdate]
    public function onUpdatedPost(): void
    {
        $this->setUpdatedAt(new \DateTime('now'));
        $this->setIsEdited(true);
    }
}
