<?php

// src/Controller/DashboardController.php

namespace App\Controller;

use Conduction\CommonGroundBundle\Service\CommonGroundService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


/**
 * The DashboardController test handles any calls that have not been picked up by another test, and wel try to handle the slug based against the wrc.
 *
 * Class DashboardController
 *
 * @Route("/dashboard/organization")
 */
class DashboardOrganizationController extends AbstractController
{
    /**
     * @Route("/")
     * @Template
     */
    public function indexAction(CommonGroundService $commonGroundService, Request $request)
    {
        $variables = [];

        // On an index route we might want to filter based on user input
        $variables['query'] = array_merge($request->query->all(), $variables['post'] = $request->request->all());

        return $variables;
    }

    /**
     * @Route("/tutorials")
     * @Template
     */
    public function tutorialsAction(CommonGroundService $commonGroundService, Request $request)
    {
        $variables = [];

        // On an index route we might want to filter based on user input
        $variables['query'] = array_merge($request->query->all(), $variables['post'] = $request->request->all());

        // Get resource tutorials (known as cources component side)
        $variables['tutorials'] = $commonGroundService->getResource(['component' => 'edu', 'type' => 'courses'], $variables['query'])['hydra:member'];

        return $variables;
    }

    /**
     * @Route("/tutorials/{id}")
     * @Template
     */
    public function tutorialAction(CommonGroundService $commonGroundService, Request $request, $id)
    {
        $variables = [];

        if ($id != 'new') {
            // Get resource challenges (known as tender component side)
            $variables['tutorial'] = $commonGroundService->getResource(['component' => 'edu', 'type' => 'courses', 'id'=>$id]);
            $variables['participants'] = $commonGroundService->getResourceList(['component' => 'edu', 'type' => 'participants'],['courses.id' => $id])['hydra:member'];
        } else {
            $variables['tutorial'] = [];
        }

        // Lets see if there is a post to procces
        if ($request->isMethod('POST')) {
            $resource = $request->request->all();

            // Add the post data to the already aquired resource data
            $resource = array_merge($variables['resource'], $resource);

            // Update to the commonground component
            $variables['tutorial'] = $commonGroundService->saveResource($resource, ['component' => 'edu', 'type' => 'courses', 'id' => $id]);
        }

        return $variables;
    }

    /**
     * @Route("/internships")
     * @Template
     */
    public function internshipsAction(CommonGroundService $commonGroundService, Request $request)
    {
        $variables = [];

        // On an index route we might want to filter based on user input
        $variables['query'] = array_merge($request->query->all(), $variables['post'] = $request->request->all());

        // Get resources Interschips
        $variables['internships'] = $commonGroundService->getResource(['component' => 'mrc', 'type' => 'job_postings'], $variables['query'])['hydra:member'];

        // Lets see if there is a post to procces
        if ($request->isMethod('POST')) {
            //array legen voor posten van nieuwe stage
            $variables['internship'] = [];
            //array waar mn form inzit
            $resource = $request->request->all();

            $resource['standardHours'] = (int) $resource['standardHours'];

            // Add the post data to the already aquired internship data
            $resource = array_merge($variables['internship'], $resource);

            // Save to the commonground component
            $variables['internship'] = $commonGroundService->saveResource($resource, ['component' => 'mrc', 'type' => 'job_postings']);
        }

        return $variables;
    }

    /**
     * @Route("/internships/{id}")
     * @Template
     */
    public function internshipAction(CommonGroundService $commonGroundService, Request $request, $id)
    {
        $variables = [];
        // On an index route we might want to filter based on user input
        $variables['query'] = array_merge($request->query->all(), $variables['post'] = $request->request->all());

        // Get resource Interschip
        if ($id != 'new') {
            $variables['internship'] = $commonGroundService->getResource(['component' => 'mrc', 'type' => 'job_postings', 'id'=>$id]);
        } else {
            $variables['internship'] = [];
            //Get resources Organizations
            $variables['organizations'] = $commonGroundService->getResource(['component' => 'wrc', 'type' => 'organizations'], $variables['query'])['hydra:member'];
        }

        return $variables;
    }

    /**
     * @Route("/challenges")
     * @Template
     */
    public function challengesAction(Request $request, CommonGroundService $commonGroundService)
    {
        $variables = [];

        // On an index route we might want to filter based on user input
        $variables['query'] = array_merge($request->query->all(), $variables['post'] = $request->request->all());

        // Get resource challenges (known as tender component side)
        $variables['challenges'] = $commonGroundService->getResource(['component' => 'chrc', 'type' => 'tenders'], $variables['query'])['hydra:member'];

        return $variables;
    }

    /**
     * @Route("/challenges/{id}")
     * @Template
     */
    public function challengeAction(Request $request, CommonGroundService $commonGroundService, $id)
    {
        $variables = [];

        if ($id != 'new') {
            // Get resource challenges (known as tender component side)
            $variables['challenge'] = $commonGroundService->getResource(['component' => 'chrc', 'type' => 'tenders', 'id'=>$id]);
            $variables['proposals'] = $commonGroundService->getResourceList(['component' => 'chrc', 'type' => 'proposals'],['tender.id' => $id])['hydra:member'];
        } else {
            $variables['challenge'] = [];
        }

        // Lets see if there is a post to procces
        if ($request->isMethod('POST')) {
            $resource = $request->request->all();

            // Add the post data to the already aquired resource data
            $resource = array_merge($variables['challenge'], $resource);

            // Update to the commonground component
            $variables['challenge'] = $commonGroundService->saveResource($resource, ['component' => 'chrc', 'type' => 'tenders', 'id' => $id]);

            return $this->redirect($this->generateUrl('app_dashboardorganization_challenges'));
        }
        return $variables;
    }

    /**
     * @Route("/teams")
     * @Template
     */
    public function teamsAction(CommonGroundService $commonGroundService, Request $request)
    {
        $variables = [];

        // On an index route we might want to filter based on user input
        $variables['query'] = array_merge($request->query->all(), $variables['post'] = $request->request->all());

        $variables['teams'] = [];

        return $variables;
    }

    /**
     * @Route("/teams/{id}")
     * @Template
     */
    public function teamAction(CommonGroundService $commonGroundService, Request $request, $id)
    {
        $variables = [];

        return $variables;
    }

    /**
     * @Route("/settings")
     * @Template
     */
    public function settingsAction(CommonGroundService $commonGroundService, Request $request)
    {
        $variables = [];

        return $variables;
    }
}
