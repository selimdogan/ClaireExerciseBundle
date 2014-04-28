<?php
namespace SimpleIT\ClaireExerciseBundle\Feature\CreatedExercise;

use OC\Tester\ApiFeatureTest;
use SimpleIT\ApiBundle\Model\ApiResponse;
use SimpleIT\Utils\FormatUtils;

/**
 * Test ItemByAttemptController test
 *
 * @author Baptiste Cable <baptiste.cable@liris.cnrs.fr>
 */
class ItemByAttemptTest extends ApiFeatureTest
{
    const ID_TEST_1 = 1;

    const ID_TEST_2 = 2;

    const ID_TEST_3 = 3;

    const ID_TEST_4 = 4;

    const ID_TEST_5 = 5;

    const ID_TEST_6 = 6;

    const ITEM_ID_TEST_1 = 1;

    const ITEM_ID_TEST_2 = 2;

    const ITEM_ID_TEST_3 = 3;

    const ITEM_ID_TEST_4 = 4;

    const ITEM_ID_TEST_5 = 5;

    const ITEM_ID_TEST_6 = 6;

    const ID_TEST_NOT_EXISTING = 250;

    const NOT_FOUND = 'Not Found';

    const TEST_RESOURCE_1_1 = '{"type":"multiple-choice","corrected":true,"content":{"comment":"Les cotes sont numerotees de 1 a 9 et seules les 7 premi\u00e8res sont fixes et dites \"vraies cotes\".","propositions":[{"text":"autre","right":false,"ticked":false},{"text":"9","right":false,"ticked":false},{"text":"6","right":false,"ticked":false},{"text":"7","right":true,"ticked":true}],"mark":100,"item_type":"multiple-choice-question"}}';

    const TEST_RESOURCE_1_2 = '{"type":"multiple-choice","corrected":true,"content":{"comment":"Les fibres musculaires sont extr\u00eamement fines et il y en a un tr\u00e8s grand nombre dans chaque muscle.","question":"Le diam\u00e8tre d\'une fibre musculaire correspond a :","propositions":[{"text":"Celui d\'un nerf","right":false,"ticked":false},{"text":"Celui d\'une art\u00e8re","right":false,"ticked":false},{"text":"Celui du muscle","right":false,"ticked":true},{"text":"Celui d\'un cheveu","right":true,"ticked":false},{"text":"Celui d\'un vaisseau","right":false}],"mark":0,"item_type":"multiple-choice-question"}}';

    const TEST_RESOURCE_1_3 = '{"item_id":3,"type":"multiple-choice","corrected":false,"content":{"comment":"La contraction est isometrique lorsque la longueur du muscle ne change pas, par exemple, lors du maitien d\'une charge. Par contre, son volume peut changer.","question":"Une contraction isometrique correspond a :","propositions":[{"text":"Une contraction symetrique","right":false},{"text":"Une contraction o\u00f9 le volume du muscle ne change pas","right":false},{"text":"La contraction d\'un muscle isomorphe","right":false},{"text":"Une contraction sans raccourcissement","right":true}],"item_type":"multiple-choice-question"}}';

    const TEST_RESOURCE_2_4 = '{"type":"pair-items","corrected":true,"content":{"fix_parts":[{"text":"76","object_type":"text"},{"text":"12","object_type":"text"},{"text":"124","object_type":"text"},{"text":"4","object_type":"text"}],"mobile_parts":[{"text":"Programme 1 en C qui a pour sortie ecran 12","object_type":"text"},{"text":"Programme 2 en C qui a pour sortie ecran 124","object_type":"text"},{"text":"Programme 11 en C avec return value de 76","object_type":"text"},{"text":"Programme 10 en C qui renvoit 4","object_type":"text"}],"solutions":[2,0,1,3],"answers":[2,0,1,3],"mark":100,"item_type":"pair-items"}}';

    const TEST_RESOURCE_3_5 = '{"type":"group-items","corrected":true,"content":{"display_group_names":"ask","objects":[{"source":"img8.jpeg","object_type":"picture"},{"source":"img2.jpeg","object_type":"picture"},{"source":"img3.jpeg","object_type":"picture"},{"source":"img7.jpeg","object_type":"picture"},{"source":"img6.jpeg","object_type":"picture"},{"source":"img4.jpeg","object_type":"picture"}],"groups":["M","L","S"],"solutions":[2,1,0,2,0,0],"answers":{"obj":[2,1,0,2,0,0],"gr":["M","L","S"]},"mark":100,"item_type":"group-items"}}';

