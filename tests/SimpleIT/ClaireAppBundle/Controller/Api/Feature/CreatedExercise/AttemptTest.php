<?php
namespace SimpleIT\ExerciseBundle\Feature\CreatedExercise;

use SimpleIT\ApiBundle\Model\ApiResponse;
use OC\Tester\ApiFeatureTest;
use SimpleIT\Utils\FormatUtils;

/**
 * Test Stored Exercises.
 *
 * @author Baptiste Cable <baptiste.cable@liris.cnrs.fr>
 */
class AttemptTest extends ApiFeatureTest
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

    const ID_TEST_3 = 8;

    const ID_TEST_4 = 9;

    const ID_TEST_NOT_EXISTING = 250;

    const TEST_RESOURCE_1 = '{"id":1,"exercise":1,"test_attempt":1,"user":1000001,"position":0}';

    const TEST_RESOURCE_2 = '{"id":2,"exercise":2,"test_attempt":1,"user":1000001,"position":1}';

    const TEST_RESOURCE_3 = '{"id":8,"exercise":3,"user":1000001}';

    const TEST_RESOURCE_4 = '{"id":9,"exercise":4,"user":1000002}';

    const TEST_RESOURCE_LIST_1 = '[{"id":1,"exercise":1,"test_attempt":1,"user":1000001,"position":0},{"id":2,"exercise":2,"test_attempt":1,"user":1000001,"position":1},{"id":3,"exercise":3,"test_attempt":1,"user":1000002,"position":2},{"id":4,"exercise":4,"test_attempt":2,"user":1000002,"position":0},{"id":5,"exercise":2,"test_attempt":2,"user":1000002,"position":1},{"id":6,"exercise":1,"user":1000001},{"id":7,"exercise":2,"user":1000002},{"id":8,"exercise":3,"user":1000001},{"id":9,"exercise":4,"user":1000002}]';

    const TEST_RESOURCE_LIST_2 = '[{"id":1,"exercise":1,"test_attempt":1,"user":1000001,"position":0},{"id":2,"exercise":2,"test_attempt":1,"user":1000001,"position":1},{"id":6,"exercise":1,"user":1000001},{"id":8,"exercise":3,"user":1000001}]';

    const TEST_RESOURCE_LIST_3 = '[{"id":3,"exercise":3,"test_attempt":1,"user":1000002,"position":2},{"id":4,"exercise":4,"test_attempt":2,"user":1000002,"position":0},{"id":5,"exercise":2,"test_attempt":2,"user":1000002,"position":1},{"id":7,"exercise":2,"user":1000002},{"id":9,"exercise":4,"user":1000002}]';

    /**
     * @var string
     */
    protected $path = '/attempts/{attemptId}';

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
            array(self::ID_TEST_3, ApiResponse::STATUS_CODE_OK, self::TEST_RESOURCE_3),
            array(self::ID_TEST_4, ApiResponse::STATUS_CODE_OK, self::TEST_RESOURCE_4),
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
        $response = $this->get(array('attemptId' => $inputIdentifier));

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
                array()
            ),
            array(
                self::BAD_REQUEST_CODE,
                self::BAD_REQUEST,
                array('user' => self::ME),
                array()
            ),
            array(
                ApiResponse::STATUS_CODE_OK,
                self::TEST_RESOURCE_LIST_1,
                array('user' => self::ALL),
                array("Authorization" => self::USER_TOKEN_1)
            ),
            array(
                ApiResponse::STATUS_CODE_OK,
                self::TEST_RESOURCE_LIST_1,
                array('user' => self::ALL),
                array()
            ),
            array(
                self::BAD_REQUEST_CODE,
                self::BAD_REQUEST,
                array('user' => self::WRONG_VALUE),
                array()
            ),
            array(
                ApiResponse::STATUS_CODE_OK,
                self::TEST_RESOURCE_LIST_2,
                array(),
                array("Authorization" => self::USER_TOKEN_1)
            ),
            array(
                ApiResponse::STATUS_CODE_OK,
                self::TEST_RESOURCE_LIST_2,
                array('user' => self::ME),
                array("Authorization" => self::USER_TOKEN_1)
            ),
            array(
                ApiResponse::STATUS_CODE_OK,
                self::TEST_RESOURCE_LIST_3,
                array(),
                array("Authorization" => self::USER_TOKEN_2)
            ),
            array(
                ApiResponse::STATUS_CODE_OK,
                self::TEST_RESOURCE_LIST_3,
                array('user' => self::ME),
                array("Authorization" => self::USER_TOKEN_2)
            )
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
