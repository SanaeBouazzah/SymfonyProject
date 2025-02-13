<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Controller\SecurityController;


final class HomeController extends AbstractController
{
  #[Route('/home', name: 'app_home')]
  public function index(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $hasher): Response{
    if ($this->getUser()) {
      return $this->render('home/index.html.twig', [
        'controller_name' => 'HomeController',
      ]);
     }
     return $this->redirectToRoute('app_login');
  }

}
