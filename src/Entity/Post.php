<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Serializer\Filter\PropertyFilter;
/**
 * @ORM\Entity(repositoryClass="App\Repository\PostRepository")
 * @ApiFilter(
 *     SearchFilter::class,
 *     properties={
 *      "id":"exact",
 *       "title":"partial",
 *       "content":"partial",
 *       "author":"exact",
 *       "author.firstName":"partial",
 *       "images.id":"exact",
 *       "tags.name":"exact"
 * 
 *     }
 *    
 *   )
 * @ApiFilter(
 *   DateFilter::class,
 *   properties={
 *      "date"
 *    }
 *  )
 * @ApiFilter(
 *   OrderFilter::class,
 * properties={
 *       "id",
 *      "date",
 *       "title"
 *    }
 * )
 * @ApiFilter(
 *   PropertyFilter::class,
 *   arguments={
 *    "parameterName":"properties",
 *     "overrideDefaultProperties":false,
 *     "whitelist":{"id","title","content","author","slug","tags.id"} 
 *  }
 * )
 * @ApiResource(
 *   
 *   attributes={
 *          "order"={"date":"DESC"},
 *          "pagination_client_enabled"=true,
 *         "pagination_client_items_per_page"=true,
 *         "maximum_items_per_page"=30
 *        
 *      },
 *   itemOperations={
 *             "get"={"normalization_context"=
 *                       {"groups"={"get-post-with-author"}}
 *                },
 *             "put"={
 *                "access_control"="is_granted('ROLE_EDITOR') or (is_granted('ROLE_WRITER') and object.getAuthor() == user) "
 *                    },
 *             "delete"={
 *              "access_control"="is_granted('ROLE_EDITOR') or (is_granted('ROLE_WRITER') and object.getAuthor() == user) "
 *               }
 *             },
 *   collectionOperations={
 *     "get"={"normalization_context"=
 *                       {"groups"={"get-post-with-author","get-tag"}}
 *                } ,
 *       "post"={
 *             "access_control"="is_granted('ROLE_WRITER')"
 *           },
 *       "api_blog_types_posts_get_subresource"={
 *           "normalization_context"=
 *                     {"groups"={"get-blogType"}}
 *                }
 *            
 *      },
 *     denormalizationContext={"groups"={"post"}},
 *   normalizationContext={
 *               "groups"={"get-blogType"}
 *           }
 * )
 */
class Post
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"get-post-with-author","get-blogType"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"post","get-post-with-author","get-blogType"})
     * @Assert\NotBlank()
     * @Assert\Length(min=3,max=50)
     */
    private $title;

    /**
     * @Groups({"post","get-post-with-author","get-blogType"})
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    private $content;

    /**
     * @Groups({"post","get-post-with-author","get-blogType"})
     * @ORM\Column(type="boolean")
     * @Assert\NotBlank()
     */
    private $publish;

   /**
    * @ORM\ManyToOne(targetEntity="App\Entity\User",inversedBy="posts") 
    *@ORM\JoinColumn(nullable=false)
    * @Groups({"get-post-with-author","get-blogType"})
  
    */
    private $author;

    /**
     * @Groups({"post","get-blog-post-with-author","get-blogType"})
     * @ORM\Column(type="string",length=255,nullable=true)
     * @Groups({"get-post-with-author","get-blogType"})
     */
    private $slug;
    /**
     * @ORM\Column(type="datetime")
     * @Groups({"get-post-with-author","get-blogType"})
     */
    private $date;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment",mappedBy="post")
     * @ORM\JoinColumn(nullable=true,onDelete="SET NULL")
     * @ApiSubresource()
     * @Groups({"get-post-with-author"})
     */
    private $comments;

     /**
     * @ORM\OneToMany(targetEntity="App\Entity\Like_user",mappedBy="post")
     * @ORM\JoinColumn(nullable=true,onDelete="SET NULL")
     * @ApiSubresource()
     * @Groups({"get-post-with-author","delete-with-author","get-blogType"})
     */
    private $likes;
      /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Image")
     * @ORM\JoinTable()
     * @ApiSubresource()
     * @Groups({"post", "get-post-with-author","get-blogType"})
     */
    private $images;
      /**
     *@ORM\ManyToOne(targetEntity="App\Entity\BlogType",inversedBy="posts")
     *@ORM\JoinColumn(nullable=false)
     *@Groups({"post","get-post-with-author"})
     */
    private $blogType;

      /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Tag")
     * @ORM\JoinTable()
     * @ApiSubresource()
     * @Groups({"post", "get-post-with-author","get-blogType","get-tag"})
     */
    private $tags;
      /**
     * @ORM\Column(type="string", length=255,nullable=true)
     * @Groups({"post","get-post-with-author","get-blogType"})
     * @Assert\Length(min=3,max=255)
     */
    private $description;

    public function __construct()
    {
        $this->comments=new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->tags=new ArrayCollection();
        $this->likes=new ArrayCollection();
       
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
    public function getAuthor():?User
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

    public function getLikes():Collection
    { 
        return $this->likes;
    }

    public function setLike(Like_user $like): self
    {
        
        $this->likes = $like;
       die($like);

        return $this;
    }

    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image)
    {
        $this->images->add($image);
    }

    public function removeImage(Image $image)
    {
        $this->images->removeElement($image);
    }
    public function __toString()
    {
        return $this->title;
    }

    public function getBlogType():?BlogType
    {
        return $this->blogType;
    }
    public function setBlogType(BlogType $blogType):self
    {
         $this->blogType=$blogType;
         return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

   

    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag)
    {
        $this->tags->add($tag);
    }

    public function removeTag(Tag $tag)
    {
        $this->tags->removeElement($tag);
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

// "pagination_partial"=true

// nullable=true

/**
 * @ORM\ManyToMany(targetEntity="App\Entity\Image", inversedBy="users")
 */
// private $likes;

// public function __construct()
// {
//     $this->images = new ArrayCollection();
//     $this->likes = new ArrayCollection();
// }
//  ....
//  /**
//  * @return Collection|Image[]
//  */
// public function getLikes(): Collection
// {
//     return $this->likes;
// }

// public function addLike(Image $like): self
// {
//     if (!$this->likes->contains($like)) {
//         $this->likes[] = $like;
//     }

//     return $this;
// }

// public function removeLike(Image $like): self
// {
//     if ($this->likes->contains($like)) {
//         $this->likes->removeElement($like);
//     }

//     return $this;
// }


// /**
//  * @ORM\ManyToMany(targetEntity="App\Entity\User", mappedBy="likes")
//  */
// private $users;

// public function __construct()
// {
//     $this->likes = new ArrayCollection();
//     $this->users = new ArrayCollection();
// }
// ...
// /**
//  * @return Collection|User[]
//  */
// public function getUsers(): Collection
// {
//     return $this->users;
// }

// public function addUser(User $user): self
// {
//     if (!$this->users->contains($user)) {
//         $this->users[] = $user;
//         $user->addLike($this);
//     }

//     return $this;
// }

// public function removeUser(User $user): self
// {
//     if ($this->users->contains($user)) {
//         $this->users->removeElement($user);
//         $user->removeLike($this);
//     }

//     return $this;
// }