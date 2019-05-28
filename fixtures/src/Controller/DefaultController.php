<?php


namespace App\Controller;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DefaultController
 *
 * @package App\Controller
 */
class DefaultController
{
    /**
     * @Route(
     *     path="/",
     *     name="homepage"
     * )
     */
    public function index()
    {
        return new Response('<html><body>Hello World</body></html>',200);
    }
}