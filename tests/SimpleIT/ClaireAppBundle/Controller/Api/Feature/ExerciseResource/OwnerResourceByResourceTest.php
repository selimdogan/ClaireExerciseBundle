<?php
namespace SimpleIT\ExerciseBundle\Feature\ExerciseResource;

use SimpleIT\ApiBundle\Model\ApiResponse;
use OC\Tester\ApiFeatureTest;
use SimpleIT\Utils\FormatUtils;

/**
 * Test OwnerResourceByResource Controller
 *
 * @author Baptiste Cable <baptiste.cable@liris.cnrs.fr>
 */
class OwnerResourceByResourceTest extends ApiFeatureTest
{
    const BAD_REQUEST_CODE = 400;

    const BAD_REQUEST = 'Bad Request';

    const NOT_FOUND = 'Not Found';

    const USER_TOKEN_1 = 'Bearer token-1';

    const USER_WRONG_TOKEN = 'Bearer toto';

    const ID_RESOURCE_1 = 109;

    const ID_RESOURCE_2 = 130;

    const ID_OWNER_RESOURCE_1 = 9;

    const ID_OWNER_RESOURCE_2 = 30;

    const ID_NOT_EXISTING = 250;

    /**
     * @var string
     */
    protected $path = '/resources/{resourceId}/owner-resources/{ownerResourceId}';

