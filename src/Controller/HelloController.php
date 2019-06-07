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


use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;


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
     * @Route("/find/{id}", name="find")
     */
    public function find(Request $request, Person $person)
    {
        return $this->render('hello/find.html.twig', [
            'title' => 'Hello',
            'data' => $person,
        ]);
    }


    /**
     * @Route("/create", name="create")
     */
    public function create(Request $request)
    {
        $person = new Person();
        $form = $this->createFormBuilder($person)
            ->add('name', TextType::class)
            ->add('mail', TextType::class)
            ->add('age', IntegerType::class)
            ->add('save', SubmitType::class, array('label' => 'Click'))
            ->getForm();


        if ($request->getMethod() == 'POST'){
            $form->handleRequest($request);
            $person = $form->getData();
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($person);
            $manager->flush();
            return $this->redirect('/hello');
        } else {
            return $this->render('hello/create.html.twig', [
                'title' => 'Hello',
                'message' => 'Create Entity',
                'form' => $form->createView(),
            ]);
        }
    }


    /**
     * @Route("/update/{id}", name="update")
     */
    public function update(Request $request, Person $person)
    {
        $form = $this->createFormBuilder($person)
            ->add('name', TextType::class)
            ->add('mail', TextType::class)
            ->add('age', IntegerType::class)
            ->add('save', SubmitType::class, array('label' => 'Click'))
            ->getForm();


        if ($request->getMethod() == 'POST'){
            $form->handleRequest($request);
            $person = $form->getData();
            $manager = $this->getDoctrine()->getManager();
            $manager->flush();
            return $this->redirect('/hello');
        } else {
            return $this->render('hello/create.html.twig', [
                'title' => 'Hello',
                'message' => 'Update Entity id=' . $person->getId(),
                'form' => $form->createView(),
            ]);
        }
    }


    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(Request $request, Person $person)
    {
        $form = $this->createFormBuilder($person)
            ->add('name', TextType::class)
            ->add('mail', TextType::class)
            ->add('age', IntegerType::class)
            ->add('save', SubmitType::class, array('label' => 'Click'))
            ->getForm();


        if ($request->getMethod() == 'POST'){
            $form->handleRequest($request);
            $person = $form->getData();
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($person);
            $manager->flush();
            return $this->redirect('/hello');
        } else {
            return $this->render('hello/create.html.twig', [
                'title' => 'Hello',
                'message' => 'Delete Entity id=' . $person->getId(),
                'form' => $form->createView(),
            ]);
        }
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


class FindForm
{
    private $find;


    public function getFind()
    {
        return $this->find;
    }
    public function setFind($find)
    {
        $this->find = $find;
    }
}