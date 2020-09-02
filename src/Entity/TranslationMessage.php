<?php

namespace App\Entity;

use App\Repository\TranslationMessageRepository;
use App\Entity\TranslationKey;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TranslationMessageRepository::class)
 */
class TranslationMessage
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $translation_key_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $language;

    /**
     * @ORM\Column(type="string", length=200)
     */
    private $message;

    /**
     * @ORM\ManyToOne(targetEntity=TranslationKey::class, inversedBy="translationMessages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $translation_key;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTranslationKeyId(): ?int
    {
        return $this->translation_key_id;
    }

    public function setTranslationKeyId(int $translation_key_id): self
    {
        $this->translation_key_id = $translation_key_id;

        return $this;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(string $language): self
    {
        $this->language = $language;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getTranslationKey(): ?TranslationKey
    {
        return $this->translation_key;
    }

    public function setTranslationKey(?TranslationKey $translation_key): self
    {
        $this->translation_key = $translation_key;

        return $this;
    }
}