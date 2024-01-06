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
    #[Route('/product/create/form', name: 'app_product_create_form')]
    public function creationForm(Request $request, EntityManagerInterface $entityManager,Security $security): Response
    {
        $user = $security->getUser();
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
            $product->setOwner($user);
            $entityManager->persist($product);
            $entityManager->flush();

            $products = $entityManager->getRepository(Product::class)->findAll();

            return $this->redirectToRoute('app_products_user', [
                "product" => $product,
                "products" => $products
            ]);
        }

        $products = $entityManager->getRepository(Product::class)->findAll();

        return $this->render('product/game_create_form.html.twig', [
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
    #[Route('/product/user', name: 'app_products_user')]
    public function showUserProducts(Security $security, EntityManagerInterface $entityManager): Response
    {

        $user = $security->getUser();

        $products = $entityManager->getRepository(Product::class)->findBy(['owner' => $user]);

        return $this->render('product/show_user.html.twig', [
            'products' => $products,
        ]);
}
}
