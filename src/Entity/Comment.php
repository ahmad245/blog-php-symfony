<?php

namespace App\Entity;
use ApiPlatform\Core\Annotation\ApiSubresource;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 *@ApiResource(
 * attributes={
 *          "order"={"date":"DESC"},
 *          "pagination_client_enabled"=true,
 *         "pagination_client_items_per_page"=true,
 *         "maximum_items_per_page"=30
 *      },
 *   itemOperations={
 *             "get"={ "normalization_context"=
 *                     {"groups"={"get-comment-with-author"}}},
 *             "put"={"access_control"="is_granted('ROLE_EDITOR') or (is_granted('ROLE_COMMENTATOR') and object.getAuthor() == user)"},
 *             "delete"={
 *                "access_control"="is_granted('ROLE_EDITOR') or (is_granted('ROLE_COMMENTATOR') and object.getAuthor() == user)",
 *                 {"groups"={"delete-with-author"}}
 *                 }
 *            
 *  },
 *   collectionOperations={
 *       "get" ,
 *       "post"={
 *         "access_control"="is_granted('ROLE_COMMENTATOR')" ,
 *         "normalization_context"={
 *               "groups"={"get-comment-with-author"}
 *           }
 *     },
 *       "api_posts_comments_get_subresource"={
 *                  "normalization_context"=
 *                     {"groups"={"get-comment-with-author"}}
 *                }
 *
 *      },
 *     denormalizationContext={"groups"={"post"}},
 *     normalizationContext={
 *               "groups"={"get-comment-with-author"}
 *           }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\CommentRepository")
 */
class Comment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"get-comment-with-author","delete-with-author"})
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     * @Groups({"post","get-comment-with-author"})
     * @Assert\NotBlank()
     *  @Assert\Length(min=3,max=3000)
     */
    private $message;
    
    /**
     * @ORM\Column(type="datetime")
     * @Groups({"get-comment-with-author"})
     */
    private $date;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User",inversedBy="comments")
     *@ORM\JoinColumn(nullable=false)
     * @Groups({"get-comment-with-author"})
     */
    private $author;
    /**
     *@ORM\ManyToOne(targetEntity="App\Entity\Post",inversedBy="comments")
     *@ORM\JoinColumn(nullable=true,onDelete="SET NULL")
     *@Groups({"post"})
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
    public function getAuthor():?User
    {
        return $this->author;
    }

    public function setAuthor(User $author): self
    {
        $this->author = $author;

        return $this;
    }
    public function getPost():?Post
    {
        return $this->post;
    }
    public function setPost(Post $post):self
    {
         $this->post=$post;
         return $this;
    }
    public function __toString()
    {
        return substr($this->message, 0, 20) . '...';
    }
}
