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

        $bestOfYetiCount = 10;
        
        $bestOfYetiQuery = 'SELECT yetis.yeti_id, yetis.name, yetis.gender, yetis.height, yetis.weight, yetis.residence, yetis.age, AVG(ratings.value) AS average_rating
        FROM `yetis` LEFT JOIN `ratings` ON yetis.yeti_id = ratings.yeti_id 
        GROUP BY yetis.yeti_id 
        ORDER BY average_rating
        DESC LIMIT ' . $bestOfYetiCount;
        $bestOf = $conn->fetchAllAssociative($bestOfYetiQuery);
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
            GROUP BY yetis.yeti_id');
            $randomYetiQuery->bindValue(1, $randomYetiId);
            $yeti = $randomYetiQuery->executeQuery()->fetchAssociative();
        }

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
            return $this->redirectToRoute($request->attributes->get('_route'));
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
            $conn = DriverManager::getConnection([
                'url' => $this->getParameter('DATABASE_URL')
            ]);
            $formData = $form->getData();

            // Check if entered name already exists in db
            $query = $conn->prepare("SELECT COUNT(name) FROM yetis WHERE name = ?");
            $query->bindValue(1, $formData["name"]);
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
                ->setParameter(0, $formData["name"])
                ->setParameter(1, $formData["age"])
                ->setParameter(2, $formData["height"])
                ->setParameter(3, $formData["weight"])
                ->setParameter(4, $formData["gender"])
                ->setParameter(5, $formData["residence"]);

            $queryBuilder->executeStatement();

            $this->addFlash('notice', 'Yeti created successfuly!');

            return $this->redirectToRoute('bestOf');
        }

        return $this->render('add_new_yeti.html.twig', [
            'add_new_yeti_form' => $form->createView()
        ]);
    }

    /**
     *
     * @Route("/stats", name="stats")
     */
    public function stats(Request $request): Response
    {
        $conn = DriverManager::getConnection([
            'url' => $this->getParameter('DATABASE_URL')
        ]);
        
        $statsQuery = $conn->prepare('
        SELECT yetis.yeti_id, yetis.name, AVG(ratings.value) AS average_rating, COUNT(ratings.rating_id) AS ratings_count
        FROM `yetis` LEFT JOIN `ratings` ON yetis.yeti_id = ratings.yeti_id
        WHERE timestamp BETWEEN ? AND ?
        GROUP BY yetis.yeti_id');
        
        //Current day stats 
        $midnightTimestamp = strtotime('midnight');    
        $nextMidnightTimestamp = strtotime('midnight')+ (1*60*60*24);
        
        $statsQuery->bindValue(1,date('Y-m-d h:i:s', $midnightTimestamp));
        $statsQuery->bindValue(2,date('Y-m-d h:i:s', $nextMidnightTimestamp));
        $statsToday = $statsQuery->executeQuery()->fetchAllAssociative();
        
        //Current month stats
        $beginingOfMonthTimestamp = strtotime('first day of this month midnight');
        $endOfMonthTimestamp =  strtotime('last day of this month midnight') + (1*60*60*24);
        
        $statsQuery->bindValue(1,date('Y-m-d h:i:s', $beginingOfMonthTimestamp));
        $statsQuery->bindValue(2,date('Y-m-d h:i:s', $endOfMonthTimestamp));
        $statsThisMonth = $statsQuery->executeQuery()->fetchAllAssociative();
        
        //Current year stats
        $beginingOfYearTimestamp = strtotime('first day of January this year midnight');
        $endOfYearTimestamp =  strtotime('last day of December this year midnight') + (1*60*60*24);
        
        $statsQuery->bindValue(1,date('Y-m-d h:i:s', $beginingOfYearTimestamp));
        $statsQuery->bindValue(2,date('Y-m-d h:i:s', $endOfYearTimestamp));
        $statsThisYear = $statsQuery->executeQuery()->fetchAllAssociative();
        
        return $this->render('statistics.html.twig', [
            'statsToday' => $statsToday,
            'statsThisMonth' => $statsThisMonth,
            'statsThisYear' => $statsThisYear,
        ]);
    }

    public static function getSubscribedServices(): array
    {
        return parent::getSubscribedServices();
    }
}