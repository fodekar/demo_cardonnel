<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Question;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'questions' => $this->getQuestionRepository()->findCounterByContent(),

        ]);
    }

    private function getQuestionRepository()
    {
        return $this->get('doctrine')->getRepository(Question::class);
    }
}
