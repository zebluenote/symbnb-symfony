<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\Admin\AdminCommentType;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminCommentController extends AbstractController
{
    /**
     * Permet d'afficher la liste des commentaires
     * 
     * @Route("/admin/comments", name="admin_comments_index")
     * 
     * @return Response 
     * @throws LogicException 
     */
    public function index(CommentRepository $repo)
    {
        $comments = $repo->findAll();

        return $this->render('admin/comment/index.html.twig', [
            'comments' => $comments
        ]);
    }

    /**
     * Permet de modifier le contenu d'un commentaire
     * 
     * @Route("/admin/comment/{id}/edit", name="admin_comment_edit")
     * 
     * @param Comment $comment 
     * @param Request $request 
     * @param EntityManagerInterface $manager 
     * @return RedirectResponse 
     */
    public function edit(Comment $comment, Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(AdminCommentType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($comment);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le commentaire num. {$comment->getId()} a été modifié"
            );

            return $this->redirectToRoute('admin_comments_index');
        }

        return $this->render('admin/comment/edit.html.twig', [
            'form' => $form->createView(),
            'comment' => $comment
        ]);
    }

    /**
     * Permet de supprimer un commentaire
     * 
     * @Route("/admin/comment/{id}/delete", name="admin_comment_delete")
     * 
     * @param Comment $comment 
     * @param EntityManagerInterface $manager 
     * @return RedirectResponse 
     * @throws LogicException 
     */
    public function delete(Comment $comment, EntityManagerInterface $manager) 
    {
        $commentId = $comment->getId();
    
        $manager->remove($comment);
        $manager->flush();

        $this->addFlash(
            'success',
            "Le commentaire n°{$commentId} de {$comment->getAuthor()->getfullName()} a été supprimé"
        );

        return $this->redirectToRoute('admin_comments_index');

    }
}
