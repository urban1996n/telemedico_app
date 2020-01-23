<?php
namespace App\Application\Infrastructure\Http\Data\Utils;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use GuzzleHttp\Exception\RequestException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

Class EcomController extends AbstractController
{   
    const API_BASE_URL = "http://localhost/app/api/public/index.php/api/";// http://178.19.96.101/

    /**
     * @param string
     */
    protected $apiKey;
    
    /**
     *@param SessionInterface $session     
     */
    protected $session;
    
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;

        if($session->get('apiKey'))
        {
            $this->apiKey = $session->get('apiKey');
        }
    }
}