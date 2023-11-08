<?php

namespace App\Entity;

use App\Repository\MemberRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MemberRepository::class)]
class Member
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 32)]
    public string $first_name;

    #[ORM\Column(length: 32)]
    public string $last_name;

    #[ORM\Column(length: 255)]
    public string $address;

    public function getId(): ?int
    {
        return $this->id;
    }
}
