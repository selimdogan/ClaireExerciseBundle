<?php
namespace SimpleIT\ClaireExerciseBundle\Controller\Api\ExerciseResource;

use SimpleIT\ApiBundle\Controller\ApiController;
use SimpleIT\ApiBundle\Exception\ApiNotFoundException;
use SimpleIT\ApiBundle\Model\ApiGotResponse;
use SimpleIT\ApiBundle\Model\ApiPaginatedResponse;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ResourceResource;
use SimpleIT\CoreBundle\Exception\NonExistingObjectException;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ResourceResourceFactory;
use SimpleIT\Utils\Collection\CollectionInformation;
use Symfony\Component\HttpFoundation\Request;

/**
 * API Resource by user Controller
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class ResourceByUserController extends ApiController
{
    /**
     * Get a specific Resource resource
     *
     * @param int $resourceId
     * @param int $ownerId
     *
     * @throws ApiNotFoundException
     * @return ApiGotResponse
     */
    public function viewAction($resourceId, $ownerId)
    {
        try {
            $resource = $this->get('simple_it.exercise.exercise_resource')->getByIdAndOwner(
                $resourceId,
                $ownerId
            );
            $resourceResource = ResourceResourceFactory::create($resource);

            return new ApiGotResponse($resourceResource, array("details", 'Default'));

        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(ResourceResource::RESOURCE_NAME);
        }
    }

    /**
     * Get the list of resources
     *
     * @param CollectionInformation $collectionInformation
     * @param int                   $ownerId
     *
     * @throws ApiNotFoundException
     * @return ApiPaginatedResponse
     */
    public function listAction(
        CollectionInformation $collectionInformation,
        $ownerId
    )
    {
        try {
            $resources = $this->get('simple_it.exercise.exercise_resource')->getAll(
                $collectionInformation,
                $ownerId
            );

            $oemResources = ResourceResourceFactory::createCollection($resources);

            return new ApiPaginatedResponse($oemResources, $resources, array(
                'resource_list',
                'Default'
            ));
        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException("User");
        }
    }
}
