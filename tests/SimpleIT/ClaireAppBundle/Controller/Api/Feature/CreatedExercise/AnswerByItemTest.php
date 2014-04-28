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
class AnswerByItemTest extends ApiFeatureTest
{
    const BAD_REQUEST_CODE = 400;

    const NOT_FOUND = 'Not Found';

    const BAD_REQUEST = '#Bad Request#';

    const ITEM_ID_TEST_1 = 1;

    const ITEM_ID_TEST_2 = 2;

    const ITEM_ID_TEST_3 = 3;

    const ITEM_ID_TEST_4 = 4;

    const ITEM_ID_TEST_5 = 5;

    const ITEM_ID_TEST_6 = 6;

    const ITEM_ID_TEST_NOT_EXISTING = 250;

    const ANSWER_ID_TEST_1 = 1;

    const ANSWER_ID_TEST_2 = 2;

    const ANSWER_ID_TEST_3 = 3;

    const ANSWER_ID_TEST_4 = 4;

    const ANSWER_ID_TEST_5 = 5;

    const ANSWER_ID_TEST_6 = 6;

    const ANSWER_ID_TEST_7 = 7;

    const ANSWER_ID_TEST_8 = 8;

    const ANSWER_ID_TEST_9 = 9;

    const ANSWER_ID_TEST_NOT_EXISTING = 250;

    const VIEW_TEST_RESOURCE_1_1 = '{"type":"multiple-choice","corrected":true,"content":{"comment":"Les cotes sont numerotees de 1 a 9 et seules les 7 premi\u00e8res sont fixes et dites \"vraies cotes\".","propositions":[{"text":"autre","right":false,"ticked":false},{"text":"9","right":false,"ticked":false},{"text":"6","right":false,"ticked":false},{"text":"7","right":true,"ticked":true}],"mark":100,"item_type":"multiple-choice-question"}}';

    const VIEW_TEST_RESOURCE_2_2 = '{"type":"multiple-choice","corrected":true,"content":{"comment":"Les fibres musculaires sont extr\u00eamement fines et il y en a un tr\u00e8s grand nombre dans chaque muscle.","question":"Le diam\u00e8tre d\'une fibre musculaire correspond a :","propositions":[{"text":"Celui d\'un nerf","right":false,"ticked":false},{"text":"Celui d\'une art\u00e8re","right":false,"ticked":false},{"text":"Celui du muscle","right":false,"ticked":true},{"text":"Celui d\'un cheveu","right":true,"ticked":false},{"text":"Celui d\'un vaisseau","right":false}],"mark":0,"item_type":"multiple-choice-question"}}';

    const VIEW_TEST_RESOURCE_2_3 = '{"type":"multiple-choice","corrected":true,"content":{"comment":"Les fibres musculaires sont extr\u00eamement fines et il y en a un tr\u00e8s grand nombre dans chaque muscle.","question":"Le diam\u00e8tre d\'une fibre musculaire correspond a :","propositions":[{"text":"Celui d\'un nerf","right":false,"ticked":false},{"text":"Celui d\'une art\u00e8re","right":false,"ticked":false},{"text":"Celui du muscle","right":false,"ticked":true},{"text":"Celui d\'un cheveu","right":true,"ticked":false},{"text":"Celui d\'un vaisseau","right":false}],"mark":0,"item_type":"multiple-choice-question"}}';

    const VIEW_TEST_RESOURCE_4_4 = '{"type":"pair-items","corrected":true,"content":{"fix_parts":[{"text":"76","object_type":"text"},{"text":"12","object_type":"text"},{"text":"124","object_type":"text"},{"text":"4","object_type":"text"}],"mobile_parts":[{"text":"Programme 1 en C qui a pour sortie ecran 12","object_type":"text"},{"text":"Programme 2 en C qui a pour sortie ecran 124","object_type":"text"},{"text":"Programme 11 en C avec return value de 76","object_type":"text"},{"text":"Programme 10 en C qui renvoit 4","object_type":"text"}],"solutions":[2,0,1,3],"answers":[2,0,1,3],"mark":100,"item_type":"pair-items"}}';

