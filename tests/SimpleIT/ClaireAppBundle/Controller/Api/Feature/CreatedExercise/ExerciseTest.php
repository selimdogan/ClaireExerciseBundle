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
class ExerciseTest extends ApiFeatureTest
{

    const ID_TEST_1 = 1;

    const ID_TEST_2 = 2;

    const ID_TEST_3 = 3;

    const ID_TEST_4 = 4;

    const ID_TEST_NOT_EXISTING = 250;

    const TEST_RESOURCE_1 = '{"id":1,"owner_exercise_model":1,"content":{"wording":"Pour chacune des questions suivantes, indiquez toutes les reponses justes (une ou plusieurs).","documents":[],"item_count":3,"exercise_type":"multiple-choice"}}';

    const TEST_RESOURCE_2 = '{"id":2,"owner_exercise_model":2,"content":{"wording":"Associez la sortie ecran ou la valeur de retour au programme correspondant.","documents":[],"item_count":1,"exercise_type":"pair-items"}}';

    const TEST_RESOURCE_3 = '{"id":3,"owner_exercise_model":3,"content":{"wording":"Classez les animaux par categorie de taille et indiquez la categorie.","documents":[],"item_count":1,"exercise_type":"group-items"}}';

    const TEST_RESOURCE_4 = '{"id":4,"owner_exercise_model":4,"content":{"wording":"Classez du plus petit au plus grand.","documents":[],"item_count":1,"exercise_type":"order-items"}}';

    const TEST_RESOURCE_IN_LIST_1 = '{"id":1,"owner_exercise_model":1}';

    const TEST_RESOURCE_IN_LIST_2 = '{"id":2,"owner_exercise_model":2}';

    const TEST_RESOURCE_IN_LIST_3 = '{"id":3,"owner_exercise_model":3}';

    const TEST_RESOURCE_IN_LIST_4 = '{"id":4,"owner_exercise_model":4}';

    const TEST_RESOURCE_IN_LIST_5 = '{"id":5,"owner_exercise_model":1}';

    /**
     * @var string
     */
    protected $path = '/exercises/{exerciseId}';

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
        $response = $this->get(array('exerciseId' => $inputIdentifier));

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
            self::TEST_RESOURCE_IN_LIST_5 . ']';

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
