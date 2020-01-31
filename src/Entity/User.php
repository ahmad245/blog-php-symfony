<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use App\Controller\ResetPasswordAction;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Serializer\Filter\PropertyFilter;

/**
 * @ApiFilter(
 *     SearchFilter::class,
 *     properties={
 *      "id":"exact",
 *       "firstName":"partial",
 *       "lastName":"partial",
 *       "email":"exact"
 * 
 *     }
 *    
 *   )
 * 
 * @ApiFilter(
 *   OrderFilter::class,
 * properties={
 *       "id",
 *       "firstName",
 *        "lastName"
 *    }
 * )
 * @ApiFilter(
 *   PropertyFilter::class,
 *   arguments={
 *    "parameterName":"properties",
 *     "overrideDefaultProperties":false,
 *     "whitelist":{"id","firstName","lastName","email","roles"} 
 *  }
 * )
 * @ApiResource(
 *   attributes={
 *          "pagination_client_enabled"=true,
 *         "pagination_client_items_per_page"=true,
 *         "maximum_items_per_page"=30
 *        
 *      },
 *   itemOperations={
 *      "get"={
 *             "access_control"="is_granted('IS_AUTHENTICATED_FULLY')",
 *             "normalization_context"={"groups"={"get"}}
 *      },
 *     "put"={
 *                "access_control"=" is_granted('ROLE_SUPERADMIN') or ( is_granted('IS_AUTHENTICATED_FULLY') and object == user) " ,
 *               "denormalization_context"={"groups"={"put"}},
 *              "normalization_context"={"groups"={"get"}}
 *               },
 *       "delete"={
 *                "access_control"=" is_granted('ROLE_SUPERADMIN') or ( is_granted('IS_AUTHENTICATED_FULLY') and object == user) " ,
 *               "denormalization_context"={"groups"={"delete-user"}},
 *              "normalization_context"={"groups"={"delete-user"}}
 *               },
 *      "put-reset-password"={
 *             "access_control"="is_granted('IS_AUTHENTICATED_FULLY') and object == user",
 *             "method"="PUT",
 *             "path"="/users/{id}/reset-password",
 *             "controller"=ResetPasswordAction::class,
 *             "denormalization_context"={
 *                 "groups"={"put-reset-password"}
 *             },
 *             "validation_groups"={"put-reset-password"}
 *         },
 *     "put-roles"={
 *             "access_control"="is_granted('ROLE_SUPERADMIN') or ( is_granted('IS_AUTHENTICATED_FULLY') and object == user) ",
 *             "method"="PUT",
 *             "path"="/users/{id}/roles",
 *             "denormalization_context"={
 *                 "groups"={"put-roles"}
 *             },
 *             "validation_groups"={"put-roles"}
 *         }
 *    },
 *     collectionOperations={
 *        "post"={
 *        "denormalization_context"={"groups"={"post"}},
 *        "normalization_context"={"groups"={"get"}},
 *         "validation_groups"={"post"}
 *       },
 *        "get"={
 *           "normalization_context"=
 *                 {"groups"={"get"}} 
 *               } 
        
 *     }
 *     
 *
 * )
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity("email", groups={"post"})
 */
class User implements UserInterface
{
    const ROLE_COMMENTATOR = 'ROLE_COMMENTATOR';
    const ROLE_WRITER = 'ROLE_WRITER';
    const ROLE_EDITOR = 'ROLE_EDITOR';
    const ROLE_ADMIN = 'ROLE_ADMIN';
    const ROLE_SUPERADMIN = 'ROLE_SUPERADMIN';

