<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\DTO\ProduitDTO;
use App\Service\ProduitService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use OpenApi\Annotations as OA;

class ProduitController extends AbstractController
{
    public function __construct(
        private readonly ProduitService $produitService
    )
    {
    }

    /**
     * @OA\Get(
     *     path="/api/products",
     *     summary="Lister de tous les produits",
     *     @OA\Response(
     *         response=200,
     *         description="Liste de produits retournée avec succès",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Produit"))
     *     )
     * )
     */
    #[Route('/api/products', name: 'list_products', methods: ['GET'])]
    public function list(): Response
    {
        $produits = $this->produitService->getAllProduits();
        return $this->json($produits);
    }

    /**
     * @OA\Post(
     *     path="/api/produits",
     *     summary="Créer un nouveau produit",
     *     description="Cette API permet de créer un nouveau produit avec les détails fournis dans la requête.",
     *     tags={"Produits"},
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="code", type="string", description="Code unique du produit"),
     *             @OA\Property(property="name", type="string", description="Nom du produit"),
     *             @OA\Property(property="description", type="string", description="Description du produit"),
     *             @OA\Property(property="image", type="string", description="URL de l'image du produit"),
     *             @OA\Property(property="category", type="string", description="Catégorie du produit"),
     *             @OA\Property(property="price", type="number", format="float", description="Prix du produit"),
     *             @OA\Property(property="quantity", type="integer", description="Quantité disponible du produit"),
     *             @OA\Property(property="internalReference", type="string", description="Référence interne du produit"),
     *             @OA\Property(property="shellId", type="integer", description="ID de la coque associée au produit"),
     *             @OA\Property(property="inventoryStatus", type="string", enum={"INSTOCK", "LOWSTOCK", "OUTOFSTOCK"},
     *     description="État du stock du produit"),
     *             @OA\Property(property="rating", type="integer", description="Note moyenne du produit"),
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Produit créé avec succès",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", description="ID unique du produit"),
     *             @OA\Property(property="code", type="string", description="Code du produit"),
     *             @OA\Property(property="name", type="string", description="Nom du produit"),
     *             @OA\Property(property="description", type="string", description="Description du produit"),
     *             @OA\Property(property="category", type="string", description="Catégorie du produit"),
     *             @OA\Property(property="price", type="number", format="float", description="Prix du produit"),
     *             @OA\Property(property="quantity", type="integer", description="Quantité disponible"),
     *             @OA\Property(property="internalReference", type="string", description="Référence interne du produit"),
     *             @OA\Property(property="shellId", type="integer", description="ID de la coque associée"),
     *             @OA\Property(property="inventoryStatus", type="string", enum={"INSTOCK", "LOWSTOCK", "OUTOFSTOCK"},
     *     description="État du stock"),
     *             @OA\Property(property="rating", type="integer", description="Note moyenne"),
     *             @OA\Property(property="createdAt", type="integer", description="Timestamp de création"),
     *             @OA\Property(property="updatedAt", type="integer", description="Timestamp de mise à jour"),
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Requête incorrecte, des paramètres sont manquants ou invalides"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erreur serveur lors de la création du produit"
     *     )
     * )
     */
    #[Route('/api/products', methods: ['POST'])]
    public function create(Request $request): Response
    {
        $this->checkIfAdmin();

        $data = json_decode($request->getContent(), true);

        $produitDTO = new ProduitDTO(
            null,
            $data['code'],
            $data['name'],
            $data['description'],
            $data['image'],
            $data['category'],
            $data['price'],
            $data['quantity'],
            $data['internalReference'],
            $data['shellId'],
            $data['inventoryStatus'],
            null,
            null,
            null
        );

        $produit = $this->produitService->createProduit($produitDTO);

        return $this->json($produit, Response::HTTP_CREATED); //201 created
    }

    #[Route('/api/products/{id}', methods: ['GET'])]
    public function getProduit(int $id): JsonResponse
    {
        $produitDTO = $this->produitService->getProduit($id);

        if (!$produitDTO) {
            return $this->json(['error' => 'Produit non trouvé'], 404);
        }

        return $this->json($produitDTO); //200
    }

    #[Route('/api/products/{id}', name: 'update_product', methods: ['PUT', 'PATCH'])]
    public function updateProduit(Request $request, int $id): JsonResponse
    {
        $this->checkIfAdmin();

        $data = json_decode($request->getContent(), true);

        $produitDTO = new ProduitDTO(
            null,
            $data['code'],
            $data['name'],
            $data['description'],
            $data['image'],
            $data['category'],
            $data['price'],
            $data['quantity'],
            $data['internalReference'],
            $data['shellId'],
            $data['inventoryStatus'],
            null,
            null,
            null
        );

        return $this->json(
            $this->produitService->updateProduit($id, $produitDTO),
            Response::HTTP_OK
        ); // 200 avec contenu ou 204 (No Content)
    }

    private function checkIfAdmin(): void
    {
        $user = $this->getUser();
        if (!$user || $user->getEmail() !== 'admin@admin.com') {
            throw $this->createAccessDeniedException("Accès réservé seulment à l'administrateur.");
        }
    }
}
