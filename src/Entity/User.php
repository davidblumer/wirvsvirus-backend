<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 *
 * @ApiResource(
 *     attributes={
 *         "denormalization_context"={
 *             "groups"={
 *                 "user_write"
 *             }
 *         },
 *         "filters"={
 *
 *         },
 *         "normalization_context"={
 *             "groups"={
 *                 "user_read"
 *             }
 *         }
 *     },
 *     itemOperations={
 *         "get"={
 *             "method"="GET"
 *         },
 *     },
 *     collectionOperations={
 *         "api_register"={
 *             "method"="POST",
 *         },
 *     },
 * )
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="guid")
     * @ORM\GeneratedValue(strategy="UUID")
     *
     * @Groups({
     *     "user_read",
     *     "ticket_read",
     * })
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     *
     * @Groups({
     *     "user_read",
     *     "user_write",
     *     "ticket_read",
     * })
     */
    private $email;

    /**
     * @ORM\Column(type="string")
     *
     * @Groups({
     *     "ticket_read",
     * })
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $lastName;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="string")
     *
     * @Groups({
     *     "user_write",
     * })
     */
    private $password;

    /**
     * @ORM\Embedded(class="Address")
     *
     * @Groups({
     *     "user_read",
     *     "user_write",
     * })
     */
    private $address;

    /**
     * @ORM\Column(type="string")
     *
     * @Groups({
     *     "user_read",
     *     "user_write",
     * })
     */
    private $paypal;

    /**
     * @ORM\OneToMany(targetEntity="Ticket", mappedBy="creator")
     *
     * @Groups({
     *     "user_read",
     * })
     */
    private $tickets;

    /**
     * @ORM\OneToMany(targetEntity="Ticket", mappedBy="acceptedBy")
     *
     * @Groups({
     *     "user_read",
     * })
     */
    private $acceptedTickets;

    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="user")
     *
     * @Groups({
     *     "user_read",
     * })
     */
    private $comments;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->address         = new Address();
        $this->tickets         = new ArrayCollection();
        $this->acceptedTickets = new ArrayCollection();
        $this->comments        = new ArrayCollection();
    }

    /**
     * @return string|null
     */
    public function __toString()
    {
        return $this->getEmail();
    }

    public function getId(): ?string
    {
        return $this->id;
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
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param mixed $firstName
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param mixed $lastName
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getUsername()
    {
        return (string)$this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles()
    {
        $roles   = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword(string $password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     * @return User
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPaypal()
    {
        return $this->paypal;
    }

    /**
     * @param mixed $paypal
     * @return User
     */
    public function setPaypal($paypal)
    {
        $this->paypal = $paypal;

        return $this;
    }

    /**
     * @return Collection|Ticket[]
     */
    public function getTickets(): Collection
    {
        return $this->tickets;
    }

    public function addTicket(Ticket $ticket): self
    {
        if (!$this->tickets->contains($ticket)) {
            $this->tickets[] = $ticket;
            $ticket->setAcceptedBy($this);
        }

        return $this;
    }

    public function removeTicket(Ticket $ticket): self
    {
        if ($this->tickets->contains($ticket)) {
            $this->tickets->removeElement($ticket);
            // set the owning side to null (unless already changed)
            if ($ticket->getAcceptedBy() === $this) {
                $ticket->setAcceptedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Ticket[]
     */
    public function getAcceptedTickets(): Collection
    {
        return $this->acceptedTickets;
    }

    public function addAcceptedTicket(Ticket $acceptedTicket): self
    {
        if (!$this->acceptedTickets->contains($acceptedTicket)) {
            $this->acceptedTickets[] = $acceptedTicket;
            $acceptedTicket->setAcceptedBy($this);
        }

        return $this;
    }

    public function removeAcceptedTicket(Ticket $acceptedTicket): self
    {
        if ($this->acceptedTickets->contains($acceptedTicket)) {
            $this->acceptedTickets->removeElement($acceptedTicket);
            // set the owning side to null (unless already changed)
            if ($acceptedTicket->getAcceptedBy() === $this) {
                $acceptedTicket->setAcceptedBy(null);
            }
        }

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
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getUser() === $this) {
                $comment->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }
}
