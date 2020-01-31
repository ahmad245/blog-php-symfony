<?php
// api/src/Controller/CreateMediaObjectAction.php

namespace App\Controller;

use App\Entity\Image;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class CreateMediaObjectAction
{
    public function __invoke(Request $request): Image
    {
        $uploadedFile = $request->files->get('file');
        if (!$uploadedFile) {
            throw new BadRequestHttpException('"file" is required');
        }

        $mediaObject = new Image();
        $mediaObject->file = $uploadedFile;

        return $mediaObject;
    }
}