<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Entity\Types\TicketStatus;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TicketRepository")
 *
 * @ApiResource(
 *     attributes={
 *         "denormalization_context"={
 *             "groups"={
 *                 "ticket_write"
 *             }
 *         },
 *         "filters"={
 *
 *         },
 *         "normalization_context"={
 *             "groups"={
 *                 "ticket_read"
 *             }
 *         }
 *     },
 *     itemOperations={
 *         "get"={
 *             "method"="GET"
 *         },
 *     },
 *     collectionOperations={
 *         "get"={
 *             "method"="GET"
 *         },
 *         "post"={
 *             "method"="POST"
 *         },
 *     },
 * )
 * @ApiFilter(SearchFilter::class, properties={"creator": "exact"})
 */
class Ticket
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
     * @ORM\Column(type="string")
     *
     * @Groups({
     *     "ticket_read",
     *     "ticket_write",
     * })
     */
    private $title;

    /**
     * @ORM\Column(type="string")
     *
     * @Groups({
     *     "ticket_read",
     *     "ticket_write",
     * })
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="tickets")
     *
     * @Groups({
     *     "ticket_read",
     * })
     */
    private $creator;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="tickets")
     */
    private $acceptedBy;

    /**
     * @ORM\Embedded(class="Address")
     *
     * @Groups({
     *     "ticket_read",
     *     "ticket_write",
     * })
     */
    private $address;

    /**
     * @ORM\Column(type="string")
     *
     * @Groups({
     *     "ticket_read",
     *     "ticket_write",
     * })
     */
    private $status;

    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="ticket")
     *
     * @Groups({
     *     "ticket_read",
     * })
     */
    private $comments;

    /**
     * Ticket constructor.
     */
    public function __construct()
    {
        $this->setStatus(TicketStatus::OPEN);

        $this->address  = new Address();
        $this->comments = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getTitle();
    }

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     * @return Ticket
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     * @return Ticket
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreator()
    {
        return $this->creator;
    }

    /**
     * @param mixed $creator
     * @return Ticket
     */
    public function setCreator($creator)
    {
        $this->creator = $creator;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAcceptedBy()
    {
        return $this->acceptedBy;
    }

    /**
     * @param mixed $acceptedBy
     * @return Ticket
     */
    public function setAcceptedBy($acceptedBy)
    {
        $this->acceptedBy = $acceptedBy;

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
     * @return Ticket
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     * @return Ticket
     */
    public function setStatus($status)
    {
        $this->status = $status;

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
}