    const VIEW_TEST_RESOURCE_4_5 = '{"type":"pair-items","corrected":true,"content":{"fix_parts":[{"text":"76","object_type":"text"},{"text":"12","object_type":"text"},{"text":"124","object_type":"text"},{"text":"4","object_type":"text"}],"mobile_parts":[{"text":"Programme 1 en C qui a pour sortie ecran 12","object_type":"text"},{"text":"Programme 2 en C qui a pour sortie ecran 124","object_type":"text"},{"text":"Programme 11 en C avec return value de 76","object_type":"text"},{"text":"Programme 10 en C qui renvoit 4","object_type":"text"}],"solutions":[2,0,1,3],"answers":[2,1,0,3],"mark":50,"item_type":"pair-items"}}';

    const VIEW_TEST_RESOURCE_5_6 = '{"type":"group-items","corrected":true,"content":{"display_group_names":"ask","objects":[{"source":"img8.jpeg","object_type":"picture"},{"source":"img2.jpeg","object_type":"picture"},{"source":"img3.jpeg","object_type":"picture"},{"source":"img7.jpeg","object_type":"picture"},{"source":"img6.jpeg","object_type":"picture"},{"source":"img4.jpeg","object_type":"picture"}],"groups":["M","L","S"],"solutions":[2,1,0,2,0,0],"answers":{"obj":[2,1,0,2,0,0],"gr":["M","L","S"]},"mark":100,"item_type":"group-items"}}';

    const VIEW_TEST_RESOURCE_5_7 = '{"type":"group-items","corrected":true,"content":{"display_group_names":"ask","objects":[{"source":"img8.jpeg","object_type":"picture"},{"source":"img2.jpeg","object_type":"picture"},{"source":"img3.jpeg","object_type":"picture"},{"source":"img7.jpeg","object_type":"picture"},{"source":"img6.jpeg","object_type":"picture"},{"source":"img4.jpeg","object_type":"picture"}],"groups":["M","L","S"],"solutions":[2,1,0,2,0,0],"answers":{"obj":[2,1,1,0,0,0],"gr":["M","L","S"]},"mark":73.3333333333,"item_type":"group-items"}}';

    const VIEW_TEST_RESOURCE_6_8 = '{"type":"order-items","corrected":true,"content":{"objects":[{"source":"img5.jpeg","object_type":"picture"},{"source":"img3.jpeg","object_type":"picture"},{"source":"img6.jpeg","object_type":"picture"},{"source":"img8.jpeg","object_type":"picture"},{"source":"img2.jpeg","object_type":"picture"}],"give_first":true,"give_last":true,"solutions":[0,3,1,2,4],"answers":[0,3,1,2,4],"mark":100,"item_type":"order-items"}}';

    const VIEW_TEST_RESOURCE_6_9 = '{"type":"order-items","corrected":true,"content":{"objects":[{"source":"img5.jpeg","object_type":"picture"},{"source":"img3.jpeg","object_type":"picture"},{"source":"img6.jpeg","object_type":"picture"},{"source":"img8.jpeg","object_type":"picture"},{"source":"img2.jpeg","object_type":"picture"}],"give_first":true,"give_last":true,"solutions":[0,3,1,2,4],"answers":[0,1,2,3,4],"mark":40,"item_type":"order-items"}}';

    const TEST_RESOURCE_LIST_1 = '[{"id":1},{"id":10}]';

    const TEST_RESOURCE_LIST_2 = '[{"id":2},{"id":3},{"id":11}]';

    const TEST_RESOURCE_LIST_3 = '[]';

    const TEST_RESOURCE_LIST_4 = '[{"id":4},{"id":5},{"id":12}]';

    const TEST_RESOURCE_LIST_5 = '[{"id":6},{"id":7},{"id":13}]';

