<?php

namespace App\Controller;

use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

#[Route('/test')]
class TestController extends AbstractController
{
    private $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    #[Route('/image-optimize', name: 'image_optimize')]
    public function imageOptimizer(): Response
    {
        
        $imagine = new Imagine();
        $filePath = '/home/linqur/learning/symfony/guestbook/public/uploads/photos/2f42438877fb.png';        

        list($iWidth, $iHeight) = getimagesize($filePath);

        $ratio = $iWidth / $iHeight;
        $width = 200;
        $height = 150;

        if ($height / $width > $ratio) {
            $iWidth = $height * $ratio;
        } else {
            $height = $width / $ratio;
        }

        $photo = $imagine->open($filePath);
        $photo->resize(new Box($width, $height))->save('/home/linqur/learning/symfony/guestbook/public/uploads/photos/optimize.png');

        return new Response($this->twig->render('tests/test.html.twig'));
    }
}