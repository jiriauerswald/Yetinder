<?php
namespace App\Controller;


use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Entity\Yeti;
use App\Form\AddNewYetiFormType;
use Doctrine\DBAL\DriverManager;

class YetinderControler extends AbstractController
{
    
    
    # [Route('/')]
    /**
     * @Route("/", name="bestOf")
     */
    
    public function bestOf(): Response
    {
        $bestOf = [
            'Bigfoot' => [
                'score' => 7,
                'gender' => 'male',
                'age' => 32,
                'height' => 248,
                'interest' => 'female'
            ],
            'Yetigirl' => [
                'score' => 12,
                'gender' => 'female',
                'age' => 21,
                'height' => 211,
                'interest' => 'male'
            ],
            'Homer' => [
                'score' => 21,
                'gender' => 'male',
                'age' => 32,
                'height' => 261,
                'interest' => 'female'
            ],
            'Lisa' => [
                'score' => 12,
                'gender' => 'female',
                'age' => 21,
                'height' => 211,
                'interest' => 'male'
            ]
        ];
        
        //TODO fetch 10 yetis from db sorted by score decs
        
        return $this->render('best_of.html.twig', [
            'yetinder' => $bestOf
        ]);
    }
    
    
    /**
     * @Route("/add", name="addNew")
     */
    public function addNewYeti(Request $request):Response
    {
        //$yeti = new Yeti();
        $conn = DriverManager::getConnection(['url' => 'mysql://root:@localhost/yetinder']);
        
        $form = $this->createForm(AddNewYetiFormType::class);
        
        //$form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //TODO osetrit validaci formulare
            $yeti = $form->getData();
            // ... perform some action, such as saving the task to the database
            $conn->insert('yeti', [
                'name' => $yeti["name"],
                'age' =>$yeti["age"],
                'height' =>$yeti["height"],
                'gender' =>$yeti["gender"],
                //'interest' =>$yeti["interest"],
            ]);
            return $this->redirectToRoute('bestOf');
        }
        
        return $this->render('add_new_yeti.html.twig', [
            'add_new_yeti_form' => $form->createView()
        ]);
    }
    
    
    public static function getSubscribedServices(): array
    {
        return parent::getSubscribedServices();
    }

    
}