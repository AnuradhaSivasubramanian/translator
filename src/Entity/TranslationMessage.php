<?php

namespace App\Entity;

use App\Repository\TranslationMessageRepository;
use App\Entity\TranslationKey;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * @ORM\Entity(repositoryClass=TranslationMessageRepository::class)
 * @ORM\HasLifecycleCallbacks()
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

    /**
     * @var datetime $updated
     *
     * @ORM\Column(type="datetime", nullable = true)
     */
    private $updated;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return int|null
     */
    public function getTranslationKeyId(): ?int
    {
        return $this->translation_key_id;
    }

    /**
     * @param int $translation_key_id
     * @return $this
     */
    public function setTranslationKeyId(int $translation_key_id): self
    {
        $this->translation_key_id = $translation_key_id;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLanguage(): ?string
    {
        return $this->language;
    }

    /**
     * @param string $language
     * @return $this
     */
    public function setLanguage(string $language): self
    {
        $this->language = $language;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * @param string $message
     * @return $this
     */
    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return \App\Entity\TranslationKey|null
     */
    public function getTranslationKey(): ?TranslationKey
    {
        return $this->translation_key;
    }

    /**
     * @param \App\Entity\TranslationKey|null $translation_key
     * @return $this
     */
    public function setTranslationKey(?TranslationKey $translation_key): self
    {
        $this->translation_key = $translation_key;

        return $this;
    }

    /**
     * Gets triggered every time on update

     * @ORM\PreUpdate
     */
    public function preUpdate()
    {
        $this->updated = new \DateTime("now");
    }

    public function getUpdated(){
        return $this->updated;
    }
}