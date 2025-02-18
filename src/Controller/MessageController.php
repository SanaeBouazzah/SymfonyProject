<?php

namespace App\Controller;

use App\DTO\CreateMessage;
use App\Factory\MessageFactory;
use App\Repository\ConversationRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class MessageController extends AbstractController
{

    public function __construct(private ConversationRepository $conversationRepository,
    private readonly MessageFactory $factory)
    {
      
    }


    #[Route('/message', name: 'message.create', methods:['POST'])]
    public function create(#[MapRequestPayload()]CreateMessage $payload): Response
    {
       $conversation = $this->conversationRepository->find($payload->conversationId);
       $message = $this->factory->create(
        conversation : $conversation,
        author : $this->getUser(),
        content : $payload->content
       );
        return new Response('', Response::HTTP_CREATED);
    }
}
