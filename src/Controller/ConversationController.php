<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\Mercure\Authorization;
use Symfony\Component\Mercure\Discovery;
use App\Service\TopicService;
use App\Factory\ConversationFactory;
use App\Repository\ConversationRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method User|null getUser()
 */
final class ConversationController extends AbstractController
{
    public function __construct(
        private readonly Authorization $authorization,
        private readonly ConversationRepository $conversationRepository,
        private readonly ConversationFactory $factory,
        private readonly Discovery $discovery,
        private readonly TopicService $topicService
    ) {}

    #[Route('/conversation/users/{recipient}', name: 'conversation.show')]
    public function show(?User $recipient, Request $request): Response
    {
        $sender = $this->getUser();
        $conversation = $this->conversationRepository->findByUsers($sender, $recipient) ?: 
                        $this->factory->create($sender, $recipient);

        $topic = $this->topicService->getTopicUrl($conversation);
        $this->discovery->addLink($request);
        $this->authorization->setCookie($request, [$topic]);

        return $this->render('conversation/show.html.twig', [
            'conversation' => $conversation,
            'topic' => $topic,
            'recipient' => $recipient
        ]);
    }
}