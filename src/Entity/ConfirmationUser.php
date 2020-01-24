<?php
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ApiResource(
 *   
 *  itemOperations={},
 *  collectionOperations={
 *    "post"={
 *         "path"="/users/confirm"
 *        }
 *  
 *   }
 * )
 */
class ConfirmationUser{
  
    /**
     * @AsserT\NotBlank()
     * @Assert\Length(min=30,max=30)
     *
     */
    public $confirmationToken;
}