    const TEST_RESOURCE_4_6 = '{"item_id":6,"type":"order-items","corrected":false,"content":{"objects":[{"source":"img5.jpeg","object_type":"picture"},{"source":"img3.jpeg","object_type":"picture"},{"source":"img6.jpeg","object_type":"picture"},{"source":"img8.jpeg","object_type":"picture"},{"source":"img2.jpeg","object_type":"picture"}],"give_first":true,"give_last":true,"solutions":{"name":"or","0":0,"1":3,"2":1,"3":2,"4":4},"item_type":"order-items"}}';

    const TEST_RESOURCE_LIST_1 = '[{"item_id":1,"type":"multiple-choice"},{"item_id":2,"type":"multiple-choice"},{"item_id":3,"type":"multiple-choice"}]';

    const TEST_RESOURCE_LIST_2 = '[{"item_id":4,"type":"pair-items"}]';

    const TEST_RESOURCE_LIST_3 = '[{"item_id":5,"type":"group-items"}]';

    const TEST_RESOURCE_LIST_4 = '[{"item_id":6,"type":"order-items"}]';

    const TEST_RESOURCE_LIST_5 = '[{"item_id":4,"type":"pair-items"}]';

    const TEST_RESOURCE_LIST_6 = '[{"item_id":1,"type":"multiple-choice"},{"item_id":2,"type":"multiple-choice"},{"item_id":3,"type":"multiple-choice"}]';

    /**
     * @var string
     */
    protected $path = '/attempts/{attemptId}/items/{itemId}';

    /**
     * Get provider
     *
     * @return array
     */
    public static function getProvider()
    {
        return array(
            array(
                array('attemptId' => self::ID_TEST_1, 'itemId' => self::ITEM_ID_TEST_1),
                ApiResponse::STATUS_CODE_OK,
                self::TEST_RESOURCE_1_1
            ),
            array(
                array('attemptId' => self::ID_TEST_1, 'itemId' => self::ITEM_ID_TEST_2),
                ApiResponse::STATUS_CODE_OK,
                self::TEST_RESOURCE_1_2
            ),
            array(
                array('attemptId' => self::ID_TEST_1, 'itemId' => self::ITEM_ID_TEST_3),
                ApiResponse::STATUS_CODE_OK,
                self::TEST_RESOURCE_1_3
            ),
            array(
                array('attemptId' => self::ID_TEST_1, 'itemId' => self::ITEM_ID_TEST_4),
                ApiResponse::STATUS_CODE_CONTENT_NOT_FOUND,
                self::NOT_FOUND
            ),
            array(
                array('attemptId' => self::ID_TEST_2, 'itemId' => self::ITEM_ID_TEST_4),
                ApiResponse::STATUS_CODE_OK,
                self::TEST_RESOURCE_2_4
            ),
            array(
                array('attemptId' => self::ID_TEST_3, 'itemId' => self::ITEM_ID_TEST_5),
                ApiResponse::STATUS_CODE_OK,
                self::TEST_RESOURCE_3_5
            ),
            array(
                array('attemptId' => self::ID_TEST_4, 'itemId' => self::ITEM_ID_TEST_6),
                ApiResponse::STATUS_CODE_OK,
                self::TEST_RESOURCE_4_6
            ),
            array(
                array('attemptId' => self::ID_TEST_NOT_EXISTING, 'itemId' => self::ITEM_ID_TEST_6),
                ApiResponse::STATUS_CODE_CONTENT_NOT_FOUND,
                self::NOT_FOUND
            ),
        );
    }

    /**
     * Test GET
     *
     * @param array  $inputIdentifiers    Exercise id
     * @param int    $expectedStatusCode  Expected status code
     * @param string $expectedContent     Expected content
     *
     * @dataProvider getProvider
     */
    public function testView($inputIdentifiers, $expectedStatusCode, $expectedContent)
    {
        $response = $this->get($inputIdentifiers);

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
                self::ID_TEST_1,
                ApiResponse::STATUS_CODE_OK,
                self::TEST_RESOURCE_LIST_1,
            ),
            array(
                self::ID_TEST_2,
                ApiResponse::STATUS_CODE_OK,
                self::TEST_RESOURCE_LIST_2,
            ),
            array(
                self::ID_TEST_3,
                ApiResponse::STATUS_CODE_OK,
                self::TEST_RESOURCE_LIST_3,
            ),
            array(
                self::ID_TEST_4,
                ApiResponse::STATUS_CODE_OK,
                self::TEST_RESOURCE_LIST_4,
            ),
            array(
                self::ID_TEST_5,
                ApiResponse::STATUS_CODE_OK,
                self::TEST_RESOURCE_LIST_5,
            ),
            array(
                self::ID_TEST_6,
                ApiResponse::STATUS_CODE_OK,
                self::TEST_RESOURCE_LIST_6,
            ),
            array(
                self::ID_TEST_NOT_EXISTING,
                ApiResponse::STATUS_CODE_CONTENT_NOT_FOUND,
                'Not Found',
            ),
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
            array('attemptId' => $inputIdentifier),
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
