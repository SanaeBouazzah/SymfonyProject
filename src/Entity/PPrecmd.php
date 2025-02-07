<?php

namespace App\Entity;

use App\Repository\PPrecmdRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PPrecmdRepository::class)]
class PPrecmd
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
