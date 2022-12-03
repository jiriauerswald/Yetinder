<?php
namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Form\AddNewYetiFormType;
use App\Form\RateYetiFormType;
use Doctrine\DBAL\DriverManager;

class YetinderControler extends AbstractController
{
    /**
     *
     * @Route("/", name="bestOf")
     */
    public function bestOf(): Response
    {
        $conn = DriverManager::getConnection([
            'url' => $this->getParameter('DATABASE_URL')
        ]);
        // TODO limit value to shouldnt be hardcoded
        $query = 
        'SELECT yetis.yeti_id, yetis.name, yetis.gender, yetis.height, yetis.weight, yetis.residence, yetis.age, AVG(ratings.value) AS average_rating
        FROM `yetis` LEFT JOIN `ratings` ON yetis.yeti_id = ratings.yeti_id 
        GROUP BY yetis.yeti_id 
        ORDER BY average_rating
        DESC LIMIT 10';
        $bestOf = $conn->fetchAllAssociative($query);
        return $this->render('best_of.html.twig', [
            'yetinder' => $bestOf
        ]);
    }

    /**
     *
     * @Route("/tinder", name="tinder")
     */
    public function tinder(Request $request): Response
    {
        $conn = DriverManager::getConnection([
            'url' => $this->getParameter('DATABASE_URL')
        ]);
        $yetisIds = $conn->fetchAllAssociative('SELECT yeti_id FROM yetis');

        $form = $this->createForm(RateYetiFormType::class);
        $form->handleRequest($request);

        $randomYetiId = $yetisIds[array_rand($yetisIds)]['yeti_id'];

        // fetch yeti with radnomly selected id from database
        if (isset($randomYetiId)) {
            $randomYetiQuery = $conn->prepare('
            SELECT yetis.yeti_id, yetis.name, yetis.gender, yetis.height, yetis.weight, yetis.residence, yetis.age, AVG(ratings.value) AS average_rating
            FROM `yetis` LEFT JOIN `ratings` ON yetis.yeti_id = ratings.yeti_id 
            WHERE yetis.yeti_id = ?            
            GROUP BY yetis.yeti_id' 
            );
            $randomYetiQuery->bindValue(1, $randomYetiId);
            $yeti = $randomYetiQuery->executeQuery()->fetchAssociative();
        }
        
       // if ($this->isCsrfTokenValid('_token', $request->request->get('rating_item'))){dd($form);}
        
        if ($form->isSubmitted() && $form->isValid()) {

            $rating = $form->getData();
            $queryBuilder = $conn->createQueryBuilder();
            $queryBuilder->insert('ratings')
                ->values([
                'yeti_id' => '?',
                'value' => '?'
            ])
                ->setParameter(0, $rating["yeti_id"])
                ->setParameter(1, $rating["rating"]);
            $result = $queryBuilder->executeStatement();
            if ($result > 0) {
                $this->addFlash('notice', 'Rating of id ' . $rating["yeti_id"] . ' stored!');
            }
            //return $this->redirectToRoute($request->attributes->get('_route'));
        }
        
        // pass currently displayed yeti id to the form before submiting
        if (! $form->isSubmitted()) {
            $form->get('yeti_id')->setData($randomYetiId);
        }

        return $this->render('tinder.html.twig', [
            'yeti' => $yeti,
            'rate_yeti_form' => $form->createView()
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

            // Check if entered name already exists in db
            $query = $conn->prepare("SELECT COUNT(name) FROM yetis WHERE name = ?");
            $query->bindValue(1, $yeti["name"]);
            $nameCount = $query->executeQuery()->fetchNumeric();
            if ($nameCount[0] > 0) {
                $this->addFlash('error', 'Yeti with given name already exists!');
                return $this->redirectToRoute('addNew');
            }

            $queryBuilder = $conn->createQueryBuilder();

            $queryBuilder->insert('yetis')
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