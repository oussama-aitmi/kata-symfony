<?php

namespace App\Service;

use App\Entity\Panier;
use App\Entity\Produit;
use App\Entity\LignePanier;
use App\Repository\LignePanierRepository;
use App\Repository\PanierRepository;

class PanierService
{
    public function __construct(
        private readonly LignePanierRepository $lignePanierRepository,
        private readonly PanierRepository $panierRepository,
    )
    {
    }

    /**
     * @param Panier $panier
     * @param Produit $produit
     * @param int $quantite
     * @return Panier
     */
    public function addProduitToPanier(Panier $panier, Produit $produit, int $quantite): Panier
    {
        // On vérfier si le produit est déjà dans le panier
        foreach ($panier->getLignesPanier() as $lignePanier) {
            if ($lignePanier->getProduit()->getId() === $produit->getId()) {
                // Si le produit est déjà dans le panier, mettez à jour la quantité
                $lignePanier->setQuantite($lignePanier->getQuantite() + $quantite);
                $this->lignePanierRepository->save($lignePanier);

                return $panier;
            }
        }

        // Sinon, créez une nouvelle ligne de panier
        $lignePanier = new LignePanier();
        $lignePanier->setProduit($produit);
        $lignePanier->setQuantite($quantite);
        $panier->addLignePanier($lignePanier);

        $this->lignePanierRepository->save($lignePanier);

        return $panier;
    }

    /**
     * @param Panier $panier
     * @return float
     */
    public function getTotalPanier(Panier $panier): float
    {
        $total = 0;
        foreach ($panier->getLignesPanier() as $lignePanier) {
            $total += $lignePanier->getProduit()->getPrice() * $lignePanier->getQuantite();
        }

        return $total;
    }

    public function getCurrentPanier()
    {
        return $this->panierRepository->findAll()[0]; // normalent on cherche un panier de User connecté
    }
}
