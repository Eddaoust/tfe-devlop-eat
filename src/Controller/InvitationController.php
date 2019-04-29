<?php

namespace App\Controller;

use App\Entity\Invitation;
use App\Repository\InvitationRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class InvitationController
 * @package App\Controller
 * @IsGranted("ROLE_ADMIN")
 */
class InvitationController extends Controller
{
    //TODO Modifier le système d'invitation en faisant apparaitre le status et ne pas supprimer l'user confirmé
    //TODO Envoyer un mail lors de la suppression de compte
    /**
     * @Route("/admin/invitation/list", name="invitation_list")
     */
    public function invitationList(InvitationRepository $repo)
    {
        $invitations = $repo->findAll();

        return $this->render('invitation/invitation_list.html.twig', [
            'invitations' => $invitations
        ]);
    }

    /**
     * @Route("/admin/invitation/delete/{id}", name="invitation_delete")
     */
    public function invitationDelete(Invitation $invitation, ObjectManager $manager)
    {
        $user = $invitation->getUser();
        $manager->remove($user);
        $manager->remove($invitation);
        $manager->flush();

        return $this->redirectToRoute('invitation_list');
    }
}
