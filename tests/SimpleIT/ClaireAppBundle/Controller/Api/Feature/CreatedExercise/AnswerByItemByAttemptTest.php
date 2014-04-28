<?php
namespace SimpleIT\ExerciseBundle\Feature\CreatedExercise;

use OC\Tester\ApiFeatureTest;
use SimpleIT\ApiBundle\Model\ApiResponse;
use SimpleIT\Utils\FormatUtils;

/**
 * Test AnswerByItemByAttemptController
 *
 * @author Baptiste Cable <baptiste.cable@liris.cnrs.fr>
 */
class AnswerByItemByAttemptTest extends ApiFeatureTest
{
    const BAD_REQUEST_CODE = 400;

    const NOT_FOUND = 'Not Found';

    const BAD_REQUEST = 'Bad Request';

    const ATTEMPT_ID_TEST_1 = 1;

    const ATTEMPT_ID_TEST_2 = 2;

    const ATTEMPT_ID_TEST_3 = 3;

    const ATTEMPT_ID_TEST_4 = 4;

    const ATTEMPT_ID_TEST_NOT_EXISTING = 250;

    const ITEM_ID_TEST_1 = 1;

    const ITEM_ID_TEST_2 = 2;

    const ITEM_ID_TEST_3 = 3;

    const ITEM_ID_TEST_4 = 4;

    const ITEM_ID_TEST_5 = 5;

    const ITEM_ID_TEST_6 = 6;

    const ITEM_ID_TEST_NOT_EXISTING = 250;

    const TEST_RESOURCE_LIST_1_1 = '[{"id":10}]';

    const TEST_RESOURCE_LIST_1_2 = '[{"id":11}]';

    const TEST_RESOURCE_LIST_1_3 = '[]';

    const TEST_RESOURCE_LIST_2_4 = '[{"id":12}]';

    const TEST_RESOURCE_LIST_3_5 = '[{"id":13}]';

    const TEST_RESOURCE_LIST_4_6 = '[]';

    /**
     * @var string
     */
    protected $path = '/attempts/{attemptId}/items/{itemId}/answers/{answerId}';

    /**
     * Get list provider
     *
     * @return array
     */
    public static function getListProvider()
    {
        return array(
            array(
                array('attemptId' => self::ATTEMPT_ID_TEST_1, 'itemId' => self::ITEM_ID_TEST_1),
                ApiResponse::STATUS_CODE_OK,
                self::TEST_RESOURCE_LIST_1_1
            ),
            array(
                array('attemptId' => self::ATTEMPT_ID_TEST_1, 'itemId' => self::ITEM_ID_TEST_2),
                ApiResponse::STATUS_CODE_OK,
                self::TEST_RESOURCE_LIST_1_2
            ),
            array(
                array('attemptId' => self::ATTEMPT_ID_TEST_1, 'itemId' => self::ITEM_ID_TEST_3),
                ApiResponse::STATUS_CODE_OK,
                self::TEST_RESOURCE_LIST_1_3
            ),
            array(
                array('attemptId' => self::ATTEMPT_ID_TEST_2, 'itemId' => self::ITEM_ID_TEST_4),
                ApiResponse::STATUS_CODE_OK,
                self::TEST_RESOURCE_LIST_2_4
            ),
            array(
                array('attemptId' => self::ATTEMPT_ID_TEST_3, 'itemId' => self::ITEM_ID_TEST_5),
                ApiResponse::STATUS_CODE_OK,
                self::TEST_RESOURCE_LIST_3_5
            ),
            array(
                array('attemptId' => self::ATTEMPT_ID_TEST_4, 'itemId' => self::ITEM_ID_TEST_6),
                ApiResponse::STATUS_CODE_OK,
                self::TEST_RESOURCE_LIST_4_6
            ),
            array(
                array(
                    'attemptId' => self::ATTEMPT_ID_TEST_4,
                    'itemId'    => self::ITEM_ID_TEST_NOT_EXISTING
                ),
                ApiResponse::STATUS_CODE_CONTENT_NOT_FOUND,
                'Not Found'
            ),
            array(
                array(
                    'attemptId' => self::ATTEMPT_ID_TEST_NOT_EXISTING,
                    'itemId'    => self::ITEM_ID_TEST_1
                ),
                ApiResponse::STATUS_CODE_CONTENT_NOT_FOUND,
                'Not Found'
            )
        );
    }

    /**
     * Test GET on collection
     *
     * @param int | string $inputIdentifier    Exercise model id
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
            $inputIdentifier,
            $inputParameters,
            array(),
            $inputRange,
            FormatUtils::JSON
        );

        $this->assertEquals($expectedStatusCode, $response->getStatusCode());
        $this->assertEquals($expectedContent, $response->getContent());
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
                self::ATTEMPT_ID_TEST_1,
                self::ITEM_ID_TEST_1,
                '{"content":[0,0,0,1]}',
                self::BAD_REQUEST_CODE,
                '#' . self::BAD_REQUEST . '#'
            ),
            array(
                self::ATTEMPT_ID_TEST_1,
                self::ITEM_ID_TEST_2,
                '{"content":[0,0,0,1]}',
                self::BAD_REQUEST_CODE,
                '#' . self::BAD_REQUEST . '#'
            ),
            array(
                self::ATTEMPT_ID_TEST_1,
                self::ITEM_ID_TEST_3,
                '{"content":[0,0,0,1]}',
                ApiResponse::STATUS_CODE_CREATED,
                '#^\{"id":[0-9]+,"content":\[0,0,0,1\]\}$#'
            ),
            array(
                self::ATTEMPT_ID_TEST_1,
                self::ITEM_ID_TEST_4,
                '{"content":[0,0,0,1]}',
                ApiResponse::STATUS_CODE_CONTENT_NOT_FOUND,
                '#' . self::NOT_FOUND . '#'
            ),
            array(
                self::ATTEMPT_ID_TEST_2,
                self::ITEM_ID_TEST_4,
                '{"content":[2,0,1,3]}',
                self::BAD_REQUEST_CODE,
                '#' . self::BAD_REQUEST . '#'
            ),
            array(
                self::ATTEMPT_ID_TEST_3,
                self::ITEM_ID_TEST_5,
                '{"content":{"obj":[2,1,0,2,0,0],"gr":["M","L","S"]}}',
                self::BAD_REQUEST_CODE,
                '#' . self::BAD_REQUEST . '#'
            ),
            array(
                self::ATTEMPT_ID_TEST_4,
                self::ITEM_ID_TEST_6,
                '{"content":[0,3,1,2,4]}',
                ApiResponse::STATUS_CODE_CREATED,
                '#^\{"id":[0-9]+,"content":\[0,3,1,2,4\]\}$#'
            ),
        );
    }

    /**
     * Test POST
     *
     * @param int    $attemptIdentifier
     * @param int    $itemIdentifier     Item id
     * @param string $inputContent       Answer content
     * @param int    $expectedStatusCode Expected status code
     * @param string $expectedContent    Expected response content
     *
     * @dataProvider postProvider
     */
    public function testCreate(
        $attemptIdentifier,
        $itemIdentifier,
        $inputContent,
        $expectedStatusCode,
        $expectedContent
    )
    {
        $response = $this->post(
            array(
                'attemptId' => $attemptIdentifier,
                'itemId'    => $itemIdentifier
            ),
            $inputContent
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
