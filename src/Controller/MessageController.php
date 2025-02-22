<?php

namespace App\Controller;

use App\DTO\CreateMessage;
use App\Factory\MessageFactory;
use App\Repository\ConversationRepository;
use App\Service\TopicService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;

/**
 * @method User|null getUser()
 */
final class MessageController extends AbstractController
{
    public function __construct(
        private readonly ConversationRepository $conversationRepository,
        private readonly MessageFactory $factory,
        private readonly HubInterface $hub,
        private readonly TopicService $topicService
    ) {}

    #[Route('/messages', name: 'message.create', methods: ['POST'])]
    public function create(#[MapRequestPayload] CreateMessage $payload): Response
    {
        $conversation = $this->conversationRepository->find($payload->conversationId);
        if (!$conversation) {
            return new Response('Conversation not found', Response::HTTP_NOT_FOUND);
        }

        $message = $this->factory->create(
            conversation: $conversation,
            author: $this->getUser(),
            content: $payload->content
        );

        $this->hub->publish(new Update(
            topics: $this->topicService->getTopicUrl($conversation),
            data: json_encode([
                'authorId' => $message->getAuthor()->getId(),
                'content' => $message->getContent()
            ]),
            private: true
        ));

        return new Response('', Response::HTTP_CREATED);
    }
}