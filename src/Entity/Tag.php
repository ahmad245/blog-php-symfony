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
 *           "pagination_enabled"=false
 *         
 *        
 *      },
 *   itemOperations={
 *             "get"={"normalization_context"=
 *                       {"groups"={"get-blogType","get-tag"}}
 *                },
 *             "put"={
 *                "access_control"="is_granted('ROLE_EDITOR') or (is_granted('ROLE_WRITER') and object.getAuthor() == user) "
 *                    }
 *             },
 *   collectionOperations={
 *     "get"={"normalization_context"=
 *                       {"groups"={"get-blogType","get-tag"}}
 *                } ,
 *       "post"={
 *             "access_control"="is_granted('ROLE_WRITER')"
 *           }  ,
 *         "api_posts_tags_get_subresource"={
 *           "normalization_context"=
 *                     {"groups"={"get-tag"}}
 *                } 
 *      },
 *     denormalizationContext={"groups"={"post"}} 
 
 * )
 * @ORM\Entity()
 */
class Tag
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"get-blogType","get-post-with-author","get-tag"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"get-blogType","post","get-tag","get-post-with-author"})
     */
    private $name;
  
   

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
    
 
    public function __toString()
    {
        return $this->name;
    }
}

// "maximum_items_per_page"=300,
//        "pagination_partial"=true

//  @Groups({"get-blogType"})


// "pagination_client_enabled"=true,
// *         "pagination_client_items_per_page"=true,
// *         "maximum_items_per_page"=100