    /**
     * Get provider
     *
     * @return array
     */
    public static function getProvider()
    {
        return array(
            array(
                self::ID_RESOURCE_1,
                self::ID_OWNER_RESOURCE_1,
                ApiResponse::STATUS_CODE_OK,
                '\{"id":9,"resource":109,"owner":1000001,"public":true,"metadata":\{.*\},"type":"text"\}'
            ),
            array(
                self::ID_RESOURCE_2,
                self::ID_OWNER_RESOURCE_2,
                ApiResponse::STATUS_CODE_OK,
                '\{"id":30,"resource":130,"owner":1000001,"public":true,"metadata":\{.*\},"type":"picture"\}'
            ),
            array(
                self::ID_RESOURCE_1,
                self::ID_OWNER_RESOURCE_2,
                ApiResponse::STATUS_CODE_CONTENT_NOT_FOUND,
                self::NOT_FOUND
            ),
            array(
                self::ID_RESOURCE_1,
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
                'resourceId'      => $resourceId,
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
                self::ID_RESOURCE_1,
                ApiResponse::STATUS_CODE_OK,
                '\[\{"id":9,"resource":109,"owner":1000001,"public":true,"metadata":\{".*"\},"type":"text","content":\{"object_type":"text"\}\},\{"id":57,"resource":109,"owner":1000002,"public":true,"metadata":\[\],"type":"text","content":\{"object_type":"text"\}\}\]'
            ),
            array(
                self::ID_RESOURCE_2,
                ApiResponse::STATUS_CODE_OK,
                '\[\{"id":30,"resource":130,"owner":1000001,"public":true,"metadata":\{".*"\},"type":"picture","content":\{"source":"img6.jpeg","object_type":"picture"\}\}\]'
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
            array('resourceId' => $inputIdentifier),
            $inputParameters,
            array(),
            $inputRange,
            FormatUtils::JSON
        );

        $this->assertEquals($expectedStatusCode, $response->getStatusCode());
        $this->assertRegExp('#^' . $expectedContent . '$#', $response->getContent());
    }

    /**
     * Post provider
     *
     * @return array
     */
    public static function postProvider()
    {
        return array(
            array(
                self::ID_RESOURCE_2,
                array('Authorization' => self::USER_TOKEN_1),
                '{"public":true,"metadata":{"doo":"mee","doo2":"mee2"}}',
                ApiResponse::STATUS_CODE_CREATED,
                '#^\{"id":[0-9]+,"resource":130,"owner":1000001,"public":true,"metadata":\{.*\},"type":"picture","content":\{"source":"img6.jpeg","object_type":"picture"\}\}$#'
            ),
            array(
                self::ID_RESOURCE_2,
                array(),
                '{"public":true,"metadata":{"doo":"mee","doo2":"mee2"}}',
                self::BAD_REQUEST_CODE,
                '#' . self::BAD_REQUEST . '#'
            ),
            array(
                self::ID_RESOURCE_2,
                array('Authorization' => self::USER_WRONG_TOKEN),
                '{"public":true,"metadata":{"doo":"mee","doo2":"mee2"}}',
                401,
                '#^Unauthorized$#'
            ),
            array(
                self::ID_RESOURCE_2,
                array('Authorization' => self::USER_TOKEN_1),
                '{"id":30,"public":true,"metadata":{"doo":"mee","doo2":"mee2"}}',
                self::BAD_REQUEST_CODE,
                '#' . self::BAD_REQUEST . '#'
            ),
            array(
                self::ID_RESOURCE_2,
                array('Authorization' => self::USER_TOKEN_1),
                '{"metadata":{"doo":"mee","doo2":"mee2"}}',
                self::BAD_REQUEST_CODE,
                '#' . self::BAD_REQUEST . '#'
            ),
            array(
                self::ID_RESOURCE_2,
                array('Authorization' => self::USER_TOKEN_1),
                '{"public":true}',
                self::BAD_REQUEST_CODE,
                '#' . self::BAD_REQUEST . '#'
            ),
            array(
                self::ID_NOT_EXISTING,
                array('Authorization' => self::USER_TOKEN_1),
                '{"public":true,"metadata":{"doo":"mee","doo2":"mee2"}}',
                ApiResponse::STATUS_CODE_CONTENT_NOT_FOUND,
                '#' . self::NOT_FOUND . '#'
            )
        );
    }

    /**
     * Test POST
     *
     * @param int    $inputIdentifier    Resource id
     * @param array  $headers
     * @param string $content
     * @param int    $expectedStatusCode Expected status code
     * @param string $expectedContent
     *
     * @dataProvider postProvider
     */
    public function testCreate(
        $inputIdentifier,
        array $headers,
        $content,
        $expectedStatusCode,
        $expectedContent
    )
    {
        $response = $this->post(
            array('resourceId' => $inputIdentifier),
            $content,
            array(),
            $headers
        );

        $this->assertEquals($expectedStatusCode, $response->getStatusCode());
        $this->assertRegExp($expectedContent, $response->getContent());
    }

    /**
     * Put provider
     *
     * @return array
     */
    public static function putProvider()
    {
        return array(
            array(
                self::ID_RESOURCE_1,
                self::ID_OWNER_RESOURCE_1,
                '{"public":false}',
                ApiResponse::STATUS_CODE_OK,
                '#^\{"id":9,"resource":109,"owner":1000001,"public":false,"metadata":\{.*\},"type":"text","content":\{"text":"Programme 1 en C qui a pour sortie ecran 12","object_type":"text"\}\}$#'
            ),
            array(
                self::ID_RESOURCE_1,
                self::ID_OWNER_RESOURCE_1,
                '{"id":5,"public":false}',
                self::BAD_REQUEST_CODE,
                '#' . self::BAD_REQUEST . '#'
            ),
            array(
                self::ID_RESOURCE_1,
                self::ID_OWNER_RESOURCE_1,
                '{"metadata":{"doo":"mee"}}',
                self::BAD_REQUEST_CODE,
                '#' . self::BAD_REQUEST . '#'
            ),
            array(
                self::ID_NOT_EXISTING,
                self::ID_OWNER_RESOURCE_1,
                '{"public":false}',
                ApiResponse::STATUS_CODE_CONTENT_NOT_FOUND,
                '#' . self::NOT_FOUND . '#'
            ),
            array(
                self::ID_RESOURCE_1,
                self::ID_NOT_EXISTING,
                '{"public":false}',
                ApiResponse::STATUS_CODE_CONTENT_NOT_FOUND,
                '#' . self::NOT_FOUND . '#'
            ),
        );
    }

    /**
     * Test PUT
     *
     * @param int    $oemId              Resource id
     * @param string $ownerResourceId
     * @param string $content
     * @param int    $expectedStatusCode Expected status code
     * @param string $expectedContent
     *
     * @dataProvider putProvider
     */
    public function testEdit(
        $oemId,
        $ownerResourceId,
        $content,
        $expectedStatusCode,
        $expectedContent
    )
    {
        $response = $this->put(
            array('resourceId' => $oemId, 'ownerResourceId' => $ownerResourceId),
            $content
        );

        $this->assertEquals($expectedStatusCode, $response->getStatusCode());
        $this->assertRegExp($expectedContent, $response->getContent());
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
