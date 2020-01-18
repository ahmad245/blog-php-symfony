<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ApiResource(
 *   normalizationContext={
 *     "groups"={"read"}
 *   }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields={"email"})
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read"})
     * @Assert\NotBlank()
     * @Assert\Email()
     * @Assert\Length(min=3,max=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=50)
     * @Groups({"read"})
     * @Assert\NotBlank()
     * @Assert\Length(min=3,max=50)
     */

    private $firstName;

    /**
     * @ORM\Column(type="string", length=50)
     * @Groups({"read"})
     * @Assert\NotBlank()
     * @Assert\Length(min=3,max=50)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Regex(
     *   pattern="/(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9]).{7,}/",
     *   message="Password must be seven charachters and contain at least one digit,one uppercase and one lowercase"
     * )
     */
    private $password;
     
    /**
     *  @Assert\NotBlank()
     *  @Assert\Expression(
     *    "this.getPassword()===this.getConfirmPassword()",
     *     message="Passwords does not match"
     * )
     */
    private $confirmPassword;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Post",mappedBy="author")
     * @Groups({"read"})
     */
    private $posts;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment",mappedBy="author")
     * @Groups({"read"})
     */
    private $comments;

    public function __construct()
    {
        $this->posts=new ArrayCollection();
        $this->comments=new ArrayCollection();
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
    
        public function getRoles()
         {
             return ['ROLE_USER'];
         }
    
        

        
         public function getSalt(){
          return null;
         }
     
         
         public function getUsername(){
           
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

    

   
}

// {
// 	"email":"ahmad@gmail.com",
// 	"first_name":"ahmad",
// 	"last_name":"almasri",
// 	 "password":"123456"
// }