<?php
namespace SimpleIT\ClaireExerciseBundle\Feature\CreatedExercise;

use SimpleIT\ApiBundle\Model\ApiResponse;
use OC\Tester\ApiFeatureTest;
use SimpleIT\Utils\FormatUtils;

/**
 * Test Stored Exercises.
 *
 * @author Baptiste Cable <baptiste.cable@liris.cnrs.fr>
 */
class ItemTest extends ApiFeatureTest
{

    const ID_TEST_1 = 1;

    const ID_TEST_2 = 4;

    const ID_TEST_3 = 5;

    const ID_TEST_4 = 6;

    const ID_TEST_NOT_EXISTING = 250;

    const TEST_RESOURCE_1 = '{"item_id":1,"type":"multiple-choice","corrected":false,"content":{"comment":"Les cotes sont numerotees de 1 a 9 et seules les 7 premi\u00e8res sont fixes et dites \"vraies cotes\".","propositions":[{"text":"autre","right":false},{"text":"9","right":false},{"text":"6","right":false},{"text":"7","right":true}],"item_type":"multiple-choice-question"}}';

    const TEST_RESOURCE_2 = '{"item_id":4,"type":"pair-items","corrected":false,"content":{"fix_parts":[{"text":"76","object_type":"text"},{"text":"12","object_type":"text"},{"text":"124","object_type":"text"},{"text":"4","object_type":"text"}],"mobile_parts":[{"text":"Programme 1 en C qui a pour sortie ecran 12","object_type":"text"},{"text":"Programme 2 en C qui a pour sortie ecran 124","object_type":"text"},{"text":"Programme 11 en C avec return value de 76","object_type":"text"},{"text":"Programme 10 en C qui renvoit 4","object_type":"text"}],"solutions":[[2],[0],[1],[3]],"item_type":"pair-items"}}';

    const TEST_RESOURCE_3 = '{"item_id":5,"type":"group-items","corrected":false,"content":{"display_group_names":"ask","objects":[{"source":"img8.jpeg","object_type":"picture"},{"source":"img2.jpeg","object_type":"picture"},{"source":"img3.jpeg","object_type":"picture"},{"source":"img7.jpeg","object_type":"picture"},{"source":"img6.jpeg","object_type":"picture"},{"source":"img4.jpeg","object_type":"picture"}],"groups":["M","L","S"],"solutions":[2,1,0,2,0,0],"item_type":"group-items"}}';

    const TEST_RESOURCE_4 = '{"item_id":6,"type":"order-items","corrected":false,"content":{"objects":[{"source":"img5.jpeg","object_type":"picture"},{"source":"img3.jpeg","object_type":"picture"},{"source":"img6.jpeg","object_type":"picture"},{"source":"img8.jpeg","object_type":"picture"},{"source":"img2.jpeg","object_type":"picture"}],"give_first":true,"give_last":true,"solutions":{"name":"or","0":0,"1":3,"2":1,"3":2,"4":4},"item_type":"order-items"}}';

    const TEST_RESOURCE_IN_LIST_1 = '{"item_id":1,"type":"multiple-choice"}';

    const TEST_RESOURCE_IN_LIST_2 = '{"item_id":2,"type":"multiple-choice"}';

    const TEST_RESOURCE_IN_LIST_3 = '{"item_id":3,"type":"multiple-choice"}';

    const TEST_RESOURCE_IN_LIST_4 = '{"item_id":4,"type":"pair-items"}';

    const TEST_RESOURCE_IN_LIST_5 = '{"item_id":5,"type":"group-items"}';

    const TEST_RESOURCE_IN_LIST_6 = '{"item_id":6,"type":"order-items"}';

    const TEST_RESOURCE_IN_LIST_7 = '{"item_id":7,"type":"multiple-choice"}';

    const TEST_RESOURCE_IN_LIST_8 = '{"item_id":8,"type":"multiple-choice"}';

    const TEST_RESOURCE_IN_LIST_9 = '{"item_id":9,"type":"multiple-choice"}';

    /**
     * @var string
     */
    protected $path = '/items/{itemId}';

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
        $response = $this->get(array('itemId' => $inputIdentifier));

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
        $expectedListNormal = '[' .
            self::TEST_RESOURCE_IN_LIST_1 . ',' .
            self::TEST_RESOURCE_IN_LIST_2 . ',' .
            self::TEST_RESOURCE_IN_LIST_3 . ',' .
            self::TEST_RESOURCE_IN_LIST_4 . ',' .
            self::TEST_RESOURCE_IN_LIST_5 . ',' .
            self::TEST_RESOURCE_IN_LIST_6 . ',' .
            self::TEST_RESOURCE_IN_LIST_7 . ',' .
            self::TEST_RESOURCE_IN_LIST_8 . ',' .
            self::TEST_RESOURCE_IN_LIST_9 . ']';

        return array(
            array(ApiResponse::STATUS_CODE_OK, $expectedListNormal)
        );
    }

    /**
     * Test GET on collection
     *
     * @param integer $expectedStatusCode Expected status code
     * @param string  $expectedContent    Expected content
     * @param array   $inputParameters    Parameters
     * @param array   $inputRange         Range (min, max)
     *
     * @dataProvider getListProvider
     */
    public function testList(
        $expectedStatusCode,
        $expectedContent,
        array $inputParameters = array(),
        array $inputRange = array()
    )
    {
        $response = $this->getAll(
            array(),
            $inputParameters,
            array(),
            $inputRange,
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
