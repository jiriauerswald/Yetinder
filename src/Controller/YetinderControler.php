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

    /**
     *
     * @Route("/", name="bestOf")
     */
    public function bestOf(): Response
    {

        // TODO change parameter or getConnection to use .env to get url from there
        $conn = DriverManager::getConnection([
            'url' => 'mysql://root:@localhost/yetinder'
        ]);
        //TODO limit value to shouldnt be hardcoded
        $query = 'SELECT * FROM yetis ORDER BY height DESC LIMIT 10';
        $bestOf = $conn->fetchAllAssociative($query);

        return $this->render('best_of.html.twig', [
            'yetinder' => $bestOf
        ]);
    }
    
    /**
     *
     * @Route("/tinder", name="tinder")
     */
    public function tinder(): Response
    {
        
        // TODO change parameter or getConnection to use .env to get url from there
        $conn = DriverManager::getConnection([
            'url' => 'mysql://root:@localhost/yetinder'
        ]);
        $yetisIds = $conn->fetchAllAssociative('SELECT yeti_id FROM yetis');

        $randomYetiId = $yetisIds[array_rand($yetisIds)]['yeti_id'];

        if (isset($randomYetiId)){
            $randomYetiQuery = $conn->prepare('SELECT * FROM yetis WHERE yeti_id = ?');
            $randomYetiQuery->bindValue(1, $randomYetiId);
            $yeti = $randomYetiQuery->executeQuery()->fetchAssociative();
        }
   
        return $this->render('tinder.html.twig', [
            'yeti' => $yeti
        ]);
    }
    

    /**
     *
     * @Route("/add", name="addNew")
     */
    public function addNewYeti(Request $request): Response
    {
        $form = $this->createForm(AddNewYetiFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $yeti = $form->getData();
            
            // TODO change parameter or getConnection to use .env to get url from there
            $conn = DriverManager::getConnection([
                'url' => 'mysql://root:@localhost/yetinder'
            ]);
            
            //Check if entered name already exists in db
            $query = $conn->prepare("SELECT COUNT(name) FROM yeti WHERE name = ?");
            $query->bindValue(1, $yeti["name"]);
            $nameCount = $query->executeQuery()->fetchNumeric();
            if ($nameCount[0] > 0) {
                $this->addFlash('error', 'Yeti with given name already exists!');
                return $this->redirectToRoute('addNew');
            }
            
            
            // TODO Change to secure method = query builder "Table expression and columns are not escaped and are not safe for user-input."

            $queryBuilder = $conn->createQueryBuilder();

            $queryBuilder->insert('yeti')
                ->values([
                'name' => '?',
                'age' => '?',
                'height' => '?',
                'weight' => '?',
                'gender' => '?',
                'residence' => '?'
            ])
                ->setParameter(0, $yeti["name"])
                ->setParameter(1, $yeti["age"])
                ->setParameter(2, $yeti["height"])
                ->setParameter(3, $yeti["weight"])
                ->setParameter(4, $yeti["gender"])
                ->setParameter(5, $yeti["residence"]);

            $queryBuilder->executeStatement();

            $this->addFlash('notice', 'Yeti created successfuly!');

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