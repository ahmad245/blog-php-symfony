<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PostRepository")
 * @ApiResource()
 */
class Post
{
    public function __construct()
    {
        $this->comments=new ArrayCollection();
    }
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="boolean")
     */
    private $publish;

   /**
    * @ORM\ManyToOne(targetEntity="App\Entity\User",inversedBy="posts") 
    *@ORM\JoinColumn(nullable=false)
    */
    private $author;

    /**
     * @ORM\Column(type="string",length=255,nullable=true)
     */
    private $slug;
    /**
     * @ORM\Column(type="datetime")
     */
    private $date;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment",mappedBy="post")
     * @ORM\JoinColumn(nullable=false)
     */
    private $comments;

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

    public function getPublish(): ?bool
    {
        return $this->publish;
    }

    public function setPublish(bool $publish): self
    {
        $this->publish = $publish;

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
    public function getSlug(){
        return $this->slug;
    }
    public function setSlug($slug):self
    {
        $this->slug=$slug;
        return $this;
    }
    function getDate(): ?\DateTimeInterface
    {
      return $this->date;
    }
    function setDate($date): self{
        $this->date=$date;
       return $this;       
    }

        /**
     * @return Collection
     */
    public function getComments():Collection
    {
        return $this->comments;
    }

    public function setComment(Comment $comments): self
    {
        $this->comments = $comments;

        return $this;
    }
}
