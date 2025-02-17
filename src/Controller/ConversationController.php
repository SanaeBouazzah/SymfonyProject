<?php

namespace App\Controller;

use App\Entity\Conversation;
use App\Entity\User;
use App\Repository\ConversationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ConversationController extends AbstractController
{
     public function __construct(private readonly ConversationRepository $conversationRepository){}

     public function fingByUsers(User $sender, User $recipient):  Conversation{

     }

    #[Route('/conversation/users/{recipient}', name: 'conversation.show')]
    public function index(?User $recipient): Response
    {
        return $this->render('conversation/index.html.twig', [
            'controller_name' => 'ConversationController',
        ]);
    }
}
