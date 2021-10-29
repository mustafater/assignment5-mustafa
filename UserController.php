<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\UsersType;

use App\Entity\Users;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user")
     */
    public function index(): Response
    {
        $users = $this->getDoctrine()
                      ->getRepository(Users::class)  
                      ->findAll();


        return $this->render('user/index.html.twig', [
            'users' => $users ,
        ]);
    }

    /**
     * @Route("/newuser", name="newuser" , methods="GET|POST")
     */
    public function newUser(Request $request): Response
    {
        $users = new Users();
        $form = $this->createForm( UsersType::class, $users);
        $form->handleRequest ($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($users);

            $entityManager->flush();
            return $this->redirectToRoute('user');
            
        }    

        return $this->render('user/newuser.html.twig', [
            'form' => $form->createView() ,
        ]);
    }


    /**
     * @Route("/edituser/{id}", name="edituser" , methods="GET|POST")
     */
    public function editUser(Request $request, Users $users): Response
    {
        //params
        
        $form = $this->createForm( UsersType::class, $users);
        $form->handleRequest ($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($users);

            $entityManager->flush();
            return $this->redirectToRoute('user');
            
        }    

        return $this->render('user/edituser.html.twig', [
            'form' => $form->createView() ,
            'user' =>$users,

        ]);
    }

     /**
     * @Route("delete/{id}", name="delete",  requirements={"id"="\d+"})
     * 
     */
    public function deleteUser(Users $users):Response
    {   
       // $form = $this->createForm( UsersType::class, $users);
      //  $form->handleRequest ($request);


        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($users);
        $entityManager->flush();

        $users = $this->getDoctrine()
        ->getRepository(Users::class)  
        ->findAll();
        return $this->render('user/index.html.twig' , [
            'users'=>$users ,
        ]
    );

    }
}
