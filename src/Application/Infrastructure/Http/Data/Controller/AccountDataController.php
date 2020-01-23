<?php

namespace App\Application\Infrastructure\Http\Data\Controller;

use GuzzleHttp\Exception\RequestException;
use App\Application\Infrastructure\Http\Data\Utils\EcomController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
/**
 * Class AccountDataController
 * @package App\Infrastructure\Http\Data\Controller
 */
final class AccountDataController extends EcomController
{
    
    /**
    * @param string
    */

    protected $apiKey;
    /**
     *@param SessionInterface $session     
     */
    protected $session;

    /** 
     * Login route 
     * @Route("/login", name="data_login")
     * @param Request $request
     */
    public function login(Request $request)
    {
        $name = $request->get('username');
        $password = $request->get('password');
        $client = new \GuzzleHttp\Client(); 
        $content = ["username"=>"{$name}","password"=>"{$password}"];
        try {        
            $response = $client->request('POST',
            EcomController::API_BASE_URL.'login',
            ["form_params"=>$content]) ;
        } catch (RequestException $e) {
            return $this->redirectToRoute('login_view',["error"=>true]);
        }
        $response = json_decode($response->getBody());
        $role = $response->role;
        $apiKey = $response->apiKey;
        $username = $response->username;
        
        $this->session->set('apiKey', $apiKey);
        $this->session->set('role', $role);
        $this->session->set('password', $password);
        $this->session->set('username',$username);
        
        return $this->redirectToRoute('users_list');
    }
    /**
     * Logout route
     * @Route("/logout", name="data_logout") 
     */
    public function logout()
    {
        $this->session->invalidate();
        return $this->redirectToRoute('login_view');
    }

    /**
     * Register account 
     * @Route("/account/register", name="register_account")
     * @param Request $request
     */
    public function register(Request $request)
    {
        $username = $request->get('username');
        $surname = $request->get('surname');
        $name = $request->get('name');
        $password = $request->get('password');
        $role = $request->get('role');
        if($role == 1)
        {
            $role="ROLE_ADMIN";
        }else
        {
            $role="ROLE_USER";
        }
        $client = new \GuzzleHttp\Client(); 
        $content = json_encode(["username"=>"{$username}","password"=>"{$password}","name"=>"{$name}","surname"=>"{$surname}","role"=>"{$role}"]);
       
        try {
            $response = $client->request('POST',
            EcomController::API_BASE_URL.'account/new',
            ["body"=>$content]);
        } catch (ClientException $e) {
            echo Psr7\str($e->getRequest());
            echo Psr7\str($e->getResponse());
        }
        return $this->redirectToRoute('login_view',["registered"=>1]);
    }

    /**
     * Check for username's availability  
     * @Route("/account/check", name="check_username")
     * @param Request $request
     */
    public function checkUsername(Request $request)
    {
        $username = $request->get('username');
       
        $client = new \GuzzleHttp\Client(); 
       
        try {
            $response = $client->request('GET',
            EcomController::API_BASE_URL.'account/check?username='.$username
            );
        } catch (ClientException $e) {
            echo Psr7\str($e->getRequest());
            echo Psr7\str($e->getResponse());
        }
        return new Response($response->getBody());
    }

    /**
     * Admin's credentials changin for any account 
     * @Route("/account/editAccount", name="account_edit")
     * @param Request $request
     */
    public function editAccount(Request $request)
    {
        $username = $request->get('username');
        $name = $request->get('name');
        $surname = $request->get('surname');
        $password = $request->get('password');
        $id = $request->get('id');
        $role = $request->get('role');
        if(strlen($password) == 0 )
        {
            $password = null;
        }
        if($role == 1)
        {
            $role="ROLE_ADMIN";
        }else
        {
            $role="ROLE_USER";
        }
        $client = new \GuzzleHttp\Client(); 
        $content = json_encode([
            'name'=> "{$name}",
            'username'=> "{$username}",
            'surname'=> "{$surname}",
            'apiKey' => "{$this->apiKey}",
            'password'=>"{$password}",
            "role"=>"{$role}"
        ]);
        $response = $client->request('PUT',
        EcomController::API_BASE_URL.'users/'.$id,
        ["body"=>$content,'verify'=>false]);
        
        return new Response($response->getBody());
    }

    /**
     * self credentials changing for user's account 
     * @Route("/account/changeCredentials", name="account_change_credentials")
     * @param Request $request
     */
    public function changeCredentials(Request $request)
    {
        $username = $this->session->get('username');
        $newUsername = $request->get('username');
        $name = $request->get('name');
        $surname = $request->get('surname');
        $newPassword = $request->get('password');
        $password = $this->session->get('password');
        $client = new \GuzzleHttp\Client(); 
        $content = json_encode([
            'name'=> "{$name}",
            'username'=> "{$username}",
            'newUsername'=> "{$newUsername}",
            'surname'=> "{$surname}",
            'apiKey' => "{$this->apiKey}",
            'password'=>"{$password}",
            'newPassword'=>"{$newPassword}",
        ]);
        $response = $client->request('PUT',
        EcomController::API_BASE_URL.'account/changeCredientials',
        ["body"=>$content,'verify'=>false]);
        
        if($response->getBody() == "200"){
            if(strlen($newPassword)>0){
                $this->session->set('password', $newPassword);
            }
            $this->session->set('username', $newUsername);
        }
        return new Response($response->getBody());
    }

    /**
     * Get one's account's details 
     * @Route("/account/details", name="get_my_details")
     */
    public function getOne()
    {
        $client = new \GuzzleHttp\Client(); 

        $content = json_encode([
            'apiKey' => "{$this->apiKey}"
        ]);

        $response = $client->request('GET',
        EcomController::API_BASE_URL.'account/get/'.$this->apiKey,
        ["body"=>$content]);
        
        return new Response($response->getBody());
    }

    /**
     * lists all accounts only for admin's view
     * @Route("/account/getAll", name="get_all_accounts")
     */
    public function getAll()
    {
        $client = new \GuzzleHttp\Client(); 
        $content = json_encode([
            'apiKey' => "{$this->apiKey}"
        ]);
        $response = $client->request('GET',
        EcomController::API_BASE_URL.'users',
        ["body"=>$content]);
        
        return new Response($response->getBody());
    }

    /**
     * Deletes an account only for admin's view
     * @Route("/account/delete", name="account_delete")
     * @param Request $request
     */
    public function deleteAccount(Request $request)
    {
        $id = $request->get('id');

        $client = new \GuzzleHttp\Client(); 
        $content=json_encode([
            'apiKey' => "{$this->apiKey}",
        ]);
        
        $response = $client->request('DELETE',
        EcomController::API_BASE_URL.'users/'.$id,
        ["body"=>$content]);
        
        return new Response($response->getBody());
    }

    /**
     * Deletes one's account 
     * @Route("/account/delete/self", name="self_account_delete")
     * @param Request $request
     */
    public function selfDelete(Request $request)
    {
        $client = new \GuzzleHttp\Client(); 
        
        $content=json_encode([
            'apiKey' => "{$this->apiKey}",
            'username' => "{$this->session->get('username')}",
            'password' => "{$this->session->get('password')}"
        ]);
        
        $response = $client->request('DELETE',
        EcomController::API_BASE_URL.'account/delete',
        ["body"=>$content]);
        
        return new Response($response->getBody());
    }
}
