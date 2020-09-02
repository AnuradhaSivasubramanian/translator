<?php

namespace App\Entity;

use App\Repository\TranslationKeyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    /**
     * @ORM\OneToMany(targetEntity=TranslationMessage::class, mappedBy="translation_key", orphanRemoval=true)
     */
    private $translationMessages;

    public function __construct()
    {
        $this->translationMessages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTextKey(): ?string
    {
        return $this->text_key;
    }

    public function setTextKey(string $text_key): self
    {
        $this->text_key = $text_key;

        return $this;
    }

    /**
     * @return Collection|TranslationMessage[]
     */
    public function getTranslationMessages(): Collection
    {
        return $this->translationMessages;
    }

    public function addTranslationMessage(TranslationMessage $translationMessage): self
    {
        if (!$this->translationMessages->contains($translationMessage)) {
            $this->translationMessages[] = $translationMessage;
            $translationMessage->setTranslationKey($this);
        }

        return $this;
    }

    public function removeTranslationMessage(TranslationMessage $translationMessage): self
    {
        if ($this->translationMessages->contains($translationMessage)) {
            $this->translationMessages->removeElement($translationMessage);
            // set the owning side to null (unless already changed)
            if ($translationMessage->getTranslationKey() === $this) {
                $translationMessage->setTranslationKey(null);
            }
        }

        return $this;
    }
}
