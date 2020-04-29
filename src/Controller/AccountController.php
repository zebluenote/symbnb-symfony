<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AccountType;
use App\Entity\PasswordUpdate;
use App\Form\RegistrationType;
use App\Form\PasswordUpdateType;
use Symfony\Component\Form\FormError;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends AbstractController
{
    /**
     * @Route("/login", name="account_login")
     * 
     * @return Response
     */
    public function login(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        $lastUsername = $utils->getLastUsername();

        return $this->render('account/login.html.twig', [
            'controller_name' => 'AccountController',
            'hasError' => $error !== null,
            'lastUsername' => $lastUsername
        ]);
    }

    /**
     * @Route("/logout", name="account_logout")
     * 
     * @return void 
     */
    public function logout()
    {
    }

    /**
     * permet d'afficher le formulaire d'inscription
     * 
     * @Route("/register", name="account_register")
     * 
     * @return Response 
     */
    public function register(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hash = $encoder->encodePassword($user, $user->getHash());
            $user->setHash($hash);
            $manager->persist($user);
            $manager->flush();
            $this->addFlash(
                'success',
                "Votre compte a été créé, vous pouvez maintenant vous connecter sur notre site !"
            );
            return $this->redirectToRoute('account_login');
        }

        return $this->render('account/registration.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * permet d'afficher et de traiter le formulaire de modification du profil utilisateur
     * 
     * @Route("/account/profile", name="account_profile")
     * @IsGranted("ROLE_USER")
     * 
     * @return Response
     */
    public function profile(Request $request, EntityManagerInterface $manager)
    {

        // Récupération du user actuellement connecté
        $user = $this->getUser();

        $form = $this->createForm(AccountType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($user);
            $manager->flush();
            $this->addFlash(
                'success',
                "Votre profil a été modifié avec succès."
            );
            // return $this->redirectToRoute('account_login');
        }

        return $this->render('account/profile.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * permet de modifier le mot de passe
     * 
     * @Route("/account/password-update", name="account_password")
     * @IsGranted("ROLE_USER")
     * 
     * @return Response
     */
    public function updatePassword(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
    {
        $passwordUpdate = new PasswordUpdate();

        // Récupération du user actuellement identifié
        $user = $this->getUser();

        $form = $this->createForm(PasswordUpdateType::class, $passwordUpdate);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            // 1. Vérifier que l'ancien password a été correctement saisi
            if (false === password_verify($passwordUpdate->getOldPassword(), $user->gethash())) {
                $form->get('oldPassword')->addError(new FormError("Vous n'avez pas saisi correctement votre mot de passe actuel"));
            }

            // 2. Vérification de la validité du formulaire (soit erreur ci-dessus, soit autre erreur de saisie)
            if ($form->isValid()) {
                $newpassword = $passwordUpdate->getNewPassword();
                $hash = $encoder->encodePassword($user, $newpassword);
                $user->setHash($hash);

                $manager->persist($user);
                $manager->flush();

                $this->addFlash(
                    'success',
                    "Votre mot de passe a été modifié"
                );

                return $this->redirectToRoute('homepage');
            }
        }

        return $this->render('account/password.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet d'afficher la page du compte de l'utilisateur identifié
     * 
     * @Route("/account", name="account_index")
     * @IsGranted("ROLE_USER")
     * 
     * @return Response
     */
    public function myAccount()
    {
        return $this->render('user/index.html.twig', [
            'user' => $this->getUser()
        ]);
    }
}
