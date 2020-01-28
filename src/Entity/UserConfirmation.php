<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     collectionOperations={
 *  "get"={
*              "method"="GET",
*              "path"="/users/confirm",
*          },
 *         "post"={
 *          "method"="POST",
 *             "path"="/users/confirm"
 *         }
 *     },
 *     itemOperations={}
 * )
 */
class UserConfirmation
{
    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=30, max=30)
     */
    public $confirmationToken;
}
