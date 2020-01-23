<?php

namespace App\Application\Infrastructure\Http\View\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ViewController
 * @package App\Infrastructure\Http\View\Controller
 */
final class AccountViewController extends AbstractController
{   
   /**
     * Render login view 
     * @Route("/login", name="login_view")
     */
    public function loginForm(){
        return $this->render('login/login.html.twig');
    }

     /**
     * Render register view
     * @Route("/register", name="register_view")
     */
    public function registerForm(){
        return $this->render('register/register.html.twig');
    }

    /**
     * Render account view or users list depending on user's role
     * @Route("/users", name="users_list")
     */
    public function usersIndex(){
        if($this->get('session')->get('role') == "ROLE_ADMIN"){
            return $this->render('users/users.html.twig');
        }else{
            return $this->render('account/account.html.twig');
        }
    }
}
