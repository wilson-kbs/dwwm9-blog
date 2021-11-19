<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\RedactorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\Length;


#[
    ORM\Entity(repositoryClass: RedactorRepository::class),
    ORM\HasLifecycleCallbacks,
    ApiResource(
        collectionOperations: [
            'get' => [
                'normalization_context' => ['groups' => ['read:Redactor', 'read:Redactor:collection']],
                "pagination_items_per_page" => 20,
                "pagination_client_items_per_page" => true,
                "order" => ["createdAt" => "DESC"]
            ],
            'post' => [
                'denormalization_context' => ['groups' => ['write:Redactor', 'POST:write:Redactor']],
                'openapi_context' => [
                    'security' => [['bearerAuth' => []]],
                ],
                'security' => 'is_granted("ROLE_ADMIN")',
                "security_message" => "Sorry, but you are not permission.",
            ],
        ],
        itemOperations: [
            'get' => [
                'normalization_context' => ['groups' => ['read:Redactor', 'read:Redactor:item']],
            ],
            'put' => [
                'denormalization_context' => ['groups' => ['write:Redactor']],
                'openapi_context' => [
                    'security' => [['bearerAuth' => []]]
                ],
                'security' => 'is_granted("ROLE_ADMIN") or object.user == user',
                "security_message" => "Sorry, but you are not permission.",
            ],
            'patch' => [
                'denormalization_context' => ['groups' => ['write:Redactor']],
                'openapi_context' => [
                    'security' => [['bearerAuth' => []]]
                ],
                'security' => 'is_granted("ROLE_ADMIN") or object.user == user',
                "security_message" => "Sorry, but you are not permission.",
            ],
            'delete' => [
                'openapi_context' => [
                    'security' => [['bearerAuth' => []]]
                ],
                'security' => 'is_granted("ROLE_ADMIN")',
                "security_message" => "Sorry, but you are not permission.",
            ]
        ],
    )
]
#[ApiFilter(SearchFilter::class, properties: ['title' => 'partial'])]
class Redactor
{
    #[
        ORM\Id,
        ORM\GeneratedValue,
        ORM\Column(type: Types::INTEGER),
        Groups(['read:Redactor', 'read:Post']),
    ]
    private $id;

    #[
        ORM\Column(type: Types::STRING, length: 255),
        Length(min: 3, max: 100),
        Groups(['read:Redactor', 'POST:write:Redactor', 'read:Post'])
    ]
    private $lastname;

    #[
        ORM\Column(type: Types::STRING, length: 255),
        Length(min: 3, max: 100),
        Groups(['read:Redactor', 'POST:write:Redactor', 'read:Post'])
    ]
    private $firstname;

    #[
        ORM\Column(type: Types::TEXT),
        Length(min: 3, max: 2048),
        Groups(['read:Redactor', 'write:Redactor', 'read:Post'])
    ]
    private $bio;

    #[
        ORM\Column(type: Types::STRING, length: 2048),
        Groups(['read:Redactor', 'write:Redactor', 'read:Post'])
    ]
    private $img;

    #[
        ORM\Column(type: Types::DATETIME_IMMUTABLE),
        Groups('read:Redactor', 'read:Redactor:item')
    ]
    private $createdAt;

    #[
        ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true),
        Groups('read:Redactor', 'read:Redactor:item')
    ]
    private $updatedAt;

    #[
        ORM\OneToOne(targetEntity: User::class, inversedBy: "redactor", cascade: ["persist"]),
        Groups(['POST:write:Redactor'])
    ]
    private $user;

    #[
        ORM\OneToMany(targetEntity: Post::class, mappedBy: "redactor", cascade: ["detach", "merge"]),
        // Groups([]),
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

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getBio(): ?string
    {
        return $this->bio;
    }

    public function setBio(string $bio): self
    {
        $this->bio = $bio;

        return $this;
    }

    public function getImg(): ?string
    {
        return $this->img;
    }

    public function setImg(string $img): self
    {
        $this->img = $img;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

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
            $post->setRedactor($this);
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getRedactor() === $this) {
                $post->setRedactor(null);
            }
        }

        return $this;
    }

    #[ORM\PrePersist]
    public function onPersist(): void
    {
        if ($this->getUser() !== null) {
            $this->user->addRoles('ROLE_REDACTOR');
        }

        if ($this->getCreatedAt() === null) {
            $this->setCreatedAt(new \DateTimeImmutable('now'));
        }
    }

    #[ORM\PreUpdate]
    public function onUpdatedPost(): void
    {
        $this->setUpdatedAt(new \DateTime('now'));
    }
}
