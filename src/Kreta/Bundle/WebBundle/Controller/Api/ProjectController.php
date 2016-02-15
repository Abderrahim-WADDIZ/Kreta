<?php

/*
 * This file is part of the Kreta package.
 *
 * (c) Beñat Espiña <benatespina@gmail.com>
 * (c) Gorka Laucirica <gorka.lauzirika@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kreta\Bundle\WebBundle\Controller\Api;

use FOS\RestBundle\Controller\Annotations as Rest;
use Kreta\Bundle\ProjectBundle\Security\Authorization\Voter\ProjectVoter;
use Kreta\Component\Project\Model\Interfaces\ProjectInterface;
use Kreta\SimpleApiDocBundle\Annotation\ApiDoc;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Project private api controller class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class ProjectController extends Controller
{
    /**
     * Returns the project of given project slug and organization slug.
     *
     * @param string $organizationSlug The slug of organization
     * @param string $projectSlug      The slug of project
     *
     * @ApiDoc(statusCodes={200, 403, 404})
     * @Rest\View(statusCode=200, serializerGroups={"project"})
     * @Rest\Get("/organizations/{organizationSlug}/projects/{projectSlug}")
     *
     * @return ProjectInterface
     */
    public function getOrganizationProjectAction($organizationSlug, $projectSlug)
    {
        $project = $this->get('kreta_project.repository.project')->findOneBy(
            ['o.slug' => $organizationSlug, 'slug' => $projectSlug], false
        );
        if (!$this->get('security.authorization_checker')->isGranted(ProjectVoter::VIEW, $project)) {
            throw new AccessDeniedException();
        }

        return $project;
    }

    /**
     * Returns projects that the current user is a
     * creator and the organization is null.
     *
     * @ApiDoc(statusCodes={200, 403, 404})
     * @Rest\View(statusCode=200, serializerGroups={"projectList"})
     * @Rest\Get("/me/projects")
     *
     * @return ProjectInterface[]
     */
    public function getMyProjectsAction()
    {
        $projects = $this->get('kreta_project.repository.project')->findBy([
            'creator' => $this->getUser(), 'organization' => null,
        ]);

        return $projects;
    }

    /**
     * Returns project of project slug given if the current
     * user is a creator and the organization is null.
     *
     * @param string $projectSlug The slug of project
     *
     * @ApiDoc(statusCodes={200, 403, 404})
     * @Rest\View(statusCode=200, serializerGroups={"project"})
     * @Rest\Get("/me/projects/{projectSlug}")
     *
     * @return ProjectInterface
     */
    public function getMyProjectAction($projectSlug)
    {
        $project = $this->get('kreta_project.repository.project')->findOneBy([
            'creator' => $this->getUser(), 'slug' => $projectSlug, 'organization' => null,
        ], false);

        return $project;
    }
}
