<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


final class HomeController extends AbstractController
{
  #[Route('/home', name: 'app_home')]
  public function index(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $hasher): Response{
    $user = new User();
    $existingUser = $em->getRepository(User::class)->findOneBy(['username' => 'sanae']);
    if (!$existingUser) {
      $user->setUsername('sanae')
        ->setPassword($hasher
          ->hashPassword($user, '0000'))
        ->setRoles([]);
      $em->persist($user);
      $em->flush();
      $this->addFlash('success', 'You have successfully logged in.');
    }
    return $this->render('home/index.html.twig', [
      'controller_name' => 'HomeController',
    ]);
  }
}
