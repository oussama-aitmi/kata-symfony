<?php
namespace App\DataFixtures;

use App\Entity\Produit;
use App\Enum\InventoryStatus;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends BaseFixture
{

    protected function loadData(ObjectManager $manager): void
    {
        $this->createMany(15, 'main_product', function($count) use ($manager) {
            $product = new Produit();
            $product->setCode('P' . $this->faker->unique()->numberBetween(100, 999));
            $product->setName($this->faker->word);
            $product->setDescription($this->faker->sentence);
            $product->setImage($this->faker->imageUrl(200, 200, 'product', true));
            $product->setCategory($this->faker->randomElement(['Electronic', 'Livres', 'Vetements', 'immobilier']));
            $product->setPrice($this->faker->randomFloat(2, 10, 100));
            $product->setQuantity($this->faker->numberBetween(1, 50));
            $product->setInternalReference($this->faker->uuid);
            $product->setShellId($this->faker->randomNumber());
            $product->setInventoryStatus([
                InventoryStatus::from($this->faker->randomElement(['INSTOCK', 'LOWSTOCK', 'OUTOFSTOCK'])),
                InventoryStatus::from($this->faker->randomElement(['INSTOCK', 'LOWSTOCK', 'OUTOFSTOCK']))
            ]);
            $product->setRating($this->faker->numberBetween(1, 5));
            $manager->persist($product);

            return $product;
        });

        $manager->flush();
    }

    public function getDependencies()
    {
        // TODO: Implement getDependencies() method.
    }
}
