<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/product', name: 'app_product')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ProductController.php',
        ]);
    }
    #[Route('/product/create/form', name: 'app_product_create_form')]
    public function creationForm(Request $request, EntityManagerInterface $entityManager): Response
    {
        $product = new Product();
        $form = $this->createFormBuilder($product)
            ->add('name', )
            ->add('price', )
            ->add('platform', )
            ->add('publisher', )
            ->add('save', SubmitType::class, ['label' => 'Add game'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();
            $entityManager->persist($product);
            $entityManager->flush();

            $products = $entityManager->getRepository(Product::class)->findAll();

            return $this->redirectToRoute('app_products', [
                "product" => $product,
                "products" => $products
            ]);
        }

        $products = $entityManager->getRepository(Product::class)->findAll();

        return $this->render('product/add_game.html.twig', [
            'form' => $form->createView(),
            "product" => $product,
            "products" => $products
        ]);
    }
    #[Route('/product/show_all', name: 'app_products')]
    public function showAllProducts(EntityManagerInterface $entityManager): Response
    {
        $products = $entityManager->getRepository(Product::class)->findAll();

        return $this->render('product/show_all.html.twig', [
            'products' => $products,
        ]);
    }
}
