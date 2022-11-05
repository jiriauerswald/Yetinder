<?php
namespace App\Controller;


use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class YetinderControler extends AbstractController
{
    
    # [Route('/')]
    /**
     * @Route("/", name="action")
     */
    
    public function bestOf(): Response
    {
        $yetinder = [
            'Bigfoot' => [
                'score' => 7,
                'sex' => 'male',
                'age' => 32,
                'height' => 248,
                'interest' => 'female'
            ],
            'Yetigirl' => [
                'score' => 12,
                'sex' => 'female',
                'age' => 21,
                'height' => 211,
                'interest' => 'male'
            ],
            'Homer' => [
                'score' => 21,
                'sex' => 'male',
                'age' => 32,
                'height' => 261,
                'interest' => 'female'
            ],
            'Lisa' => [
                'score' => 12,
                'sex' => 'female',
                'age' => 21,
                'height' => 211,
                'interest' => 'male'
            ]
        ];
        return $this->render('best_of.html.twig', [
            'yetinder' => $yetinder
        ]);
    }
    
    
    public static function getSubscribedServices(): array
    {
        return parent::getSubscribedServices();
    }

    
}