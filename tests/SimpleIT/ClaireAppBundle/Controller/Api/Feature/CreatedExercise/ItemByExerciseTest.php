<?php
namespace SimpleIT\ExerciseBundle\Feature\CreatedExercise;

use SimpleIT\ApiBundle\Model\ApiResponse;
use OC\Tester\ApiFeatureTest;
use SimpleIT\Utils\FormatUtils;

/**
 * Test ItemByExerciseController
 *
 * @author Baptiste Cable <baptiste.cable@liris.cnrs.fr>
 */
class ItemByExerciseTest extends ApiFeatureTest
{

    const ID_TEST_1 = 1;

    const ID_TEST_2 = 2;

    const ID_TEST_3 = 3;

    const ID_TEST_4 = 4;

    const TEST_RESOURCE_IN_LIST_1 = '[{"item_id":1,"type":"multiple-choice"},{"item_id":2,"type":"multiple-choice"},{"item_id":3,"type":"multiple-choice"}]';

    const TEST_RESOURCE_IN_LIST_2 = '[{"item_id":4,"type":"pair-items"}]';

    const TEST_RESOURCE_IN_LIST_3 = '[{"item_id":5,"type":"group-items"}]';

    const TEST_RESOURCE_IN_LIST_4 = '[{"item_id":6,"type":"order-items"}]';

    /**
     * @var string
     */
    protected $path = '/exercises/{exerciseId}/items/';

    /**
     * Get list provider
     *
     * @return array
     */
    public static function getListProvider()
    {
        return array(
            array(
                array('exerciseId' => self::ID_TEST_1),
                ApiResponse::STATUS_CODE_OK,
                self::TEST_RESOURCE_IN_LIST_1

            ),
            array(
                array('exerciseId' => self::ID_TEST_2),
                ApiResponse::STATUS_CODE_OK,
                self::TEST_RESOURCE_IN_LIST_2

            ),
            array(
                array('exerciseId' => self::ID_TEST_3),
                ApiResponse::STATUS_CODE_OK,
                self::TEST_RESOURCE_IN_LIST_3

            ),
            array(
                array('exerciseId' => self::ID_TEST_4),
                ApiResponse::STATUS_CODE_OK,
                self::TEST_RESOURCE_IN_LIST_4

            ),
            array(
                array('exerciseId' => 250),
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