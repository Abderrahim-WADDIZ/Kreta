<?php

/**
 * This file belongs to Kreta.
 * The source code of application includes a LICENSE file
 * with all information about license.
 *
 * @author benatespina <benatespina@gmail.com>
 * @author gorkalaucirica <gorka.lauzirika@gmail.com>
 */

namespace Kreta\Bundle\Api\ApiCoreBundle\Controller\Base;

use FOS\RestBundle\Request\ParamFetcher;
use Kreta\Component\Core\Model\Interfaces\UserInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ResourceController.
 *
 * @package Kreta\Bundle\Api\ApiCoreBundle\Controller\Base
 */
class ResourceController extends BaseController
{
    /**
     * The name of class.
     *
     * @var string
     */
    protected $class;

    /**
     * The name of bundle.
     *
     * @var string
     */
    protected $bundle;

    /**
     * Returns all the resources, it admits ordering, filter, count and pagination.
     *
     * @param ParamFetcher $paramFetcher The param fetcher
     * @param string[]     $groups       The array of serialization groups
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function getAll(ParamFetcher $paramFetcher, $groups = array())
    {
        $resources = $this->get('kreta_' . $this->bundle . '.repository_' . $this->class)
            ->findAll(
                $paramFetcher->get('order'),
                $paramFetcher->get('q'),
                $paramFetcher->get('count'),
                $paramFetcher->get('page')
            );

        return $this->handleView($this->createView($resources, $groups));
    }

    /**
     * Returns all the resources if the user is authenticated, it admits ordering, count and pagination.
     *
     * @param \Kreta\Component\Core\Model\Interfaces\UserInterface $user         The user object
     * @param ParamFetcher                                         $paramFetcher The param fetcher
     * @param string[]                                             $groups       The array of serialization groups
     * @param string                                               $query        The query, by default 'findAll'
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function getAllAuthenticated(
        UserInterface $user,
        ParamFetcher $paramFetcher,
        $groups = array(),
        $query = 'findAll'
    )
    {
        $resources = $this->get('kreta_' . $this->bundle . '.repository_' . $this->class)
            ->$query(
                $user,
                $paramFetcher->get('order'),
                $paramFetcher->get('count'),
                $paramFetcher->get('page')
            );

        return $this->handleView($this->createView($resources, $groups));
    }

    /**
     * Returns the resource for given id.
     *
     * @param string   $id     The id of the resource
     * @param string[] $groups The serialization groups
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function getOne($id, $groups = array())
    {
        return $this->handleView($this->createView($this->getResourceIfExists($id), $groups));
    }

    /**
     * Returns the resource for given id if exists, otherwise throws the exception.
     *
     * @param string $id The id of the resource
     *
     * @return mixed
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    protected function getResourceIfExists($id)
    {
        $resource = $this->get('kreta_' . $this->bundle . '.repository_' . $this->class)->findOneById($id);
        if ($resource === null) {
            throw new NotFoundHttpException('Does not exist any ' . $this->class . ' with ' . $id . ' id');
        }

        return $resource;
    }

    /**
     * Manage POST and PUT requests with logic of forms returning the response or form's validation errors.
     *
     * @param \Symfony\Component\Form\AbstractType $formType The form of resource object
     * @param mixed                                $resource The object of resource
     * @param string[]                             $groups   The serialization groups
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function manageForm(AbstractType $formType, $resource, $groups = array())
    {
        $form = $this->createForm($formType, $resource, array('csrf_protection' => false));
        $request = $this->get('request');
        if ($request->isMethod('POST') || $request->isMethod('PUT')) {
            $form->submit($request);
            if ($form->isSubmitted() === true && $form->isValid() === true) {
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($resource);
                $manager->flush();

                return $this->handleView($this->createView($resource, $groups));
            }
        }

        return $this->handleView($this->createView($this->getFormErrors($form), null, 400));
    }
}
