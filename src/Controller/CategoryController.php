<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class CategoryController extends AbstractController
{
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
    public function createCategory(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $category = new Category();
        $category->setNom($data['nom']);

        $entityManager->persist($category);
        $entityManager->flush();

        return new JsonResponse(['status' => 'Category created'], Response::HTTP_CREATED);
    }

    // PUT - Mettre à jour une catégorie
    #[Route('/category/{id}', name: 'update_category', methods: ['PUT'])]
    public function updateCategory(int $id, Request $request, CategoryRepository $categoryRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $category = $categoryRepository->find($id);

        if (!$category) {
            return new JsonResponse(['error' => 'Category not found'], Response::HTTP_NOT_FOUND);
        }

        $category->setNom($data['nom']);
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
