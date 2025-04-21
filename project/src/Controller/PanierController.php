<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\PanierService;

class PanierController extends AbstractController
{
    public function __construct(
        private PanierService     $panierService,
        private ProduitRepository $produitRepository
    )
    {
    }

    #[Route('/api/panier', name: 'add_panier', methods: ['POST'])]
    public function ajouterPanier(Request $request): JsonResponse
    {
        $produitId = $request->request->get('produit_id');
        $quantite = $request->request->get('quantite', 1);

        // Récupérer le panier de l'utilisateur et le produit à ajouter
        $panier = $this->panierService->getCurrentPanier();
        $produit = $this->produitRepository->find($produitId);

        if (!$produit) {
            return $this->json(['error' => 'Produit non trouvé'], 404);
        }

        $this->panierService->addProduitToPanier($panier, $produit, $quantite);

        return $this->json(['success' => 'Produit ajouté au panier avec succès']);
    }

    #[Route('/api/panier', name: 'get_panier', methods: ['GET'])]
    public function getPanier(): JsonResponse
    {
        /** @var Panier $panier * */
        $panier = $this->panierService->getCurrentPanier();

        $panierData = [];
        foreach ($panier->getLignesPanier() as $lignePanier) {
            $panierData[] = [
                'produit' => $lignePanier->getProduit()->getName(),
                'quantite' => $lignePanier->getQuantite(),
                'prix' => $lignePanier->getProduit()->getPrice(),
                'total' => $lignePanier->getProduit()->getPrice() * $lignePanier->getQuantite(),
            ];
        }

        return $this->json($panierData);
    }
}
