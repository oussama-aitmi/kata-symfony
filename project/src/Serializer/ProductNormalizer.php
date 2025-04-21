<?php

namespace App\Serializer;

use App\DTO\ProduitDTO;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;

class ProductNormalizer implements ContextAwareNormalizerInterface
{
    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        // Vérification si l'ibjet retourné est de type ProduitDTO
        if (!($data instanceof ProduitDTO)) {
            return false;
        }

        return true;
    }

    public function normalize($object, string $format = null, array $context = [])
    {
        $responce = [
            'id' => $object->id,
            'code' => $object->code,
            'name' => $object->name,
            'description' => $object->description,
            'image' => $object->image,
            'category' => $object->category,
            'price' => $object->price,
            'quantity' => $object->quantity,
            'internalReference' => $object->internalReference,
            'shellId' => $object->shellId,
            'inventoryStatus' => $object->inventoryStatus,
            'rating' => $object->rating,
            'createdAt' => $object->createdAt,
            'updatedAt' => $object->updatedAt,
        ];

        // Sérialisation personnalisée pour le champ inventoryStatus
        $inventoryStatuses = $object->inventoryStatus;


        if (is_array($inventoryStatuses)) {
            $responce['inventoryStatus'] = implode(' | ', $inventoryStatuses);
        }

        return $responce;
    }
}
