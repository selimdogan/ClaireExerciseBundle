<?php
namespace SimpleIT\ExerciseBundle\Feature\CreatedExercise;

use SimpleIT\ApiBundle\Model\ApiResponse;
use OC\Tester\ApiFeatureTest;
use SimpleIT\Utils\FormatUtils;

/**
 * Test Attempt By Exercise
 *
 * @author Baptiste Cable <baptiste.cable@liris.cnrs.fr>
 */
class AttemptByExerciseTest extends ApiFeatureTest
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

    const ID_TEST_3 = 3;

    const ID_TEST_4 = 4;

    const ID_TEST_NOT_EXISTING = 250;

    const TEST_RESOURCE_CREATED_1 = '#\{"id":[0-9]+,"exercise":1,"user":1000001\}#';

    const TEST_RESOURCE_CREATED_2 = '#\{"id":[0-9]+,"exercise":2,"user":1000001\}#';

    const TEST_RESOURCE_CREATED_3 = '#\{"id":[0-9]+,"exercise":3,"user":1000002\}#';

    const TEST_RESOURCE_CREATED_4 = '#\{"id":[0-9]+,"exercise":4,"user":1000002\}#';

    const TEST_RESOURCE_LIST_1_1 = '[{"id":1,"exercise":1,"test_attempt":1,"user":1000001,"position":0},{"id":6,"exercise":1,"user":1000001}]';

    const TEST_RESOURCE_LIST_1_2 = '[{"id":2,"exercise":2,"test_attempt":1,"user":1000001,"position":1}]';

    const TEST_RESOURCE_LIST_1_3 = '[{"id":8,"exercise":3,"user":1000001}]';

    const TEST_RESOURCE_LIST_1_4 = '[]';

    const TEST_RESOURCE_LIST_2_1 = '[]';

    const TEST_RESOURCE_LIST_2_2 = '[{"id":5,"exercise":2,"test_attempt":2,"user":1000002,"position":1},{"id":7,"exercise":2,"user":1000002}]';

    const TEST_RESOURCE_LIST_2_3 = '[{"id":3,"exercise":3,"test_attempt":1,"user":1000002,"position":2}]';

    const TEST_RESOURCE_LIST_2_4 = '[{"id":4,"exercise":4,"test_attempt":2,"user":1000002,"position":0},{"id":9,"exercise":4,"user":1000002}]';

    const TEST_RESOURCE_LIST_3_1 = '[{"id":1,"exercise":1,"test_attempt":1,"user":1000001,"position":0},{"id":6,"exercise":1,"user":1000001}]';

    const TEST_RESOURCE_LIST_3_2 = '[{"id":2,"exercise":2,"test_attempt":1,"user":1000001,"position":1},{"id":5,"exercise":2,"test_attempt":2,"user":1000002,"position":1},{"id":7,"exercise":2,"user":1000002}]';

    const TEST_RESOURCE_LIST_3_3 = '[{"id":3,"exercise":3,"test_attempt":1,"user":1000002,"position":2},{"id":8,"exercise":3,"user":1000001}]';

    const TEST_RESOURCE_LIST_3_4 = '[{"id":4,"exercise":4,"test_attempt":2,"user":1000002,"position":0},{"id":9,"exercise":4,"user":1000002}]';

    /**
     * @var string
     */
    protected $path = '/exercises/{exerciseId}/attempts/';

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
                array("Authorization" => self::USER_TOKEN_1),
            ),
            array(
                self::ID_TEST_3,
                ApiResponse::STATUS_CODE_CREATED,
                self::TEST_RESOURCE_CREATED_3,
                array("Authorization" => self::USER_TOKEN_2),
            ),
            array(
                self::ID_TEST_4,
                ApiResponse::STATUS_CODE_CREATED,
                self::TEST_RESOURCE_CREATED_4,
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
            array('exerciseId' => $inputIdentifier),
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
                self::ID_TEST_2,
                ApiResponse::STATUS_CODE_OK,
                self::TEST_RESOURCE_LIST_1_2,
                array(),
                array("Authorization" => self::USER_TOKEN_1),
            ),
            array(
                self::ID_TEST_3,
                ApiResponse::STATUS_CODE_OK,
                self::TEST_RESOURCE_LIST_1_3,
                array('user' => self::ME),
                array("Authorization" => self::USER_TOKEN_1),
            ),
            array(
                self::ID_TEST_4,
                ApiResponse::STATUS_CODE_OK,
                self::TEST_RESOURCE_LIST_1_4,
                array('user' => self::ME),
                array("Authorization" => self::USER_TOKEN_1),
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
                self::TEST_RESOURCE_LIST_2_2,
                array('user' => self::ME),
                array("Authorization" => self::USER_TOKEN_2),
            ),
            array(
                self::ID_TEST_3,
                ApiResponse::STATUS_CODE_OK,
                self::TEST_RESOURCE_LIST_2_3,
                array(),
                array("Authorization" => self::USER_TOKEN_2),
            ),
            array(
                self::ID_TEST_4,
                ApiResponse::STATUS_CODE_OK,
                self::TEST_RESOURCE_LIST_2_4,
                array(),
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
                array("Authorization" => self::USER_TOKEN_1),
            ),
            array(
                self::ID_TEST_3,
                ApiResponse::STATUS_CODE_OK,
                self::TEST_RESOURCE_LIST_3_3,
                array('user' => self::ALL),
                array("Authorization" => self::USER_TOKEN_1),
            ),
            array(
                self::ID_TEST_4,
                ApiResponse::STATUS_CODE_OK,
                self::TEST_RESOURCE_LIST_3_4,
                array('user' => self::ALL),
                array("Authorization" => self::USER_TOKEN_1),
            ),
            array(
                self::ID_TEST_1,
                self::BAD_REQUEST_CODE,
                self::BAD_REQUEST,
                array('user' => self::ME),
                array(),
            ),
            array(
                self::ID_TEST_2,
                self::BAD_REQUEST_CODE,
                self::BAD_REQUEST,
                array(),
                array(),
            ),
            array(
                self::ID_TEST_3,
                self::BAD_REQUEST_CODE,
                self::BAD_REQUEST,
                array('user' => self::WRONG_VALUE),
                array(),
            ),
            array(
                self::ID_TEST_4,
                ApiResponse::STATUS_CODE_OK,
                self::TEST_RESOURCE_LIST_3_4,
                array('user' => self::ALL),
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
            array('exerciseId' => $inputIdentifier),
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
