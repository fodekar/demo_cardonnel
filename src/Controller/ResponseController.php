<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Question;
use App\Entity\UserResponse;
use App\Service\User as UserService;
use App\Form\ResponseType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ResponseController extends AbstractController
{
    /**
     * @Route("/response/{uuid}", name="response")
     */
    public function index(Request $request, UserService $userService, EntityManagerInterface $entityManager, $uuid): Response
    {
        $question = $this->getQuestionRepository()->findOneByUuid($uuid);
        $question_label = ($question) ? $question->getContent() : null;

        dump($uuid, $question);
        // dump($question, $this->getRepository()->findAllByQuestion($uuid), 'pat patrouille');

        $username = $request->request->get('response')['username'];
        $user = $userService->create($username);

        //dd($user, $username, 'user');

        $response = new UserResponse();
        $response->setContent('');
        $response->setUser($user);
        $response->setQuestion($question);

        $form = $this->createForm(ResponseType::class, $response);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($response);
            $entityManager->flush();

            return $this->redirect($this->generateUrl('home'));
        }

        return $this->render('response/index.html.twig', [
            'form' => $form->createView(),
            'question' => $question_label,
            'responses' => $this->getRepository()->findAllByQuestion($uuid)
        ]);
    }

    public function getQuestionRepository()
    {
        return $this->get('doctrine')->getRepository(Question::class);
    }

    public function getRepository()
    {
        return $this->get('doctrine')->getRepository(UserResponse::class);
    }

    public function getUserRepository()
    {
        return $this->get('doctrine')->getRepository(User::class);
    }
}
