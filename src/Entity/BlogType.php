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
 * @ApiFilter(
 *     SearchFilter::class,
 *     properties={
 *      "id":"exact",
 *       "name":"partial"
 *      
 *     }
 *    
 *   )
 * 
 * @ApiFilter(
 *   OrderFilter::class,
 * properties={
 *       "id",
 *      "name",
 *      
 *    }
 * )
 * @ApiFilter(
 *   PropertyFilter::class,
 *   arguments={
 *    "parameterName":"properties",
 *     "overrideDefaultProperties":false,
 *     "whitelist":{"id","name"} 
 *  }
 * )
 * @ApiFilter(RangeFilter::class, properties={"id"})
 * @ApiResource(
 *   
 *   attributes={
 *          
 *          "order"={"name":"DESC"},
 *          "pagination_client_enabled"=false,
 *         "pagination_client_items_per_page"=false,
 *        
 *      },
 *   itemOperations={
 *             "get"={"normalization_context"=
 *                       {"groups"={"get-blogType"}}
 *                },
 *             "put"={
 *                "access_control"="is_granted('ROLE_EDITOR') or (is_granted('ROLE_WRITER') and object.getAuthor() == user) "
 *                    }
 *             },
 *   collectionOperations={
 *     "get"={"normalization_context"=
 *                       {"groups"={"get-blogType"}}
 *                } ,
 *       "post"={
 *             "access_control"="is_granted('ROLE_WRITER')"
 *           }   
 *      },
 *     denormalizationContext={"groups"={"post"}} 
 
 * )
 * @ORM\Entity(repositoryClass="App\Repository\BlogTypeRepository")
 */
class BlogType
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"get-blogType","get-post-with-author"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"get-blogType","post"})
     */
    private $name;
   /**
     * @ORM\OneToMany(targetEntity="App\Entity\Post",mappedBy="blogType")
     * @ORM\JoinColumn(nullable=false)
     * @ApiSubresource()
     * @Groups({"get-blogType"})
     */
    private $posts;
    public function __construct()
    {
        $this->posts=new ArrayCollection();
        
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
    
    public function getPosts():Collection
    {
        return $this->posts;
    }

    public function setComment(Post $posts): self
    {
        $this->posts = $posts;

        return $this;
    }
}

// "maximum_items_per_page"=300,
// *         "pagination_partial"=true