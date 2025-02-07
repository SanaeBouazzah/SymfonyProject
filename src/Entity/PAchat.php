<?php

namespace App\Entity;

use App\Repository\PAchatRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PAchatRepository::class)]
class PAchat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }
}
