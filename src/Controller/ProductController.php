<?php
namespace App\Controller;

use App\Entity\Product;
use App\Entity\Category;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProductController extends AbstractController
{
    // GET - Lister tous les produits
    #[Route('/products', name: 'get_products', methods: ['GET'])]
    public function getProducts(ProductRepository $productRepository): JsonResponse
    {
        $products = $productRepository->findAll();
        $data = [];

        foreach ($products as $product) {
            $data[] = [
                'id' => $product->getId(),
                'nom' => $product->getNom(),
                'description' => $product->getDescription(),
                'prix' => $product->getPrix(),
                'category_id' => $product->getCategory() ? $product->getCategory()->getId() : null,
                'date_de_creation' => $product->getDateDeCreation()->format('Y-m-d H:i:s'),
            ];
        }

        return new JsonResponse($data);
    }

    // POST - Créer un produit
    #[Route('/product', name: 'create_product', methods: ['POST'])]
    public function createProduct(Request $request, EntityManagerInterface $entityManager, CategoryRepository $categoryRepository, ValidatorInterface $validator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Validation des données envoyées
        $product = new Product();
        $product->setNom($data['nom']);
        $product->setDescription($data['description']);
        $product->setPrix($data['prix']);
        $category = $categoryRepository->find($data['category_id']);
        $product->setCategory($category);

        $errors = $validator->validate($product);

        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            return new JsonResponse(['errors' => $errorMessages], Response::HTTP_BAD_REQUEST);
        }

        $entityManager->persist($product);
        $entityManager->flush();

        return new JsonResponse(['status' => 'Product created'], Response::HTTP_CREATED);
    }

    // PUT - Mettre à jour un produit
    #[Route('/product/{id}', name: 'update_product', methods: ['PUT'])]
    public function updateProduct(int $id, Request $request, ProductRepository $productRepository, EntityManagerInterface $entityManager, CategoryRepository $categoryRepository, ValidatorInterface $validator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $product = $productRepository->find($id);

        if (!$product) {
            return new JsonResponse(['error' => 'Product not found'], Response::HTTP_NOT_FOUND);
        }

        $product->setNom($data['nom']);
        $product->setDescription($data['description']);
        $product->setPrix($data['prix']);
        $category = $categoryRepository->find($data['category_id']);
        $product->setCategory($category);

        // Validation des données
        $errors = $validator->validate($product);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            return new JsonResponse(['errors' => $errorMessages], Response::HTTP_BAD_REQUEST);
        }

        $entityManager->flush();

        return new JsonResponse(['status' => 'Product updated']);
    }

    // DELETE - Supprimer un produit
    #[Route('/product/{id}', name: 'delete_product', methods: ['DELETE'])]
    public function deleteProduct(int $id, ProductRepository $productRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $product = $productRepository->find($id);

        if (!$product) {
            return new JsonResponse(['error' => 'Product not found'], Response::HTTP_NOT_FOUND);
        }

        $entityManager->remove($product);
        $entityManager->flush();

        return new JsonResponse(['status' => 'Product deleted'], Response::HTTP_NO_CONTENT);
    }

    // GET - Lister toutes les catégories
    #[Route('/categories', name: 'get_categories', methods: ['GET'])]
    public function getCategories(CategoryRepository $categoryRepository): JsonResponse
    {
        $categories = $categoryRepository->findAll();
        $data = [];

        foreach ($categories as $category) {
            $data[] = [
                'id' => $category->getId(),
                'nom' => $category->getNom(),
            ];
        }

        return new JsonResponse($data);
    }

    // POST - Créer une catégorie
    #[Route('/category', name: 'create_category', methods: ['POST'])]
    public function createCategory(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $category = new Category();
        $category->setNom($data['nom']);

        // Validation
        $errors = $validator->validate($category);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            return new JsonResponse(['errors' => $errorMessages], Response::HTTP_BAD_REQUEST);
        }

        $entityManager->persist($category);
        $entityManager->flush();

        return new JsonResponse(['status' => 'Category created'], Response::HTTP_CREATED);
    }

    // PUT - Mettre à jour une catégorie
    #[Route('/category/{id}', name: 'update_category', methods: ['PUT'])]
    public function updateCategory(int $id, Request $request, CategoryRepository $categoryRepository, EntityManagerInterface $entityManager, ValidatorInterface $validator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $category = $categoryRepository->find($id);

        if (!$category) {
            return new JsonResponse(['error' => 'Category not found'], Response::HTTP_NOT_FOUND);
        }

        $category->setNom($data['nom']);

        // Validation
        $errors = $validator->validate($category);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            return new JsonResponse(['errors' => $errorMessages], Response::HTTP_BAD_REQUEST);
        }

        $entityManager->flush();

        return new JsonResponse(['status' => 'Category updated']);
    }

    // DELETE - Supprimer une catégorie
    #[Route('/category/{id}', name: 'delete_category', methods: ['DELETE'])]
    public function deleteCategory(int $id, CategoryRepository $categoryRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $category = $categoryRepository->find($id);

        if (!$category) {
            return new JsonResponse(['error' => 'Category not found'], Response::HTTP_NOT_FOUND);
        }

        $entityManager->remove($category);
        $entityManager->flush();

        return new JsonResponse(['status' => 'Category deleted'], Response::HTTP_NO_CONTENT);
    }
}
