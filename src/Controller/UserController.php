<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

//use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;

class UserController extends AbstractController
{
    
    // public function index(PersistenceManagerRegistry $doctrine){

    //     $users = $doctrine->getRepository(User::class)->findAll();
    //     return $this->json($users);
    // }
    

    // public function show(PersistenceManagerRegistry $doctrine,int $id){

    //     $users = $doctrine->getRepository(User::class)->find($id);

    //     if(!$users){
    //         return new JsonResponce(['error' => 'User not found'], 404);
    //     }

    //     return $this->json($users);
    // }

    public function create(EntityManagerInterface $entityManager,Request $request): JsonResponse
    {
       
        //Récupère les données JSON de la requete
        $data = json_decode($request->getContent(),true);

        //Crée un nouvel utilisateur
        $user = new User();
        
        $user->setId($data['id']);
        $user->setUsername($data['username']);
        $user->setEmail($data['email']);
        $user->setPassword($data['password']);
        $user->setRoles($data['roles'] ?? ['ROLE_USER']);

        //Enregistre l'utilisateur dans la base de données
        $entityManager->persist($user);
        $entityManager->flush();//push l'utilisateur dans la base de données

        //Retourne une confirmation de création
        return new JsonResponse(['status' => 'User created'], JsonResponse::HTTP_CREATED);
    }

    public function show(User $user): JsonResponse
    {

        //Crée un nouvel utilisateur
       $data = 
       [
        'id' => $user->getID(),
        'username' => $user->getUsername(),
        'email' => $user->getEmail(),
        'password'=> $user->getPassword(),
        'roles' => $user ->getRoles(),
       ];
        //Retourne une confirmation de création
        return new JsonResponse($data, JsonResponse::HTTP_OK);
    }

    public function update(EntityManagerInterface $entityManager,User $user,Request $request): JsonResponse
    {
       
        //Récupère les données JSON de la requete
        $data = json_decode($request->getContent(),true);

        //Crée un nouvel utilisateur
        $user = new User();
        
        if(isset($data['id'])){
            $user->setId($data['id']);
        }
        if(isset($data['username'])){
            $user->setUsername($data['username']);
        }
        if(isset($data['email'])){
            $user->setEmail($data['email']);
        }
        if(isset($data['password'])){
            $user->setPassword($data['password']);
        }
        if(isset($data['roles'])){
            $user->setRoles($data['roles'] ?? ['ROLE_USER']);
        }

        //Enregistre l'utilisateur dans la base de données
        $entityManager->flush();//push l'utilisateur dans la base de données
        
        //Retourne une confirmation de création
        return new JsonResponse(['status' => 'User updated'], JsonResponse::HTTP_OK);
    }
    
}
