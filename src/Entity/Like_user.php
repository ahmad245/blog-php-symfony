<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiFilter;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Core\Serializer\Filter\PropertyFilter;


/**
 * @ORM\Entity()
 * @ApiResource(
 * 
 *  itemOperations={
 *                    "get"={  "normalization_context"={"groups"={"get-like-with-author"}}},
 *                 "put"={"access_control"="is_granted('ROLE_EDITOR') or (is_granted('ROLE_COMMENTATOR') and object.getAuthor() == user)"},
 *                 "delete"={ 
 *                    "method"="DELETE",
 *                  "access_control"="is_granted('ROLE_EDITOR') or (is_granted('ROLE_COMMENTATOR') and object.getAuthor() == user)",
 *                        {"groups"={"delete-like-author"}},
 *                       
 *                     }
 *     },
 *  collectionOperations={
 *               
 *              "get" ,
 *             "post"={
 *                 "access_control"="is_granted('ROLE_COMMENTATOR')" ,
 *                 "normalization_context"={
 *                 "groups"={"get-like-with-author"}
 *                 },
 *             "api_posts_likes_get_subresource"={
 *                    "normalization_context"=
 *                     {"groups"={"get-like-with-author"}}
 *                },
 *            
 *     }
 *         
 *     
 * },
 *    
 * 
 *  denormalizationContext={"groups"={"post"}},
 *     normalizationContext={
 *               "groups"={"get-like-with-author"}
 *           }
 * )
 */
class Like_user
{
     
 /**
   * @ORM\Id()
     *@ORM\ManyToOne(targetEntity="App\Entity\Post",inversedBy="likes")
     *@ORM\JoinColumn()
     *@Groups({"post","get-like-with-author","delete-like-author"})
     */
    private $post;

    /**
      * @ORM\Id()
     *@ORM\ManyToOne(targetEntity="App\Entity\User",inversedBy="likes")
     *@ORM\JoinColumn()
     *@Groups({"post","get-like-with-author","delete-like-author"})
     */
    private $author;

    // public function getId(): ?int
    // {
    //     return $this->id;
    // }

    public function getPost():?Post
    {
        return $this->post;
    }
    public function setPost(Post $post):self
    {
         $this->post=$post;
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
    public function __toString()
    {
        return $this->author->getFirstName() . ':' . $this->post->getTitle();
    }
}


// /**
    
//      * @ORM\GeneratedValue()
//      * @ORM\Column(type="integer")
//      * @Groups({"get-post-with-author","get-blogType","get-like-with-author"})
//      */
//     private $id;
// "api_posts_likes_get_subresource"={
//     *                  "normalization_context"=
//     *                     {"groups"={"get-comment-with-author"}}
//     *                }
 
// *     collectionOperations={
//     *         "post"={
//     *        "denormalization_context"={"groups"={"post"}},
//     *        "normalization_context"={"groups"={"get"}}
//     *         
//     *       },
//     *        "get"={
//     *                 
//     *                 "normalization_context"=
//     *                 {"groups"={"get"}} 
//     *               } 
//     *     },
//     *     itemOperations={
//     *          "get"={
//     *             "access_control"="is_granted('IS_AUTHENTICATED_FULLY')",
//     *             "normalization_context"={"groups"={"get"}}
//     *               },
//     *         "post"={
//     *             "access_control"="is_granted('IS_AUTHENTICATED_FULLY')",
//     *             "normalization_context"={"groups"={"post"}}
//     *               },
//     *        "put"={
//     *             "access_control"="is_granted('IS_AUTHENTICATED_FULLY')",
//     *             "normalization_context"={"groups"={"put"}}
//     *             },
//     *          "delete"={
//     *             "access_control"="is_granted('IS_AUTHENTICATED_FULLY')",
//     *             "normalization_context"={"groups"={"delete"}}
//     *            },