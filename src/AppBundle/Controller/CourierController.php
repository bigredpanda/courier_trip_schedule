<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Courier;
use AppBundle\Forms\CourierForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CourierController
 * @Route("/couriers")
 * @package AppBundle\Controller
 */
class CourierController extends Controller
{

    /**
     * Список курьеров
     *
     * @Route("/", name="couriers_list")
     * @Template("@App/Courier/list.html.twig")
     * @Method({"GET","POST"})
     * @param Request $request
     * @return array
     */
    public function indexAction(Request $request)
    {
        $form = $this->createForm(CourierForm::class);
        $form->handleRequest($request);
        //Если форма засабмичена и валидна, то создаем курьера в БД
        if ($form->isSubmitted() && $form->isValid()) {
            $courier = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($courier);
            $em->flush();

            return $this->redirectToRoute('couriers_list');
        }

        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/get_couriers_ajax", name="get_couriers_ajax")
     * @param Request $request
     * @return JsonResponse
     */
    public function getCouriersAction(Request $request)
    {
        $start = $request->get('start');
        $length = $request->get('length');
        $courierRep = $this->getDoctrine()->getRepository('AppBundle:Courier');

        return new JsonResponse([
            "iTotalDisplayRecords" => $courierRep->getCouriersCount(),
            "data"                 => $courierRep->getCouriers($start, $length)
        ]);
    }

    /**
     * Удаление курьера
     * @Route("/delete_courier", name="delete_courier")
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteAction(Request $request)
    {
        $courier = $this->getDoctrine()->getRepository('AppBundle:Courier')->find($request->get('id'));
        if ($courier) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($courier);
            $em->flush();

            return new JsonResponse(['result' => 'success']);
        }

        return new JsonResponse(['result' => 'error']);
    }
}
