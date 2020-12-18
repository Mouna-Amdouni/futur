<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Repository\UtilisateurRepository;
use Symfony\Component\Validator\Constraints\Date;
use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use App\Form\EnqueteurType;
use App\Entity\Administrateur;
use App\Form\AdminType;
use App\Entity\Enqueteur;
use App\Repository\EnqueteurRepository;
use App\Repository\AdministrateurRepository;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
    /**
    *@Route("/inscriptionAdmin",name="signinAdmin")
    */
    public function signIn(Request $req,UserPasswordEncoderInterface $encoder){
        $admin= new Administrateur();
        $form = $this->createForm(AdminType::class, $admin);
        $form->handleRequest($req);
        if ($form->isSubmitted() && $form->isValid()) {
            $utilisateur=new Utilisateur();
            $encoded = $encoder->encodePassword($admin, $admin->getPassword());
            $admin->setPassword($encoded);
            $utilisateur->setNom($admin->getUsername())
            ->setPrenom(" ")
            ->setDateNaissance(new \DateTime())
            ->setEmail($admin->getEmail())
            ->setTel(12445)
            ->setGenre(1)
            ->setMotDePasse($admin->getPassword())
            ->setAdresse(" ")
            ->setCin(1455)
            ->setPhoto("Hello World !")
            ;
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($admin);
            $entityManager->persist($utilisateur);
            $entityManager->flush();
           // return $this->redirectToRoute('inscription',['id'=>$utilisateur->getId()]);
          // return $this->redirectToRoute('loginA');
        }
        return $this->render('admin/inscription.html.twig', [
            'form' => $form->createView()]);
    }

    /**
     * @Route("/connexion",name="login")
     */
     public function loginAdmin(){
        return $this->render('admin/login.html.twig');
    }
    /**
     * @Route("/logout",name="logout")
     */
    public function logoutAdmin(){
        return $this->redirectToRoute('loginA');
    }

    /**
     * @Route("/comptes",name="acounts")
     */
    public function  comptes(AdministrateurRepository $repa,UtilisateurRepository $repu,EnqueteurRepository $repe){
        $utlisateurs=$repu->findAll();
        $enqueteurs=$repe->findAll();
       // $consultants;
       return $this->render("admin\comptes.html.twig",[
           'users'=>$utlisateurs,'enqueteurs'=>$enqueteurs
       ]);
    }


    public function getimage($photo){
        return \imagecreatefromstring($photo);
    }
     /**
     * @Route("/deleteUSer/{id}",name="deleteUser")
     */
    public function deleteUser(Utilisateur $utilisateur,Request $req){
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($utilisateur);
        $entityManager->flush();
        return $this->redirectToRoute("acounts");
    }

    /**
     * @Route("/deleteEnqueteur/{id}",name="deleteEnqueteur")
     */
    public function deleteEnqueteur(Enqueteur $enqueteur,Request $req){
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($enqueteur);
        $entityManager->flush();
        return $this->redirectToRoute("acounts");
    }
}
