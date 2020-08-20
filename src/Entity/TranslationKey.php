<?php

namespace App\Entity;

use App\Repository\TranslationKeyRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TranslationKeyRepository::class)
 */
class TranslationKey
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $text_key;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText_key(): ?string
    {
        return $this->text_key;
    }

    public function setTextKey(string $text_key): self
    {
        $this->text_key = $text_key;

        return $this;
    }
}