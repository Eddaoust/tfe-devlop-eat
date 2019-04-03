<?php

namespace App\Controller;

use App\Form\UserUpdateType;
use App\Form\PasswordResetType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends Controller
{
    /**   
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/log/account", name="account_one")
     */
    public function account()
    {
        $user = $this->getUser();

        return $this->render('account/account.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * @param Request $request
     * @param ObjectManager $manager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/log/account/edit", name="account_update")
     */
    public function editAccount(Request $request, ObjectManager $manager)
    {
        $user = $this->getUser();

        $form = $this->createForm(UserUpdateType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
			//TODO Impossible de modifier l'image quand on est l'utilisateur connecté
            $data = $form->getData();
            $image = $data->getImage();
            // Test de la présence d'une image envoyé via le form
            if (!is_null($image) && !is_null($image->getFile()))
            {
                $file = $image->getFile();
                // Test si l'utilisateur contient déja une image
                if (!is_null($user->getImage()->getName()))
                {
                    $path = $this->getParameter('user_images_directory').'/'.$user->getImage()->getName();
                    // Suppression de l'ancienne image
                    unlink($path);
                }
                $fileName = md5(uniqid()).'.'.$file->guessExtension();
                $file->move(
                    $this->getParameter('user_images_directory'),
                    $fileName);
                $image->setName($fileName);
            }
            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success', 'Les informations de votre compte ont été modifié avec succès');
            return $this->redirectToRoute('account_one');
        }

        return $this->render('user/user_update.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param ObjectManager $manager
     * @param UserPasswordEncoderInterface $encoder
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/log/account/password", name="account_password")
     */
    public function editPassword(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {
        $user = $this->getUser();
        $form = $this->createForm(PasswordResetType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $user->setPassword($encoder->encodePassword($user, $user->getPassword()));
            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success', 'Votre password a été modifié avec succès');
            return $this->redirectToRoute('account_one');
        }

        return $this->render('user/user_password.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