    const TEST_RESOURCE_LIST_6 = '[{"id":8},{"id":9}]';

    /**
     * @var string
     */
    protected $path = '/items/{itemId}/answers/{answerId}';

    /**
     * Get provider
     *
     * @return array
     */
    public static function getProvider()
    {
        return array(
            array(
                self::ITEM_ID_TEST_1,
                self::ANSWER_ID_TEST_1,
                ApiResponse::STATUS_CODE_OK,
                self::VIEW_TEST_RESOURCE_1_1
            ),
            array(
                self::ITEM_ID_TEST_2,
                self::ANSWER_ID_TEST_2,
                ApiResponse::STATUS_CODE_OK,
                self::VIEW_TEST_RESOURCE_2_2
            ),
            array(
                self::ITEM_ID_TEST_2,
                self::ANSWER_ID_TEST_3,
                ApiResponse::STATUS_CODE_OK,
                self::VIEW_TEST_RESOURCE_2_3
            ),
            array(
                self::ITEM_ID_TEST_4,
                self::ANSWER_ID_TEST_4,
                ApiResponse::STATUS_CODE_OK,
                self::VIEW_TEST_RESOURCE_4_4
            ),
            array(
                self::ITEM_ID_TEST_4,
                self::ANSWER_ID_TEST_5,
                ApiResponse::STATUS_CODE_OK,
                self::VIEW_TEST_RESOURCE_4_5
            ),
            array(
                self::ITEM_ID_TEST_5,
                self::ANSWER_ID_TEST_6,
                ApiResponse::STATUS_CODE_OK,
                self::VIEW_TEST_RESOURCE_5_6
            ),
            array(
                self::ITEM_ID_TEST_5,
                self::ANSWER_ID_TEST_7,
                ApiResponse::STATUS_CODE_OK,
                self::VIEW_TEST_RESOURCE_5_7
            ),
            array(
                self::ITEM_ID_TEST_6,
                self::ANSWER_ID_TEST_8,
                ApiResponse::STATUS_CODE_OK,
                self::VIEW_TEST_RESOURCE_6_8
            ),
            array(
                self::ITEM_ID_TEST_6,
                self::ANSWER_ID_TEST_9,
                ApiResponse::STATUS_CODE_OK,
                self::VIEW_TEST_RESOURCE_6_9
            ),
            array(
                self::ITEM_ID_TEST_1,
                self::ANSWER_ID_TEST_4,
                ApiResponse::STATUS_CODE_CONTENT_NOT_FOUND,
                self::NOT_FOUND
            ),
            array(
                self::ITEM_ID_TEST_3,
                self::ANSWER_ID_TEST_4,
                ApiResponse::STATUS_CODE_CONTENT_NOT_FOUND,
                self::NOT_FOUND
            ),
            array(
                self::ITEM_ID_TEST_3,
                self::ANSWER_ID_TEST_NOT_EXISTING,
                ApiResponse::STATUS_CODE_CONTENT_NOT_FOUND,
                self::NOT_FOUND
            ),
            array(
                self::ITEM_ID_TEST_NOT_EXISTING,
                self::ANSWER_ID_TEST_1,
                ApiResponse::STATUS_CODE_CONTENT_NOT_FOUND,
                self::NOT_FOUND
            ),
        );
    }

