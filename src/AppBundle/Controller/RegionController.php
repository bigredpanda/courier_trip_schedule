<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Region;
use AppBundle\Forms\RegionForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/region")
 * Class ScheduleController
 * @package AppBundle\Controller
 */
class RegionController extends Controller
{
    /**
     * Вьюха со списком регионов, добавление нового региона
     * @Route("/", name="region_list")
     * @Method({"GET", "POST"})
     * @Template("@App/Region/list.html.twig")
     * @param Request $request
     * @return array
     */
    public function indexAction(Request $request)
    {
        $form = $this->createForm(RegionForm::class);
        $form->handleRequest($request);
        //Если форма засабмичена и валидна, то создаем регион в БД
        if ($form->isSubmitted() && $form->isValid()) {
            $region = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($region);
            $em->flush();

            return $this->redirectToRoute('region_list');
        }

        return [
            'form' => $form->createView()
        ];
    }

    /**
     * Получаем регионы
     * @Route("/get_regions_ajax", name="get_regions_ajax")
     * @param Request $request
     * @return JsonResponse
     */
    public function getRegionsAction(Request $request)
    {
        $start = $request->get('start');
        $length = $request->get('length');
        $regionRep = $this->getDoctrine()->getRepository('AppBundle:Region');

        return new JsonResponse([
            "iTotalDisplayRecords" => $regionRep->getRegionsCount(),
            "data"                 => $regionRep->getRegions($start, $length)
        ]);
    }

    /**
     * Удаление региона
     * @Route("/delete_region", name="delete_region")
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteAction(Request $request)
    {
        $region = $this->getDoctrine()->getRepository('AppBundle:Region')->find($request->get('id'));
        if($region) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($region);
            $em->flush();

            return new JsonResponse(['result' => 'success']);
        }

        return new JsonResponse(['result' => 'error']);
    }
}
