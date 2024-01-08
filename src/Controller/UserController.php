<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController{

    #[Route('/login', name: 'app_login')]
    public function index(AuthenticationUtils $authdUtils): Response{
    $error = $authdUtils->getLastAuthenticationError();
    $last_Username = $authdUtils->getLastUsername();

    return $this->render('user/index.html.twig', ['error' => $error, 'last_username' => $last_Username]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout($security): Response{
    $security->logout(false);
    return $this->redirectToRoute('app_login');
    }

    #[Route('/user/show_all', name: 'app_users')]
    #[IsGranted('ROLE_Admin')]
    public function showAllUsers(EntityManagerInterface $entityManager): Response
    {
        $users = $entityManager->getRepository(user::class)->findAll();

        return $this->render('user/show_all.html.twig', [
            'users' => $users,
        ]);
    }
    #[Route('/user/delete/{id}', name: 'app_user_delete')]
    #[IsGranted('ROLE_Admin')]
    public function delete(EntityManagerInterface $entityManager, User $user): Response
    {
        $entityManager->remove($user);
        $entityManager->flush();

        return $this -> redirectToRoute("app_users");
    }
}
