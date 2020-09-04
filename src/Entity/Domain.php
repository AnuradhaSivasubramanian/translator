<?php

namespace App\Entity;

use App\Repository\DomainRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DomainRepository::class)
 */
class Domain
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
    private $domain_name;

    /**
     * @ORM\ManyToMany(targetEntity=TranslationKey::class, inversedBy="domains")
     */
    private $translation_keys;

    public function __construct()
    {
        $this->translation_keys = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDomainName(): ?string
    {
        return $this->domain_name;
    }

    public function setDomainName(string $domain_name): self
    {
        $this->domain_name = $domain_name;

        return $this;
    }

    /**
     * @return Collection|TranslationKey[]
     */
    public function getTranslationKeys(): Collection
    {
        return $this->translation_keys;
    }

    public function addTranslationKey(TranslationKey $translationKey): self
    {
        if (!$this->translation_keys->contains($translationKey)) {
            $this->translation_keys[] = $translationKey;
        }

        return $this;
    }

    public function removeTranslationKey(TranslationKey $translationKey): self
    {
        if ($this->translation_keys->contains($translationKey)) {
            $this->translation_keys->removeElement($translationKey);
        }

        return $this;
    }
}
