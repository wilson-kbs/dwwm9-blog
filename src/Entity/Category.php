<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


#[
    ORM\Entity(repositoryClass: CategoryRepository::class),
    ORM\HasLifecycleCallbacks,
    ApiResource(
        collectionOperations: [
            'get' => [
                'normalization_context' => ['groups' => ['read:Category', 'read:Category:collection']],
                "order" => ["name" => "ASC"]
            ],
            'post' => [
                'denormalization_context' => ['groups' => ['write:Category']],
                'openapi_context' => [
                    'security' => [['bearerAuth' => []]]
                ],
                'security' => 'is_granted("ROLE_ADMIN")',
                'security_message' => 'Sorry, but you are not permission.',
            ],

        ],
        itemOperations: [
            'get' => [
                'normalization_context' => ['groups' => ['read:Category', 'read:Category:item']],
                'security' => 'object.getIsVisible() == true or is_granted("ROLE_USER")',
                'security_message' => 'Sorry, but you are not permission.',
            ],
            'put' => [
                'normalization_context' => ['groups' => ['write:Category:item']],
                'openapi_context' => [
                    'security' => [['bearerAuth' => []]]
                ],
                'security' => 'is_granted("ROLE_ADMIN")',
                'security_message' => 'Sorry, but you are not permission.',
            ],
            'patch' => [
                'openapi_context' => [
                    'security' => [['bearerAuth' => []]]
                ],
                'security' => 'is_granted("ROLE_ADMIN")',
                'security_message' => 'Sorry, but you are not permission.',
            ],
            'delete' => [
                'openapi_context' => [
                    'security' => [['bearerAuth' => []]]
                ],
                'security' => 'is_granted("ROLE_ADMIN")',
                'security_message' => 'Sorry, but you are not permission.',
            ]
        ],
    )
]
class Category
{
    #[
        ORM\Id,
        ORM\GeneratedValue,
        ORM\Column(type: Types::INTEGER),
        Groups(['read:Category', 'read:Post'])
    ]
    private $id;

    #[
        ORM\Column(type: Types::STRING, length: 255),
        Groups(['read:Category', 'read:Post', 'write:Category', 'write:Category:item'])
    ]
    private $name;

    #[
        ORM\Column(type: Types::BOOLEAN),
        Groups(['read:Category:item', 'write:Category', 'write:Category:item'])
    ]
    private $isAnonymousVisible = false;

    #[
        ORM\Column(type: Types::BOOLEAN),
        Groups(['read:Category:item', 'write:Category', 'write:Category:item'])
    ]
    private $isVisible = false;

    #[
        ORM\Column(type: Types::DATETIME_IMMUTABLE),
        Groups(['read:Category:item'])
    ]
    private $createdAt;

    #[
        ORM\OneToMany(targetEntity: Post::class, mappedBy: "category"),
        Groups(['read:Category:item']),
        ApiSubresource(maxDepth: 1)
    ]
    private $posts;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getIsVisible(): ?bool
    {
        return $this->isVisible;
    }

    public function setIsAnonymousVisible(bool $isAnonymousVisible): self
    {
        $this->isAnonymousVisible = $isAnonymousVisible;

        return $this;
    }

    public function getIsAnonymousVisible(): ?bool
    {
        return $this->isAnonymousVisible;
    }

    public function setIsVisible(bool $isVisible): self
    {
        $this->isVisible = $isVisible;

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

    /**
     * @return Collection|Post[]
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setCategory($this);
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getCategory() === $this) {
                $post->setCategory(null);
            }
        }

        return $this;
    }

    #[
        ORM\PrePersist,
        ORM\PreUpdate,
    ]
    public function updatedTimestamps(): void
    {
        if ($this->getCreatedAt() === null) {
            $this->setCreatedAt(new \DateTimeImmutable('now'));
        }
    }
}
