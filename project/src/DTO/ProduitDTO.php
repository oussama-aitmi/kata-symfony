<?php

namespace App\DTO;

use DateTimeImmutable;

class ProduitDTO
{
    public function __construct(
        public ?int               $id,
        public string             $code,
        public string             $name,
        public ?string            $description,
        public ?string            $image,
        public ?string            $category,
        public ?float             $price,
        public ?int               $quantity,
        public ?string            $internalReference,
        public ?int               $shellId,
        public ?array             $inventoryStatus,
        public ?float             $rating,
        public ?DateTimeImmutable $createdAt,
        public ?DateTimeImmutable $updatedAt
    )
    {
    }
}
