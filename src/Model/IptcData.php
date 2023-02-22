<?php

namespace App\Model;

use App\Service\IptcHeaderKey;
use Symfony\Component\Validator\Constraints as Assert;

class IptcData
{
    #[Assert\Length(max: 2000, maxMessage: 'Le commentaire ne peut pas dépasser {{ limit }} caractères')]
    private string $comment = "";

    #[Assert\Length(max: 32, maxMessage: 'L\'auteur ne peut pas dépasser {{ limit }} caractères')]
    private string $author = "";

    #[Assert\Length(max: 128, maxMessage: 'Les copyrights ne peut pas dépasser {{ limit }} caractères')]
    private string $copyright = "";

    public function __construct(
        string $comment,
        string $author,
        string $copyright,
    ) {
        $this->comment = $comment;
        $this->author = $author;
        $this->copyright = $copyright;
    }

    public function getComment(): string
    {
        return $this->comment;
    }

    public function setComment(string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getCopyright(): string
    {
        return $this->copyright;
    }

    public function setCopyright(string $copyright): self
    {
        $this->copyright = $copyright;

        return $this;
    }

    public function toArray()
    {
        return [
            IptcHeaderKey::COMMENT => $this->comment,
            IptcHeaderKey::AUTHOR => $this->author,
            IptcHeaderKey::COPYRIGHT => $this->copyright
        ];
    }
}
