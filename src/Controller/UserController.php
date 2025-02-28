<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\SearchType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


#[Route('/users')]
// #[IsGranted('ROLE_ADMIN')]
final class UserController extends AbstractController
{
    #[Route(name: 'users', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response{
      // $user = new User();
      // $user->setUsername('admin'); 
      // $user->setUsername('admin@example.com'); 
      // $user->setPassword('admin_password');
      // $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
      // $user->setPassword($hashedPassword);
      // $user->setRoles(['ROLE_ADMIN']); 
      // $entityManager->persist($user);
      // $entityManager->flush();
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
      $user = new User();
      $form = $this->createForm(UserType::class, $user);
      $form->handleRequest($request);
  
      if ($form->isSubmitted() && $form->isValid()) {
          $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
          $user->setPassword($hashedPassword);
          $user->setRoles(['ROLE_USER']);
          $entityManager->persist($user);
          $entityManager->flush();
  
          return $this->redirectToRoute('users');
        }
        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hashedPassword = $passwordHasher->hashPassword(
              $user,
              $user->getPassword()
            );
            $user->setPassword($hashedPassword);
            // $user->setRoles(['ROLE_USER']);
            $entityManager->flush();
            return $this->redirectToRoute('users', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('users', [], Response::HTTP_SEE_OTHER);
    }
}
