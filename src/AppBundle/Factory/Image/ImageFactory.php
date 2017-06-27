<?php

namespace AppBundle\Factory\Image;


use Imagine\Exception\Exception;
use Psr\Log\InvalidArgumentException;
use Symfony\Component\HttpFoundation\File\File;

class ImageFactory
{

    public function upload($body)
    {
        $imageName = time() . '.png';

        $data = base64_decode($body);

        $image = @imagecreatefromstring($data); // @wyciszenie Warning'a w razie błędnego $data, aby
        //elegancko rzucić wyjątkiem "Unsupported image type or corrupted image"

        if ($image != false) {
            header('Content-Type: image/png');
            $filePath = $_SERVER['DOCUMENT_ROOT'] . '/uploads/images/' . $imageName;
            imagepng($image, $filePath);
            imagedestroy($image);
        } else {
            throw new \InvalidArgumentException("Unsupported image type or corrupted image");
        }

        return $imageName;
    }

}
