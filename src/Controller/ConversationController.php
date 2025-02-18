<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Conversation;
use App\Factory\ConversationFactory;
use App\Repository\ConversationRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @method User|null getUser()
 */

final class ConversationController extends AbstractController
{
     public function __construct(private readonly ConversationRepository $conversationRepository,
                                 private readonly ConversationFactory $factory){}

    #[Route('/conversation/users/{recipient}', name: 'conversation.show')]
    public function show(?User $recipient): Response
    {
       $sender = $this->getUser();
       $conversation = $this->conversationRepository->findByUsers($sender, $recipient);
       if(!$conversation){
        $conversation = $this->factory->create($sender, $recipient);
       }
        return $this->render('conversation/show.html.twig', [
            'conversation' => $conversation
        ]);
    }
}
