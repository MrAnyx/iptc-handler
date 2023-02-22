<?php

namespace App\Model;

use App\Service\IptcHeaderKey;
use Symfony\Component\Validator\Constraints as Assert;

class IptcData
{
    #[Assert\Length(
        max: 5,
        maxMessage: 'Le commentaire ne peut pas dépasser {{ limit }} caractères',
    )]
    private string $comment = "";

    public function __construct(
        string $comment,
    ) {
        $this->comment = $comment;
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

    public function toArray()
    {
        return [
            IptcHeaderKey::COMMENT => $this->comment
        ];
    }
}
