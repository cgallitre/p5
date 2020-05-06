<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Message;
use App\Service\Pagination;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    /**
     * 
     * 
     * @Route("/user", name="user_index")
     *
     * @return void
     */
    public function dashboard (Pagination $pagination)
    {

        // Temporaire à améliorer !!

        $user = $this->getUser();
        $pagination->setEntityClass(Message::class)
        ->setCurrentPage(1);

        return $this->render('user/index.html.twig', [
            'user' => $user,
            'pagination' => $pagination
        ]);
    }

    /**
     * @Route("/user/{slug}/{page}", name="user_show", requirements={"page" : "\d+"})
     */
    public function index(User $user, Pagination $pagination, $page=1)
    {

        // Temporaire à améliorer !!
        
        $pagination->setEntityClass(Message::class)
        ->setCurrentPage($page);

        return $this->render('user/index.html.twig', [
            'user' => $user,
            'pagination' => $pagination
        ]);
    }
}
