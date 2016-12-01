<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Courier;
use AppBundle\Entity\Schedule;
use AppBundle\Forms\ScheduleForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/schedule")
 * Class ScheduleController
 * @package AppBundle\Controller
 */
class ScheduleController extends Controller
{
    /**
     * Вьюха с расписанием курьеров, добавление новой поездки
     *
     * @Route("/", name="schedule")
     * @Template("@App/Schedule/list.html.twig")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return array
     */
    public function indexAction(Request $request)
    {
        $form = $this->createForm(ScheduleForm::class);
        $form->handleRequest($request);
        //Если форма засабмичена и валидна, то создаем поездку в БД
        if ($form->isSubmitted() && $form->isValid()) {
            $this->createSchedule($form);
        }

        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/get_schedule_ajax", name="get_schedule_ajax")
     * @param Request $request
     * @return JsonResponse
     */
    public function getCouriersAction(Request $request)
    {
        $start = $request->get('start');
        $length = $request->get('length');
        $columns = $request->get('columns');
        $order = $request->get('order');
        $scheduleRep = $this->getDoctrine()->getRepository('AppBundle:Schedule');

        $filter = [
            'departureDateFrom' => $request->get('departureDateFrom'),
            'departureDateTo' => $request->get('departureDateTo'),
            'arrivalDateFrom' => $request->get('arrivalDateFrom'),
            'arrivalDateTo' => $request->get('arrivalDateTo'),
        ];

        return new JsonResponse([
            "iTotalDisplayRecords" => $scheduleRep->getTripsCount($filter),
            "data"                 => $scheduleRep->getTrips($start, $length, $filter, $columns, $order[0])
        ]);
    }

    /**
     * @param Form $form
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    private function createSchedule(Form $form)
    {
        $schedule = $form->getData();
        $em = $this->getDoctrine()->getManager();
        $em->persist($schedule);
        $em->flush();

        return $this->redirectToRoute('schedule');
    }


    /**
     * Удаление поездки
     * @Route("/delete_trip", name="delete_trip")
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteAction(Request $request)
    {
        $trip = $this->getDoctrine()->getRepository('AppBundle:Schedule')->find($request->get('id'));
        if ($trip) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($trip);
            $em->flush();

            return new JsonResponse(['result' => 'success']);
        }

        return new JsonResponse(['result' => 'error']);
    }

    /**
     * @Route("/get_disable_dates", name="get_disable_dates")
     * @param Request $request
     * @return JsonResponse
     */
    public function getDisabledDates(Request $request) {
        $courierId = $request->get('courierId');

        if($courierId) {
            $scheduleDates = $this->getDoctrine()->getRepository('AppBundle:Schedule')->getScheduleDates($courierId);
            $aDisableDates = $this->get('app.service.schedule_service')->getDisableDates($scheduleDates);

            return new JsonResponse($aDisableDates);
        }
        return new JsonResponse('error');
    }


    /**
     * Получаем длительность поездки, в зависимости от региона
     * @Route("/get_trip_time", name="get_trip_time")
     * @param Request $request
     * @return JsonResponse
     */
    public function getTripTime(Request $request) {
        $regionId = $request->get('regionId');

        if($regionId) {
            $tripTime = $this->getDoctrine()->getRepository('AppBundle:Region')->find($regionId)->getTripDuration();
            return new JsonResponse($tripTime);
        }

        return new JsonResponse('error');
    }

}
