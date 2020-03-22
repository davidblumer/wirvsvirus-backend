<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommentRepository")
 */
class Comment
{
    /**
     * @ORM\Id
     * @ORM\Column(type="guid")
     * @ORM\GeneratedValue(strategy="UUID")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="comments")
     *
     * @Groups({
     *     "ticket_read",
     * })
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Ticket", inversedBy="comments")
     */
    private $ticket;

    /**
     * @ORM\Column(type="string", length=10000)
     *
     * @Groups({
     *     "ticket_read",
     * })
     */
    private $text;

    /**
     * @ORM\OneToOne(targetEntity="File")
     *
     * @Groups({
     *     "ticket_read",
     * })
     */
    private $file;

    public function __toString()
    {
        return $this->getText();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     * @return Comment
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTicket()
    {
        return $this->ticket;
    }

    /**
     * @param mixed $ticket
     * @return Comment
     */
    public function setTicket($ticket)
    {
        $this->ticket = $ticket;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     * @return Comment
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param mixed $file
     * @return Comment
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }
}
