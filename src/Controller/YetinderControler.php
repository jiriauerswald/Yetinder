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

        //TODO change parameter or getConnection to use .env to get url from there
        $conn = DriverManager::getConnection(['url' => 'mysql://root:@localhost/yetinder']);
        $query = 'SELECT * FROM yeti ORDER BY height DESC LIMIT 10';
        $bestOf = $conn->fetchAllAssociative($query);
        
        //dd($bestOf);
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
        //TODO change parameter or getConnection to use .env to get url from there
        $conn = DriverManager::getConnection(['url' => 'mysql://root:@localhost/yetinder']);
        
        $form = $this->createForm(AddNewYetiFormType::class);
        
        $form->handleRequest($request);
        
        //TODO dodelat validaci formulare
        if ($form->isSubmitted() && $form->isValid()) {
            
            $yeti = $form->getData();

            $conn->insert('yeti', [
                'name' => $yeti["name"],
                'age' =>$yeti["age"],
                'height' =>$yeti["height"],
                'weight' =>$yeti["weight"],
                'gender' =>$yeti["gender"],
                'residence' =>$yeti["residence"],
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