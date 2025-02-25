<?php

namespace App\Factory;

use App\Entity\User;
use App\Entity\Message;
use App\Entity\Conversation;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ConversationRepository;

/**
 * @method User|null getUser()
 */

class MessageFactory{
  public function  __construct(private readonly EntityManagerInterface $em)
  {
  }

  public function create(Conversation $conversation, User $author, string $content) : Message{
    $message = new Message();
    $message->setConversation($conversation);
    $message->setAuthor($author);
    $message->setContent($content);
    $message->setCreatedAt(new \DateTimeImmutable());
    

    $this->em->persist($message);
    $this->em->flush();
    return $message;
  }
}
