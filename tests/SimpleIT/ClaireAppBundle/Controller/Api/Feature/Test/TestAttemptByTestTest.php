<?php
namespace SimpleIT\ExerciseBundle\Feature\Test;

use OC\Tester\ApiFeatureTest;
use SimpleIT\ApiBundle\Model\ApiResponse;
use SimpleIT\Utils\FormatUtils;

/**
 * Test TestAttemptByTestController Test
 *
 * @author Baptiste Cable <baptiste.cable@liris.cnrs.fr>
 */
class TestAttemptByTestTest extends ApiFeatureTest
{
    const BAD_REQUEST_CODE = 400;

    const BAD_REQUEST = "Bad Request";

    const USER_TOKEN_1 = 'Bearer token-1';

    const USER_TOKEN_2 = 'Bearer token-2';

    const ME = 'me';

    const ALL = 'all';

    const WRONG_VALUE = 'doo';

    const ID_TEST_1 = 1;

    const ID_TEST_2 = 2;

    const ID_TEST_NOT_EXISTING = 250;

    const TEST_RESOURCE_CREATED_1 = '#^\{"id":[0-9]+,"created_at":".*","test":1,"user":1000001\}$#';

    const TEST_RESOURCE_CREATED_2 = '#^\{"id":[0-9]+,"created_at":".*","test":2,"user":1000002\}$#';

    const TEST_RESOURCE_LIST_1_1 = '[{"id":1,"created_at":"2013-10-03T13:55:47+0000","test":1,"user":1000001}]';

    const TEST_RESOURCE_LIST_1_2 = '[]';

    const TEST_RESOURCE_LIST_2_1 = '[]';

    const TEST_RESOURCE_LIST_2_2 = '[{"id":2,"created_at":"2013-10-03T13:55:47+0000","test":2,"user":1000002}]';

    const TEST_RESOURCE_LIST_3_1 = '[{"id":1,"created_at":"2013-10-03T13:55:47+0000","test":1,"user":1000001}]';

    const TEST_RESOURCE_LIST_3_2 = '[{"id":2,"created_at":"2013-10-03T13:55:47+0000","test":2,"user":1000002}]';

    /**
     * @var string
     */
    protected $path = '/tests/{testId}/test-attempts/';

    /**
     * Post provider
     *
     * @return array
     */
    public static function postProvider()
    {
        return array(
            array(
                self::ID_TEST_1,
                ApiResponse::STATUS_CODE_CREATED,
                self::TEST_RESOURCE_CREATED_1,
                array("Authorization" => self::USER_TOKEN_1),
            ),
            array(
                self::ID_TEST_2,
                ApiResponse::STATUS_CODE_CREATED,
                self::TEST_RESOURCE_CREATED_2,
                array("Authorization" => self::USER_TOKEN_2),
            ),
            array(
                self::ID_TEST_NOT_EXISTING,
                ApiResponse::STATUS_CODE_CONTENT_NOT_FOUND,
                '#Not Found#',
                array("Authorization" => self::USER_TOKEN_2),
            ),
            array(
                self::ID_TEST_NOT_EXISTING,
                self::BAD_REQUEST_CODE,
                '#' . self::BAD_REQUEST . '#',
                array(),
            ),
        );
    }

