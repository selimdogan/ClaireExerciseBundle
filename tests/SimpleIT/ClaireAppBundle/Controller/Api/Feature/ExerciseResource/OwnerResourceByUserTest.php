<?php
namespace SimpleIT\ClaireExerciseBundle\Feature\ExerciseResource;

use SimpleIT\ApiBundle\Model\ApiResponse;
use OC\Tester\ApiFeatureTest;
use SimpleIT\Utils\FormatUtils;

/**
 * Test OwnerResourceByUser Controller
 *
 * @author Baptiste Cable <baptiste.cable@liris.cnrs.fr>
 */
class OwnerResourceByUserTest extends ApiFeatureTest
{
    const BAD_REQUEST_CODE = 400;

    const BAD_REQUEST = 'Bad Request';

    const NOT_FOUND = 'Not Found';

    const ID_USER_1 = 1000001;

    const ID_USER_2 = 1000002;

    const ID_OWNER_RESOURCE_1 = 109;

    const ID_OWNER_RESOURCE_2 = 130;

    const ID_NOT_EXISTING = 250;

    /**
     * @var string
     */
    protected $path = '/users/{userId}/owner-resources/{ownerResourceId}';

    /**
     * Get provider
     *
     * @return array
     */
    public static function getProvider()
    {
        return array(
            array(
                self::ID_USER_1,
                self::ID_OWNER_RESOURCE_1,
                ApiResponse::STATUS_CODE_OK,
                '\{"id":9,"resource":109,"owner":1000001,"public":true,"metadata":\{.*\},"type":"text"\}'
            ),
            array(
                self::ID_USER_1,
                self::ID_OWNER_RESOURCE_2,
                ApiResponse::STATUS_CODE_OK,
                '\{"id":30,"resource":130,"owner":1000001,"public":true,"metadata":\{.*\},"type":"picture"\}'
            ),
            array(
                self::ID_USER_2,
                self::ID_OWNER_RESOURCE_2,
                ApiResponse::STATUS_CODE_CONTENT_NOT_FOUND,
                self::NOT_FOUND
            ),
            array(
                self::ID_USER_1,
                self::ID_NOT_EXISTING,
                ApiResponse::STATUS_CODE_CONTENT_NOT_FOUND,
                self::NOT_FOUND
            ),
            array(
                self::ID_NOT_EXISTING,
                self::ID_OWNER_RESOURCE_2,
                ApiResponse::STATUS_CODE_CONTENT_NOT_FOUND,
                self::NOT_FOUND
            ),
        );
    }

    /**
     * Test GET
     *
     * @param int    $resourceId
     * @param int    $ownerResourceId
     * @param int    $expectedStatusCode Expected status code
     * @param string $expectedContent    Expected content
     *
     * @dataProvider getProvider
     */
    public function testView(
        $resourceId,
        $ownerResourceId,
        $expectedStatusCode,
        $expectedContent
    )
    {
        $response = $this->get(
            array(
                'userId'          => $resourceId,
                'ownerResourceId' => $ownerResourceId
            )
        );

        $this->assertEquals($expectedStatusCode, $response->getStatusCode());
        $this->assertRegExp('#^' . $expectedContent . '$#', $response->getContent());
    }

    /**
     * Get list provider
     *
     * @return array
     */
    public static function getListProvider()
    {
        return array(
            array(
                self::ID_USER_1,
                ApiResponse::STATUS_CODE_OK,
                '\[\{"id":9,"resource":109,"owner":1000001,"public":true,"metadata":\{".*\}\}\]'
            ),
            array(
                self::ID_USER_2,
                ApiResponse::STATUS_CODE_OK,
                '\[\{"id":55,"resource":155,"owner":1000002,"public":true,"metadata":\[.*\}\}\]'
            ),
            array(
                self::ID_NOT_EXISTING,
                ApiResponse::STATUS_CODE_CONTENT_NOT_FOUND,
                'Not Found'
            )
        );
    }

    /**
     * Test GET on collection
     *
     * @param int | string $inputIdentifier    Resource id
     * @param integer      $expectedStatusCode Expected status code
     * @param string       $expectedContent    Expected content
     * @param array        $inputParameters    Parameters
     * @param array        $inputRange         Range (min, max)
     *
     * @dataProvider getListProvider
     */
    public function testList(
        $inputIdentifier,
        $expectedStatusCode,
        $expectedContent,
        array $inputParameters = array(),
        array $inputRange = array()
    )
    {
        $response = $this->getAll(
            array('userId' => $inputIdentifier),
            $inputParameters,
            array(),
            $inputRange,
            FormatUtils::JSON
        );

        $this->assertEquals($expectedStatusCode, $response->getStatusCode());
        $this->assertRegExp('#^' . $expectedContent . '$#', $response->getContent());
    }

    /**
     * @inheritdoc
     * @return \PHPUnit_Extensions_Database_DataSet_IDataSet|\PHPUnit_Extensions_Database_DataSet_XmlDataSet
     */
    protected function getDataSet()
    {
        return new \PHPUnit_Extensions_Database_DataSet_CompositeDataSet(
            array(
                $this->createXMLDataSet(__DIR__ . '/../../Dataset/authentification_dataset.xml'),
                $this->createXMLDataSet(__DIR__ . '/../../Dataset/resource_dataset.xml'),
                $this->createXMLDataSet(__DIR__ . '/../../Dataset/exercise_model_dataset.xml'),
                $this->createXMLDataSet(__DIR__ . '/../../Dataset/stored_exercise_dataset.xml'),
                $this->createXMLDataSet(__DIR__ . '/../../Dataset/test_model_dataset.xml'),
                $this->createXMLDataSet(__DIR__ . '/../../Dataset/test_dataset.xml'),
                $this->createXMLDataSet(__DIR__ . '/../../Dataset/attempt_dataset.xml'),
                $this->createXMLDataSet(__DIR__ . '/../../Dataset/item_dataset.xml'),
                $this->createXMLDataSet(__DIR__ . '/../../Dataset/answer_dataset.xml'),
                $this->createXMLDataSet(__DIR__ . '/../../Dataset/knowledge_dataset.xml'),
            ));
    }
}
