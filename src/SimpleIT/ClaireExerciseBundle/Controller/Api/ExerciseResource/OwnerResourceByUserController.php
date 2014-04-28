<?php
namespace SimpleIT\ClaireExerciseBundle\Controller\Api\ExerciseResource;

use SimpleIT\ApiBundle\Controller\ApiController;
use SimpleIT\ApiBundle\Exception\ApiNotFoundException;
use SimpleIT\ApiBundle\Model\ApiGotResponse;
use SimpleIT\ApiBundle\Model\ApiPaginatedResponse;
use SimpleIT\ClaireExerciseResourceBundle\Model\Resources\OwnerResourceResource;
use SimpleIT\CoreBundle\Exception\NonExistingObjectException;
use SimpleIT\ClaireExerciseBundle\Model\Resources\OwnerResourceResourceFactory;
use SimpleIT\Utils\Collection\CollectionInformation;
use Symfony\Component\HttpFoundation\Request;

/**
 * API OwnerResource Controller
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class OwnerResourceByUserController extends ApiController
{
    /**
     * Get a specific OwnerExerciseModel resource
     *
     * @param int $ownerResourceId
     * @param int $ownerId
     *
     * @throws ApiNotFoundException
     * @return ApiGotResponse
     */
    public function viewAction($ownerResourceId, $ownerId)
    {
        try {
            $ownerResource = $this->get('simple_it.exercise.owner_resource')->getByIdAndOwner(
                $ownerResourceId,
                $ownerId
            );
            $ownerResourceResource = OwnerResourceResourceFactory::create($ownerResource);

            return new ApiGotResponse($ownerResourceResource, array("owner_resource", 'Default'));

        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(OwnerResourceResource::RESOURCE_NAME);
        }
    }

    /**
     * Get the list of owner resources
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
            $ownerResources = $this->get('simple_it.exercise.owner_resource')->getAll(
                $collectionInformation,
                $ownerId
            );

            $oemResources = OwnerResourceResourceFactory::createCollection($ownerResources);

            return new ApiPaginatedResponse($oemResources, $ownerResources, array(
                'owner_resource_list',
                'Default'
            ));
        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException("User");
        }
    }
}
