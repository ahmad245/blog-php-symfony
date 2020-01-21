<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PostRepository")
 * @ApiResource(
 *   itemOperations={
 *             "get"={"normalization_context"=
 *                       {"groups"={"get-post-with-author"}}
 *                },
 *             "put"={
 *                "access_control"="is_granted('ROLE_EDITOR') or (is_granted('ROLE_WRITER') and object.getAuthor() == user) "
 *                    }
 *             },
 *   collectionOperations={
 *     "get" ,
 *       "post"={
 *             "access_control"="is_granted('ROLE_WRITER')"
 *           }   
 *      },
 *     denormalizationContext={"groups"={"post"}}
 * )
 */
class Post
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"get-post-with-author"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"post","get-post-with-author"})
     * @Assert\NotBlank()
     * @Assert\Length(min=3,max=50)
     */
    private $title;

    /**
     * @Groups({"post","get-post-with-author"})
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    private $content;

    /**
     * @Groups({"post","get-post-with-author"})
     * @ORM\Column(type="boolean")
     * @Assert\NotBlank()
     */
    private $publish;

   /**
    * @ORM\ManyToOne(targetEntity="App\Entity\User",inversedBy="posts") 
    *@ORM\JoinColumn(nullable=false)
    * @Groups({"get-post-with-author"})
  
    */
    private $author;

    /**
     * @Groups({"post","get-blog-post-with-author"})
     * @ORM\Column(type="string",length=255,nullable=true)
     */
    private $slug;
    /**
     * @ORM\Column(type="datetime")
     * @Groups({"get-post-with-author"})
     */
    private $date;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment",mappedBy="post")
     * @ORM\JoinColumn(nullable=false)
     * @ApiSubresource()
     * @Groups({"get-post-with-author"})
     */
    private $comments;

    public function __construct()
    {
        $this->comments=new ArrayCollection();
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


// {
// 	"title":"first post for online forma prof",
// 	"slug":"first post",
// 	"publish":true,
// 	"date":"2020-01-01 18:00:00",
// 	"content":"hi formaprof",
// 	"author_id":9

// }