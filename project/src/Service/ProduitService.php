<?php

namespace App\Service;

use App\DTO\ProduitDTO;
use App\Entity\Produit;
use App\Enum\InventoryStatus;
use App\Repository\ProduitRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Exception\InvalidArgumentException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProduitService
{
    public function __construct(
        readonly private ProduitRepository  $produitRepository,
        readonly private ValidatorInterface $validator
    )
    {
    }

    /**
     * @param ProduitDTO $produitDTO
     * @return Produit
     */
    public function createProduit(ProduitDTO $produitDTO): Produit
    {
        $produit = new Produit();
        $produit->setCode($produitDTO->code);
        $produit->setName($produitDTO->name);
        $produit->setDescription($produitDTO->description);
        $produit->setImage($produitDTO->image);
        $produit->setCategory($produitDTO->category);
        $produit->setPrice($produitDTO->price);
        $produit->setQuantity($produitDTO->quantity);
        $produit->setInternalReference($produitDTO->internalReference);
        $produit->setShellId($produitDTO->shellId);
        $produit->setInventoryStatus(
            array_map(fn($internalReference) => InventoryStatus::from($internalReference), $produitDTO->inventoryStatus)
        );

        if (\count($errors = $this->validator->validate($produit))) {
            throw new InvalidArgumentException($errors);
        }

        $this->produitRepository->save($produit);

        return $produit;
    }

    /**
     * @return array<ProduitDTO>
     */
    public function getAllProduits(): array
    {
        $products = $this->produitRepository->findAll();

        return array_map(function ($product) {
            return $this->mapToDto($product);
        }, $products);
    }

    /**
     * @param int $id
     * @return ProduitDTO|null
     */
    public function getProduit(int $id): ?ProduitDTO
    {
        $produit = $this->produitRepository->findOneBy(['id' => $id]);

        if (null === $produit) {
            return null;
        }

        return $this->mapToDto($produit);
    }

    public function updateProduit(int $id, ProduitDTO $produitDTO): ProduitDTO
    {
        $product = $this->produitRepository->findOneBy(['id' => $id]);

        if (!$product) {
            throw new NotFoundHttpException('Produit non trouvé');
        }

        $product->setCode($produitDTO->code);
        $product->setName($produitDTO->name);
        $product->setDescription($produitDTO->description);
        $product->setImage($produitDTO->image);
        $product->setCategory($produitDTO->category);
        $product->setPrice($produitDTO->price);
        $product->setQuantity($produitDTO->quantity);
        $product->setInternalReference($produitDTO->internalReference);
        $product->setShellId($produitDTO->shellId);
        $product->setInventoryStatuses($produitDTO->inventoryStatus);
        $product->setRating($produitDTO->rating);
        $product->setUpdatedAt(); // Mise à jour de date de Modif

        $this->produitRepository->save($product);

        return $this->mapToDto($product);
    }

    /**
     * @param Produit $product
     * @return ProduitDTO
     */
    public function mapToDto(Produit $product): ProduitDTO
    {
        return new ProduitDTO(
            $product->getId(),
            $product->getCode(),
            $product->getName(),
            $product->getDescription(),
            $product->getImage(),
            $product->getCategory(),
            $product->getPrice(),
            $product->getQuantity(),
            $product->getInternalReference(),
            $product->getShellId(),
            $product->getInventoryStatus(),
            $product->getRating(),
            $product->getCreatedAt(),
            $product->getUpdatedAt()
        );
    }
}
