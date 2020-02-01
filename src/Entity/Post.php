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
 *       "author.firstName":"partial"
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
 *     "whitelist":{"id","title","content","author","slug"} 
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
 *                       {"groups"={"get-post-with-author"}}
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
     * @ORM\JoinColumn(nullable=false,onDelete="SET NULL")
     * @ApiSubresource()
     * @Groups({"get-post-with-author"})
     */
    private $comments;
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

    public function __construct()
    {
        $this->comments=new ArrayCollection();
        $this->images = new ArrayCollection();
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