    const DEFAULT_ROLES =[self::ROLE_COMMENTATOR];
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"get", "get-post-with-author", "get-comment-with-author","get-blogType","delete-user"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"get","post", "put", "get-comment-with-author", "get-post-with-author","get-blogType"})
     * @Assert\NotBlank(groups={"post"})
     * @Assert\Email(groups={"post"})
     * @Assert\Length(min=3,max=255,groups={"post","get","delete-user"})
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=50)
     * @Groups({"get","post","put","get-post-with-author","get-comment-with-author","get-blogType","delete-user"})
     * @Assert\NotBlank(groups={"post"})
     * @Assert\Length(min=3,max=50,groups={"post"})
     */

    private $firstName;

    /**
     * @ORM\Column(type="string", length=50)
     * @Groups({"get","post","put","get-comment-with-author","get-post-with-author","get-blogType","delete-user"})
     * @Assert\NotBlank(groups={"post"})
     * @Assert\Length(min=3,max=50,groups={"post"})
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"post","put","delete-user"})
     * @Assert\NotBlank(groups={"post"})
     * @Assert\Regex(
     *   pattern="/(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9]).{7,}/",
     *   message="Password must be seven charachters and contain at least one digit,one uppercase and one lowercase",
     *    groups={"post"}
     * )
     */
    private $password;
     
    /**
     * @Groups({"post"})
     *  @Assert\NotBlank(groups={"post"})
     *  @Assert\Expression(
     *    "this.getPassword()===this.getConfirmPassword()",
     *     message="Passwords does not match",
     *    groups={"post"}
     * )
     */
    private $confirmPassword;
    


    /**
     * @Groups({"put-reset-password"})
     * @Assert\NotBlank(groups={"put-reset-password"})
     * @Assert\Regex(
     *     pattern="/(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9]).{7,}/",
     *     message="Password must be seven characters long and contain at least one digit, one upper case letter and one lower case letter",
     *     groups={"put-reset-password"}
     * )
     */
    private $newPassword;

    /**
     * @Groups({"put-reset-password"})
     * @Assert\NotBlank(groups={"put-reset-password"})
     * @Assert\Expression(
     *     "this.getNewPassword() === this.getNewRetypedPassword()",
     *     message="Passwords does not match",
     *     groups={"put-reset-password"}
     * )
     */
    private $newRetypedPassword;

    /**
     * @Groups({"put-reset-password"})
     * @Assert\NotBlank(groups={"put-reset-password"})
     * @UserPassword(groups={"put-reset-password"})
     */
    private $oldPassword;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Post",mappedBy="author")
     *  @ApiSubresource()
     * @Groups({"get"})
     */
    private $posts;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment",mappedBy="author")
     * @Groups({"get"})
     */
    private $comments;
    private $username;


    /**
     * @ORM\Column(type="array", length=200)
     *  @Groups({"get-admin","get-owner","put-roles","delete-user"})
     */
    private $roles;
     /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $passwordChangeDate;

    /**
     * @ORM\Column(type="boolean")
     */
    private $enabled;

    /**
     * @ORM\Column(type="string", length=40, nullable=true)
     */
    
    private $confirmationToken;

    public function __construct()
    {
        $this->posts=new ArrayCollection();
        $this->comments=new ArrayCollection();
        $this->roles = self::DEFAULT_ROLES;
        $this->enabled = false;
        $this->confirmationToken = null;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
      
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }
    /**
     * @return Collection
     */
    public function getPosts():Collection
    {
        return $this->posts;
    }

     /**
     * @return Collection
     */
    public function getComments():Collection
    {
        return $this->comments;
    }

    // Returns the roles granted to the user.


    public function getRoles():array
    {
       
        return  $this->roles;
    }

    public function setRoles(array $roles)
    {
     
       $this->roles=$roles;
      

    }




    public function getSalt(){
          return null;
         }
     
         
         public function getUsername(){
           return $this->email;
         }
      public function setUsername(string $email):self
      {
           $this->username=$email;
           return $this;
      }
       
         public function eraseCredentials(){

         }

         public function getConfirmPassword(){
             return $this->confirmPassword;
         }
         public function setConfirmPassword(string $confirmPassword): self
         {
             $this->confirmPassword = $confirmPassword;
     
             return $this;
         }
         public function getNewPassword(): ?string
         {
             return $this->newPassword;
         }
     
         public function setNewPassword($newPassword): void
         {
             $this->newPassword = $newPassword;
         }
     
         public function getNewRetypedPassword(): ?string
         {
             return $this->newRetypedPassword;
         }
     
         public function setNewRetypedPassword($newRetypedPassword): void
         {
             $this->newRetypedPassword = $newRetypedPassword;
         }
     
         public function getOldPassword(): ?string
         {
             return $this->oldPassword;
         }
     
         public function setOldPassword($oldPassword): void
         {
             $this->oldPassword = $oldPassword;
         }
     
         public function getPasswordChangeDate()
         {
             return $this->passwordChangeDate;
         }
     
         public function setPasswordChangeDate($passwordChangeDate): void
         {
             $this->passwordChangeDate = $passwordChangeDate;
         }
     
         public function getEnabled()
         {
             return $this->enabled;
         }
     
         public function setEnabled($enabled): void
         {
             $this->enabled = $enabled;
         }
     
         public function getConfirmationToken()
         {
             
             return $this->confirmationToken;
         }
     
         public function setConfirmationToken($confirmationToken)
         {
            
             $this->confirmationToken = $confirmationToken;
             return $this;
         }
         public function __toString(): string
         {
             return $this->firstName;
         }

    

   
}
//// for log in 
// {
// 	"username":"ahmad@gmail.com",
//
// 	 "password":"ahadAd2!"
//
// }

