<?php
namespace App\Controller;

use App\Entity\Person;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class HelloController extends AbstractController
{
    /**
     * @Route("/log", name="log")
     */
    public function log(Request $request, LoggerInterface $logger)
    {
        $data = array (
            'name'=>array('first'=>'Taro','second'=>'Yamada'),
            'age'=>36, 'mail'=>'taro@yamada.kun'
        );
        $logger->info(serialize($data));
        return new JsonResponse($data);
    }

    /**
     * @Route("/hello", name="hello")
     */
    public function index(Request $request)
    {
        $repository = $this->getDoctrine()
            ->getRepository(Person::class);

        $data = $repository->findAll();
        return $this->render('hello/index.html.twig', [
            'title' => 'Hello',
            'data' => $data,
        ]);
    }

    /**
     * @Route("/notfound", name="notfound")
     */
    public function notfound(Request $request)
    {
        $content = <<< EOM
        <html>
        <head><title>ERROR</title></head>
        <body>
        <h1>ERROR! 404</h1>
        </body>
        </html>
EOM;
        $response = new Response(
            $content,
            Response::HTTP_NOT_FOUND,
            array('content-type' => 'text/html')
        );
        return $response;
    }

    /**
     * @Route("/error", name="error")
     */
    public function error(Request $request)
    {
        $content = <<< EOM
        <html>
        <head><title>ERROR</title></head>
        <body>
        <h1>ERROR! 500</h1>
        </body>
        </html>
EOM;
        $response = new Response(
            $content,
            Response::HTTP_INTERNAL_SERVER_ERROR,
            array('content-type' => 'text/html')
        );
        return $response;
    }

    /**
     * @Route("/other/{domain}", name="other")
     */
    public function other(Request $request, $domain='')
    {
        if ($domain == '') {
            return $this->redirect('/hello');
        } else {
            return new RedirectResponse("http://{$domain}.com");
        }
    }
}
