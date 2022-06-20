<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Question;
use App\Entity\User;
use App\Form\QuestionType;

class QuestionController extends AbstractController
{
    /**
     * @Route("/question", name="question")
     */
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $username = $request->request->get('question')['username'];
        $user = $this->getUserRepository()->findOneByUsername($username);

        $question = new Question();
        $question->setContent('');
        $question->setUser($user);

        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($question);
            $entityManager->flush();

            return $this->redirect($this->generateUrl('home'));
        }

        return $this->render('question/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function getUserRepository()
    {
        return $this->get('doctrine')->getRepository(User::class);
    }
}