// token
// eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE1Nzk0MzU0MTEsImV4cCI6MTU3OTQzOTAxMSwicm9sZXMiOlsiUk9MRV9VU0VSIl0sImVtYWlsIjoiYWhtYWRAZ21haWwuY29tIn0.Ix7_LlK6nrcIz0IK50Qbhej12dRU7NuISJSPKc6ZrnqDmcJy9swukjD1DeeblnLqBwqCGbxDaZpDxn9QE87v8UeB81Vk6fMOKFA4IjCNmbnDsS4gzhOUnQzYoqMqaQfl6RC4dq5Dlc100f3VCe39QRPeoR_FV6l81c6hbXlWu0jg4Oaz3jyIVoyCWxwgcDDucc08r17TedMv5J3V5_s9vP8VL88ai_V1LhMdCLspQS64h1qnYK3SxNBKXNwGLwaO4ONpfWyJMDPDW8LxPqCsoAU-6YxmIbNhDZm5hphJEOu1zQbRp92O2MkZUy6WVyuxkyGNR8UjNxopNCREIVxNrlcFDtjDROWdr_uDz18zb6k5XDhTCNqVipI0FXrdwxtWeNnPEe7K-U_kAMh7Yml3-b03Nd0NhDW8OfuohLdhr20K2M0QND9-ZX_HsRzX-sUM_u9g-bS4TJZo5xA0Kx6bYE-Jk28JVkkAMz7TScwklFhvxaKjWRRcAI5M2R-AORjQKcExCKM8LIJNiUnbPqom0A3PRTYPCGfaFv9xR2OoXEawwufrCiwpIaue5dNGSHCn010i1ckmasTksA24Gr1WLKAIpOPNgTju1WR_CKQ7Hi2-lRJha363zvQrUOxY05xJgmD27qozRjYTHZQ-VNltbzLDS_ZenuZe5Zxy3srHHik
// {
// 	"email":"ahmad@gmail.com",
// 	"first_name":"ahmad",
// 	"last_name":"almasri",
// 	 "password":"123456"
// }
// {
// 	"email":"ahmad@gmail.com",
// 	"firstName":"ahmad",
// 	"lastName":"almasri",
// 	 "password":"ahadAd2!",
// 	 "confirmPassword":"ahadAd2!"
// }

// {
// 	"newPassword":"ahmad123#",
// 	"newRetypedPassword":"ahmad123#",
// 	"oldPassword":"secret123#"
	

// }