    /**
     * Test GET
     *
     * @param int    $inputItemId        Item id
     * @param int    $inputAnswerId      Answer id
     * @param int    $expectedStatusCode Expected status code
     * @param string $expectedContent    Expected content
     *
     * @dataProvider getProvider
     */
    public function testView($inputItemId, $inputAnswerId, $expectedStatusCode, $expectedContent)
    {
        $response = $this->get(array('itemId' => $inputItemId, 'answerId' => $inputAnswerId));

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
                array('itemId' => self::ITEM_ID_TEST_1),
                ApiResponse::STATUS_CODE_OK,
                self::TEST_RESOURCE_LIST_1

            ),
            array(
                array('itemId' => self::ITEM_ID_TEST_2),
                ApiResponse::STATUS_CODE_OK,
                self::TEST_RESOURCE_LIST_2

            ),
            array(
                array('itemId' => self::ITEM_ID_TEST_3),
                ApiResponse::STATUS_CODE_OK,
                self::TEST_RESOURCE_LIST_3

            ),
            array(
                array('itemId' => self::ITEM_ID_TEST_4),
                ApiResponse::STATUS_CODE_OK,
                self::TEST_RESOURCE_LIST_4

            ),
            array(
                array('itemId' => self::ITEM_ID_TEST_NOT_EXISTING),
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
                self::ITEM_ID_TEST_1,
                '{"content":[0,0,0,1]}',
                ApiResponse::STATUS_CODE_CREATED,
                '#^\{"id":[0-9]+,"content":\[0,0,0,1\]\}$#'
            ),
            array(
                self::ITEM_ID_TEST_4,
                '{"content":[2,0,1,3]}',
                ApiResponse::STATUS_CODE_CREATED,
                '#^\{"id":[0-9]+,"content":\[2,0,1,3\]\}$#'
            ),
            array(
                self::ITEM_ID_TEST_5,
                '{"content":{"obj":[2,1,0,2,0,0],"gr":["M","L","S"]}}',
                ApiResponse::STATUS_CODE_CREATED,
                '#^\{"id":[0-9]+,"content":\{"obj":\[2,1,0,2,0,0\],"gr":\["M","L","S"\]\}\}$#'
            ),
            array(
                self::ITEM_ID_TEST_6,
                '{"content":[0,3,1,2,4]}',
                ApiResponse::STATUS_CODE_CREATED,
                '#^\{"id":[0-9]+,"content":\[0,3,1,2,4\]\}$#'
            ),
            array(
                self::ITEM_ID_TEST_1,
                '{"content":[0,0,0,2]}',
                self::BAD_REQUEST_CODE,
                self::BAD_REQUEST
            ),
            array(
                self::ITEM_ID_TEST_1,
                '{"content":[0,0,"T",0]}',
                self::BAD_REQUEST_CODE,
                self::BAD_REQUEST
            ),
            array(
                self::ITEM_ID_TEST_1,
                '{"content":[0,0,1,0,1]}',
                self::BAD_REQUEST_CODE,
                self::BAD_REQUEST
            ),
            array(
                self::ITEM_ID_TEST_1,
                '{"content":[0,0,0]}',
                self::BAD_REQUEST_CODE,
                self::BAD_REQUEST
            ),
            array(
                self::ITEM_ID_TEST_1,
                '{"content":[0,0,,0]}',
                self::BAD_REQUEST_CODE,
                self::BAD_REQUEST
            ),
            array(
                self::ITEM_ID_TEST_1,
                '{"content":[0,0,0,1],}',
                self::BAD_REQUEST_CODE,
                self::BAD_REQUEST
            ),
            array(
                self::ITEM_ID_TEST_4,
                '{"content":[2,0,6,3]}',
                self::BAD_REQUEST_CODE,
                self::BAD_REQUEST
            ),
            array(
                self::ITEM_ID_TEST_4,
                '{"content":[2,0,1,"t"]}',
                self::BAD_REQUEST_CODE,
                self::BAD_REQUEST
            ),
            array(
                self::ITEM_ID_TEST_4,
                '{"content":[2,0,1,3,2]}',
                self::BAD_REQUEST_CODE,
                self::BAD_REQUEST
            ),
            array(
                self::ITEM_ID_TEST_4,
                '{"content":[0,0,1]}',
                self::BAD_REQUEST_CODE,
                self::BAD_REQUEST
            ),
            array(
                self::ITEM_ID_TEST_4,
                '{"content":[2,1,,3]}',
                self::BAD_REQUEST_CODE,
                self::BAD_REQUEST
            ),
            array(
                self::ITEM_ID_TEST_4,
                '{"content":[2,0,1,3],}',
                self::BAD_REQUEST_CODE,
                self::BAD_REQUEST
            ),
            array(
                self::ITEM_ID_TEST_5,
                '{"content":{"obj":[2,1,0,3,0,0],"gr":["M","L","S"]}}',
                self::BAD_REQUEST_CODE,
                self::BAD_REQUEST
            ),
            array(
                self::ITEM_ID_TEST_5,
                '{"content":{"obj":[2,1,"zop",2,0,0],"gr":["M","L","S"]}}',
                self::BAD_REQUEST_CODE,
                self::BAD_REQUEST
            ),
            array(
                self::ITEM_ID_TEST_5,
                '{"content":{"obj":[2,1,0,2,1,0,0],"gr":["M","L","S"]}}',
                self::BAD_REQUEST_CODE,
                self::BAD_REQUEST
            ),
            array(
                self::ITEM_ID_TEST_5,
                '{"content":{"obj":[2,1,0,2,0,0],"gr":["M","L","S","S"]}}',
                self::BAD_REQUEST_CODE,
                self::BAD_REQUEST
            ),
            array(
                self::ITEM_ID_TEST_5,
                '{"content":{"obj":[2,1,0,2,0,0],"gr":["M","S"]}}',
                self::BAD_REQUEST_CODE,
                self::BAD_REQUEST
            ),
            array(
                self::ITEM_ID_TEST_5,
                '{"content":{"obj":[2,1,0,2,0],"gr":["M","L","S"]}}',
                self::BAD_REQUEST_CODE,
                self::BAD_REQUEST
            ),
            array(
                self::ITEM_ID_TEST_5,
                '{"content":{"obj":[2,1,1,0,0,0],"gr":["M",,"S"]}}',
                self::BAD_REQUEST_CODE,
                self::BAD_REQUEST
            ),
            array(
                self::ITEM_ID_TEST_5,
                '{"content":{"obj":[2,1,1,0,,0],"gr":["M","L","S"]}}',
                self::BAD_REQUEST_CODE,
                self::BAD_REQUEST
            ),
            array(
                self::ITEM_ID_TEST_5,
                '{"content":{"obj":[2,1,0,2,0,0],"gr":["M","L","S"]},}',
                self::BAD_REQUEST_CODE,
                self::BAD_REQUEST
            ),
            array(
                self::ITEM_ID_TEST_6,
                '{"content":[0,3,5,2,4]}',
                self::BAD_REQUEST_CODE,
                self::BAD_REQUEST
            ),
            array(
                self::ITEM_ID_TEST_6,
                '{"content":[0,3,"E",2,4]}',
                self::BAD_REQUEST_CODE,
                self::BAD_REQUEST
            ),
            array(
                self::ITEM_ID_TEST_6,
                '{"content":[0,1,2,4]}',
                self::BAD_REQUEST_CODE,
                self::BAD_REQUEST
            ),
            array(
                self::ITEM_ID_TEST_6,
                '{"content":[0,3,1,3,2,4]}',
                self::BAD_REQUEST_CODE,
                self::BAD_REQUEST
            ),
            array(
                self::ITEM_ID_TEST_6,
                '{"content":[0,3,,2,4]}',
                self::BAD_REQUEST_CODE,
                self::BAD_REQUEST
            ),
            array(
                self::ITEM_ID_TEST_6,
                '{"content":[0,3,1,2,4],}',
                self::BAD_REQUEST_CODE,
                self::BAD_REQUEST
            )
        );
    }

    /**
     * Test POST
     *
     * @param int    $itemIdentifier     Item id
     * @param string $inputContent       Answer content
     * @param int    $expectedStatusCode Expected status code
     * @param string $expectedContent    Expected response content
     *
     * @dataProvider postProvider
     */
    public function testCreate(
        $itemIdentifier,
        $inputContent,
        $expectedStatusCode,
        $expectedContent
    )
    {
        $response = $this->post(array('itemId' => $itemIdentifier), $inputContent);

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
