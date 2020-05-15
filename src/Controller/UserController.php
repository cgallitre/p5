<?php

namespace App\Controller;
 
use App\Entity\MessageSearch;
use App\Form\MessageSearchType;
use App\Repository\MessageRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    /**
     * Tableau de bord client
     * 
     * @Route("/user", name="user_index")
     *
     */
    public function dashboard (MessageRepository $repo, PaginatorInterface $paginator, Request $request)
    {
        $user = $this->getUser();
        $search = new MessageSearch();
        $form = $this->createForm(MessageSearchType::class, $search);
        $form->handleRequest($request);

        $messages = $paginator->paginate(
            $repo->findAllQuery($search),
            $request->query->getInt('page', 1),
            5
        );
    

        return $this->render('user/index.html.twig', [
            'messages' => $messages,
            'user' => $user,
            'form' => $form->createView()
        ]);
    }
}
