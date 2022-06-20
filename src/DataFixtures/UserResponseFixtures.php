<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use App\Entity\Question;
use App\Entity\UserResponse;
use Symfony\Component\Console\Output\Output;

class UserResponseFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $data = [
            'fodekar' => [
                'Qui est le créateur de Facebook ?' => [
                    'hesskn' => 'Mark Zuckerberg',
                    'kellia' => 'Jacques Chirac'
                ]
            ],
            'hesskn' => [
                'qui est le premier président de la 5e république ?' => [
                    'fodekar' => 'Charles de Gaulle'
                ]
            ]
        ];

        foreach ($data as $username => $element) {
            $user = $this->createUser($manager, $username);

            foreach ($element as $question => $responses) {
                $user_question = $this->createQuestion($manager, $user, $question);

                $this->createUserResponse($manager, $user_question, $responses);
            }
        }
    }

    public function createUser($manager, $username)
    {
        $output = null;

        if ($username) {
            $user = $this->getUser($manager, $username);

            if (!$user) {
                $user = new User();
                $user->setUsername($username);

                $manager->persist($user);
                $manager->flush();
            }

            $output = $user;
        }

        return $output;
    }

    public function createQuestion($manager, $user, $content)
    {
        $output = null;

        if ($user && $content) {
            $question = $manager->getRepository(Question::class)->findOneByContent($content);

            if (!$question) {
                $question = new Question();
                $question->setUser($user);
                $question->setContent($content);

                $manager->persist($question);
                $manager->flush();
            }

            $output = $question;
        }

        return $output;
    }

    public function createUserResponse($manager, $question, $response_collection)
    {
        if ($response_collection && $question) {
            foreach ($response_collection as $username => $content) {
                $user = $this->getUser($manager, $username);

                if (!$user) {
                    $user = $this->createUser($manager, $username);
                }

                if ($user) {
                    $response = $manager->getRepository(UserResponse::class)->findOneByContent($content);

                    if (!$response) {
                        $response = new UserResponse();
                        $response->setUser($user);
                        $response->setQuestion($question);
                        $response->setContent($content);

                        $manager->persist($response);
                    }
                }
            }

            $manager->flush();
        }
    }

    public function getUser($manager, $username)
    {
        return $manager->getRepository(User::class)->findOneByUsername($username);
    }
}