    /**
     * Test POST
     *
     * @param int    $inputIdentifier    Exercise model id
     * @param int    $expectedStatusCode Expected status code
     * @param string $expectedContent
     * @param array  $headers
     *
     * @dataProvider postProvider
     */
    public function testCreate(
        $inputIdentifier,
        $expectedStatusCode,
        $expectedContent,
        array $headers = array()
    )
    {
        $response = $this->post(
            array('testId' => $inputIdentifier),
            null,
            array(),
            $headers
        );

        $this->assertEquals($expectedStatusCode, $response->getStatusCode());
        $this->assertRegExp($expectedContent, $response->getContent());
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
                self::ID_TEST_1,
                ApiResponse::STATUS_CODE_OK,
                self::TEST_RESOURCE_LIST_1_1,
                array(),
                array("Authorization" => self::USER_TOKEN_1),
            ),
            array(
                self::ID_TEST_1,
                ApiResponse::STATUS_CODE_OK,
                self::TEST_RESOURCE_LIST_1_1,
                array('user' => self::ME),
                array("Authorization" => self::USER_TOKEN_1),
            ),
            array(
                self::ID_TEST_1,
                ApiResponse::STATUS_CODE_OK,
                self::TEST_RESOURCE_LIST_2_1,
                array(),
                array("Authorization" => self::USER_TOKEN_2),
            ),
            array(
                self::ID_TEST_1,
                ApiResponse::STATUS_CODE_OK,
                self::TEST_RESOURCE_LIST_2_1,
                array('user' => self::ME),
                array("Authorization" => self::USER_TOKEN_2),
            ),
            array(
                self::ID_TEST_2,
                ApiResponse::STATUS_CODE_OK,
                self::TEST_RESOURCE_LIST_1_2,
                array(),
                array("Authorization" => self::USER_TOKEN_1),
            ),
            array(
                self::ID_TEST_2,
                ApiResponse::STATUS_CODE_OK,
                self::TEST_RESOURCE_LIST_1_2,
                array('user' => self::ME),
                array("Authorization" => self::USER_TOKEN_1),
            ),
            array(
                self::ID_TEST_2,
                ApiResponse::STATUS_CODE_OK,
                self::TEST_RESOURCE_LIST_2_2,
                array(),
                array("Authorization" => self::USER_TOKEN_2),
            ),
            array(
                self::ID_TEST_2,
                ApiResponse::STATUS_CODE_OK,
                self::TEST_RESOURCE_LIST_2_2,
                array('user' => self::ME),
                array("Authorization" => self::USER_TOKEN_2),
            ),
            array(
                self::ID_TEST_1,
                ApiResponse::STATUS_CODE_OK,
                self::TEST_RESOURCE_LIST_3_1,
                array('user' => self::ALL),
                array("Authorization" => self::USER_TOKEN_1),
            ),
            array(
                self::ID_TEST_2,
                ApiResponse::STATUS_CODE_OK,
                self::TEST_RESOURCE_LIST_3_2,
                array('user' => self::ALL),
                array("Authorization" => self::USER_TOKEN_2),
            ),
            array(
                self::ID_TEST_2,
                ApiResponse::STATUS_CODE_OK,
                self::TEST_RESOURCE_LIST_3_2,
                array('user' => self::ALL),
                array(),
            ),
            array(
                self::ID_TEST_1,
                self::BAD_REQUEST_CODE,
                self::BAD_REQUEST,
                array('user' => self::ME),
                array(),
            ),
            array(
                self::ID_TEST_1,
                self::BAD_REQUEST_CODE,
                self::BAD_REQUEST,
                array(),
                array(),
            ),
            array(
                self::ID_TEST_NOT_EXISTING,
                ApiResponse::STATUS_CODE_CONTENT_NOT_FOUND,
                'Not Found',
                array('user' => self::ALL),
                array("Authorization" => self::USER_TOKEN_1),
            ),
            array(
                self::ID_TEST_NOT_EXISTING,
                ApiResponse::STATUS_CODE_CONTENT_NOT_FOUND,
                'Not Found',
                array('user' => self::ALL),
                array(),
            ),
            array(
                self::ID_TEST_NOT_EXISTING,
                self::BAD_REQUEST_CODE,
                self::BAD_REQUEST,
                array('user' => self::WRONG_VALUE),
                array("Authorization" => self::USER_TOKEN_1),
            )
        );
    }

    /**
     * Test GET on collection
     *
     * @param int     $inputIdentifier    Exercise model id
     * @param integer $expectedStatusCode Expected status code
     * @param string  $expectedContent    Expected content
     * @param array   $parameters
     * @param array   $headers
     *
     * @dataProvider getListProvider
     */
    public function testList(
        $inputIdentifier,
        $expectedStatusCode,
        $expectedContent,
        array $parameters = array(),
        array $headers = array()
    )
    {
        $response = $this->getAll(
            array('testId' => $inputIdentifier),
            $parameters,
            $headers,
            array(),
            FormatUtils::JSON
        );

        $this->assertEquals($expectedStatusCode, $response->getStatusCode());
        $this->assertEquals($expectedContent, $response->getContent());
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
