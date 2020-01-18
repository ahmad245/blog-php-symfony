<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\CommentRepository")
 */
class Comment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $message;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User",inversedBy="comments")
     *@ORM\JoinColumn(nullable=false)
     */
    private $author;
    /**
     *@ORM\ManyToOne(targetEntity="App\Entity\Post",inversedBy="comments")
     *@ORM\JoinColumn(nullable=false)
     */
    private $post;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }
    /**
     * @return User
     */
    public function getAuthor():User
    {
        return $this->author;
    }

    public function setAuthor(User $author): self
    {
        $this->author = $author;

        return $this;
    }
    public function getPost():Post
    {
        return $this->post;
    }
    public function setPost(Post $post):self
    {
         $this->post=$post;
         return $this;
    }
}
