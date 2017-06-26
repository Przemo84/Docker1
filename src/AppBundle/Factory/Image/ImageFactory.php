<?php

namespace AppBundle\Factory\Image;


class ImageFactory
{

    public function upload($body)
    {
        $imageName = time().'.png';

        $data= base64_decode($body);

        $image= imagecreatefromstring($data);

        if ($image != false) {
            header('Content-Type: image/png');
            $filePath = $_SERVER['DOCUMENT_ROOT'] .'/uploads/images/'.$imageName;
            imagepng($image,$filePath);
            imagedestroy($image);

        } else {
            echo 'An error occurred.';
        }

        return $imageName;
    }

}
