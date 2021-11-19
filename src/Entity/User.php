<?php

namespace App\Entity;

use ApiPlatform\Core\Action\NotFoundAction;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Controller\MeController;
use App\Controller\SignUpController;
use App\Dto\UserInput;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Lexik\Bundle\JWTAuthenticationBundle\Security\User\JWTUserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\Length;


#[
    ORM\Entity(repositoryClass: UserRepository::class),
    ORM\HasLifecycleCallbacks,
    ApiResource(
        collectionOperations: [
            'get' => [
                'normalization_context' => ['groups' => ['read:User', 'read:User:collection']],
                "pagination_items_per_page" => 20,
                "pagination_client_items_per_page" => true,
                "order" => ["createdAt" => "DESC"],
                'openapi_context' => [
                    'security' => [['bearerAuth' => []]]
                ],
                'security' => 'is_granted("ROLE_ADMIN")'
            ],
        ],
        itemOperations: [
            'get' => [
                'controller' => NotFoundAction::class,
                'openapi_context' => ['summary' => 'hidden'],
                'read' => false,
                'output' => false
            ],
            'me' => [
                'pagination_enabled' => false,
                'path' => '/me',
                'method' => 'get',
                'controller' => MeController::class,
                'read' => false,
                'openapi_context' => [
                    'security' => [['bearerAuth' => []]]
                ],
                'security' => 'is_granted("ROLE_USER")'

            ],
            'signup' => [
                'openapi_context' => ['summary' => 'hidden'],
                'path' => '/signup',
                'method' => 'POST',
                'controller' => SignUpController::class,
                'normalization_context' => ['groups' => 'write:User'],
                'input' => UserInput::class,
                'read' => false,
            ]

        ],
        normalizationContext: ['groups' => ['read:User']]
    )
]
#[ApiFilter(SearchFilter::class, properties: ['username' => 'partial'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface, JWTUserInterface
{
    #[
        ORM\Id,
        ORM\GeneratedValue,
        ORM\Column(type: Types::INTEGER),
        Groups(['read:User', 'read:Post', 'read:User:collection'])
    ]
    private $id;

    #[
        ORM\Column(type: "string", length: 255),
        Groups(['read:User', 'read:Post', 'write:User', 'read:User:collection']),
        Length(min: 3, max: 150)
    ]
    private $username;

    #[
        Column(type: "string", length: 180, unique: true),
        Groups(['read:User', 'write:User', 'read:User:collection']),
        Length(min: 6, max: 180)
    ]
    private $email;


    #[
        ORM\Column(type: Types::JSON),
        Groups(['read:User:collection'])
    ]
    private $roles = [];

    /**
     * @var string The hashed password
     */
    #[
        Column(type: "string", length: 255),
        Groups(['write:User']),
        Length(min: 4),
    ]
    private $password;

    #[
        ORM\Column(type: Types::DATETIME_IMMUTABLE),
        Groups(['read:User:collection'])
    ]
    private $createdAt;

    #[
        ORM\OneToOne(targetEntity: Redactor::class, mappedBy: "user"),
        Groups(['read:User:collection'])
    ]
    private $redactor;

    #[
        ORM\OneToMany(targetEntity: Comment::class, mappedBy: "user")
    ]
    private $comments;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }


    public function getUsername(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function addRoles(string ...$values)
    {
        foreach ($values as $value) {
            if (!in_array($value, $this->roles, true))
                $this->roles[] = $value;
        }
    }


    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }


    public function removeRoles(string ...$values): self
    {
        foreach ($values as $value) {
            $index = array_search($value, $this->roles);
            if ($index !== false)
                unset($this->roles[$index]);
        }
        $this->roles = array_values($this->roles);
        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public static function createFromPayload($email, array $payload)
    {
        return (new User())->setId($payload['id'])->setEmail($email)->setRoles($payload['roles']);
    }

    public function getRedactor(): ?Redactor
    {
        return $this->redactor;
    }

    public function setRedactor(?Redactor $redactor): self
    {
        // unset the owning side of the relation if necessary
        if ($redactor === null && $this->redactor !== null) {
            $this->redactor->setUser(null);
        }

        // set the owning side of the relation if necessary
        if ($redactor !== null && $redactor->getUser() !== $this) {
            $redactor->setUser($this);
        }

        $this->redactor = $redactor;

        if ($redactor !== null)
            $this->addRoles('ROLE_REDACTOR');
        else if ($redactor === null)
            $this->removeRoles('ROLE_REDACTOR');

        return $this;
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
            $comment->setUser($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getUser() === $this) {
                $comment->setUser(null);
            }
        }

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

    #[ORM\PrePersist]
    public function onCreatedPost(): void
    {
        if ($this->getCreatedAt() === null) {
            $this->setCreatedAt(new \DateTimeImmutable('now'));
        }
    }
}
