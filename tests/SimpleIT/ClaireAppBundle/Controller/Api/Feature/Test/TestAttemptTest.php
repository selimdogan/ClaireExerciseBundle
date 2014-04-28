<?php
namespace SimpleIT\ExerciseBundle\Feature\Test;

use SimpleIT\ApiBundle\Model\ApiResponse;
use OC\Tester\ApiFeatureTest;
use SimpleIT\Utils\FormatUtils;

/**
 * Test TestAttempt
 *
 * @author Baptiste Cable <baptiste.cable@liris.cnrs.fr>
 */
class TestAttemptTest extends ApiFeatureTest
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

    const TEST_RESOURCE_1 = '{"id":1,"created_at":"2013-10-03T13:55:47+0000","test":1,"user":1000001}';

    const TEST_RESOURCE_2 = '{"id":2,"created_at":"2013-10-03T13:55:47+0000","test":2,"user":1000002}';

    const TEST_RESOURCE_LIST_1 = '[{"id":1,"created_at":"2013-10-03T13:55:47+0000","test":1,"user":1000001},{"id":2,"created_at":"2013-10-03T13:55:47+0000","test":2,"user":1000002}]';

    const TEST_RESOURCE_LIST_2 = '[{"id":1,"created_at":"2013-10-03T13:55:47+0000","test":1,"user":1000001}]';

    const TEST_RESOURCE_LIST_3 = '[{"id":2,"created_at":"2013-10-03T13:55:47+0000","test":2,"user":1000002}]';

    /**
     * @var string
     */
    protected $path = '/test-attempts/{testAttemptId}';

    /**
     * Get provider
     *
     * @return array
     */
    public static function getProvider()
    {
        return array(
            array(self::ID_TEST_1, ApiResponse::STATUS_CODE_OK, self::TEST_RESOURCE_1),
            array(self::ID_TEST_2, ApiResponse::STATUS_CODE_OK, self::TEST_RESOURCE_2),
            array(
                self::ID_TEST_NOT_EXISTING,
                ApiResponse::STATUS_CODE_CONTENT_NOT_FOUND,
                'Not Found'
            )
        );
    }

    /**
     * Test GET
     *
     * @param int | string $inputIdentifier    Exercise id
     * @param int          $expectedStatusCode Expected status code
     * @param string       $expectedContent    Expected content
     *
     * @dataProvider getProvider
     */
    public function testView($inputIdentifier, $expectedStatusCode, $expectedContent)
    {
        $response = $this->get(array('testAttemptId' => $inputIdentifier));

        $this->assertEquals($expectedStatusCode, $response->getStatusCode());
        $this->assertEquals($expectedContent, $response->getContent());
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
                self::BAD_REQUEST_CODE,
                self::BAD_REQUEST,
                array(),
                array(),
            ),
            array(
                self::BAD_REQUEST_CODE,
                self::BAD_REQUEST,
                array('user' => self::ME),
                array(),
            ),
            array(
                ApiResponse::STATUS_CODE_OK,
                self::TEST_RESOURCE_LIST_1,
                array('user' => self::ALL),
                array("Authorization" => self::USER_TOKEN_1),
            ),
            array(
                ApiResponse::STATUS_CODE_OK,
                self::TEST_RESOURCE_LIST_1,
                array('user' => self::ALL),
                array(),
            ),
            array(
                self::BAD_REQUEST_CODE,
                self::BAD_REQUEST,
                array('user' => self::WRONG_VALUE),
                array(),
            ),
            array(
                ApiResponse::STATUS_CODE_OK,
                self::TEST_RESOURCE_LIST_2,
                array(),
                array("Authorization" => self::USER_TOKEN_1),
            ),
            array(
                ApiResponse::STATUS_CODE_OK,
                self::TEST_RESOURCE_LIST_2,
                array('user' => self::ME),
                array("Authorization" => self::USER_TOKEN_1),
            ),
            array(
                ApiResponse::STATUS_CODE_OK,
                self::TEST_RESOURCE_LIST_3,
                array(),
                array("Authorization" => self::USER_TOKEN_2),
            ),
            array(
                ApiResponse::STATUS_CODE_OK,
                self::TEST_RESOURCE_LIST_3,
                array('user' => self::ME),
                array("Authorization" => self::USER_TOKEN_2),
            ),
        );
    }

    /**
     * Test GET on collection
     *
     * @param integer $expectedStatusCode Expected status code
     * @param string  $expectedContent    Expected content
     * @param array   $parameters
     * @param array   $headers
     *
     * @dataProvider getListProvider
     */
    public function testList(
        $expectedStatusCode,
        $expectedContent,
        array $parameters = array(),
        array $headers = array()
    )
    {
        $response = $this->getAll(
            array(),
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
