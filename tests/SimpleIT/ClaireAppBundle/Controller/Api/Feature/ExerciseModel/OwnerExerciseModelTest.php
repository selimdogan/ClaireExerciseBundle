<?php
namespace SimpleIT\ExerciseBundle\Feature\ExerciseModel;

use SimpleIT\ApiBundle\Model\ApiResponse;
use OC\Tester\ApiFeatureTest;
use SimpleIT\Utils\FormatUtils;

/**
 * Test OwnerExerciseModel Controller
 *
 * @author Baptiste Cable <baptiste.cable@liris.cnrs.fr>
 */
class OwnerExerciseModelTest extends ApiFeatureTest
{
    const BAD_REQUEST_CODE = 400;

    const BAD_REQUEST = "Bad Request";

    const NOT_FOUND = 'Not Found';

    const ID_TEST_1 = 1;

    const ID_TEST_2 = 2;

    const ID_TEST_3 = 3;

    const ID_TEST_4 = 4;

    const ID_TEST_NOT_EXISTING = 250;

    const TEST_RESOURCE_1 = '\{"id":1,"exercise_model":1,"owner":1000001,"public":true,"metadata":\{.*\},"type":"multiple-choice","title":"Bases de l\'anatomie","draft":false,"complete":true\}';

    const TEST_RESOURCE_2 = '\{"id":2,"exercise_model":2,"owner":1000001,"public":true,"metadata":\{.*\},"type":"pair-items","title":"Appariement de test n 2","draft":false,"complete":true\}';

    const TEST_RESOURCE_3 = '\{"id":3,"exercise_model":3,"owner":1000001,"public":true,"metadata":\{.*\},"type":"group-items","title":"Groupement de test n 1","draft":false,"complete":true\}';

    const TEST_RESOURCE_4 = '\{"id":4,"exercise_model":4,"owner":1000001,"public":true,"metadata":\{.*\},"type":"order-items","title":"Ordonnancement de test 2","draft":false,"complete":true\}';

    const TEST_RESOURCE_LIST_1 = '#^\[\{"id":1,"exercise_model":1,"owner":1000001,"public":true,"metadata":\{.*\},"type":"multiple-choice","title":"Bases de l\'anatomie","draft":false,"complete":true,"content":\{"wording":"Pour chacune des questions suivantes, indiquez toutes les reponses justes \(une ou plusieurs\).","exercise_model_type":"multiple-choice"\}\},\{"id":2,"exercise_model":2,"owner":1000001,"public":true,"metadata":\{.*\},"type":"pair-items","title":"Appariement de test n 2","draft":false,"complete":true,"content":\{"wording":"Associez la sortie ecran ou la valeur de retour au programme correspondant.","exercise_model_type":"pair-items"\}\},\{"id":3,"exercise_model":3,"owner":1000001,"public":true,"metadata":\{.*\},"type":"group-items","title":"Groupement de test n 1","draft":false,"complete":true,"content":\{"wording":"Classez les animaux par categorie de taille et indiquez la categorie.","exercise_model_type":"group-items"\}\},\{"id":4,"exercise_model":4,"owner":1000001,"public":true,"metadata":\{.*\},"type":"order-items","title":"Ordonnancement de test 2","draft":false,"complete":true,"content":\{"wording":"Classez du plus petit au plus grand.","exercise_model_type":"order-items"\}\}\]$#';

    const TEST_RESOURCE_LIST_2 = '#^\[\{"id":1,"exercise_model":1,"owner":1000001,"public":true,"metadata":\{.*\},"type":"multiple-choice","title":"Bases de l\'anatomie","draft":false,"complete":true,"content":\{"wording":"Pour chacune des questions suivantes, indiquez toutes les reponses justes \(une ou plusieurs\).","exercise_model_type":"multiple-choice"\}\},\{"id":2,"exercise_model":2,"owner":1000001,"public":true,"metadata":\{.*\},"type":"pair-items","title":"Appariement de test n 2","draft":false,"complete":true,"content":\{"wording":"Associez la sortie ecran ou la valeur de retour au programme correspondant.","exercise_model_type":"pair-items"\}\}\]$#';

    const TEST_RESOURCE_LIST_3 = '#^\[\{"id":1,"exercise_model":1,"owner":1000001,"public":true,"metadata":\{.*\},"type":"multiple-choice","title":"Bases de l\'anatomie","draft":false,"complete":true,"content":\{"wording":"Pour chacune des questions suivantes, indiquez toutes les reponses justes \(une ou plusieurs\).","exercise_model_type":"multiple-choice"\}\}\]$#';

    const TEST_RESOURCE_LIST_4 = '#^\[\]$#';

    /**
     * @var string
     */
    protected $path = '/owner-exercise-models/{ownerExerciseModelId}';

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
        $response = $this->get(array('ownerExerciseModelId' => $inputIdentifier));

        $this->assertEquals($expectedStatusCode, $response->getStatusCode());
        $this->assertRegExp('#^' . $expectedContent . "$#", $response->getContent());
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
                ApiResponse::STATUS_CODE_OK,
                self::TEST_RESOURCE_LIST_1
            ),
            array(
                ApiResponse::STATUS_CODE_OK,
                self::TEST_RESOURCE_LIST_2,
                array('metadata' => 'niveau:L1')
            ),
            array(
                ApiResponse::STATUS_CODE_OK,
                self::TEST_RESOURCE_LIST_3,
                array('metadata' => 'niveau:L1', 'keywords' => 'anatomie')
            ),
            array(
                ApiResponse::STATUS_CODE_OK,
                self::TEST_RESOURCE_LIST_4,
                array("public-except-user" => 1000001)
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
        $this->assertRegExp($expectedContent, $response->getContent());
    }

    /**
     * Delete provider
     *
     * @return array
     */
    public static function deleteProvider()
    {
        return array(
            array(self::ID_TEST_1, ApiResponse::STATUS_CODE_NO_CONTENT),
            array(
                self::ID_TEST_NOT_EXISTING,
                ApiResponse::STATUS_CODE_CONTENT_NOT_FOUND,
            )
        );
    }

    /**
     * Test DELETE
     *
     * @param int $inputIdentifier    Exercise id
     * @param int $expectedStatusCode Expected status code
     *
     * @dataProvider deleteProvider
     */
    public function testDelete($inputIdentifier, $expectedStatusCode)
    {
        $response = $this->delete(array('ownerExerciseModelId' => $inputIdentifier));

        $this->assertEquals($expectedStatusCode, $response->getStatusCode());
    }

    /**
     * Put provider
     *
     * @return array
     */
    public static function putProvider()
    {
        return array(
            array(
                self::ID_TEST_2,
                '{"public":false}',
                ApiResponse::STATUS_CODE_OK,
                '#^\{"id":2,"exercise_model":2,"owner":1000001,"public":false,"metadata":\{"niveau":"L1","matiere":"Programmation"\},"type":"pair-items","title":"Appariement de test n 2","draft":false,"complete":true,"content":\{"wording":"Associez la sortie ecran ou la valeur de retour au programme correspondant.","documents":\[\],"pair_blocks":\[\{"number_of_occurrences":2,"resources":\[\{"id":109\},\{"id":110\},\{"id":111\},\{"id":112\},\{"id":113\},\{"id":114\},\{"id":115\},\{"id":116\}\],"pair_meta_key":"screen-output"\},\{"number_of_occurrences":2,"resources":\[\{"id":117\},\{"id":118\},\{"id":119\},\{"id":120\},\{"id":121\},\{"id":122\},\{"id":123\},\{"id":124\}\],"pair_meta_key":"return-value"\}\],"exercise_model_type":"pair-items"\}\}$#'
            ),
            array(
                self::ID_TEST_2,
                '{"exercise_model":4}',
                ApiResponse::STATUS_CODE_OK,
                '#^\{"id":2,"exercise_model":4,"owner":1000001,"public":true,"metadata":\{"niveau":"L1","matiere":"Programmation"\},"type":"order-items","title":"Ordonnancement de test 2","draft":false,"complete":true,"content":\{"wording":"Classez du plus petit au plus grand.","documents":\[\],"sequence_block":\{"resources":\[\{"id":154\}\],"keep_all":false,"number_of_parts":5,"use_first":true,"use_last":true\},"object_blocks":\[\],"give_first":true,"give_last":true,"exercise_model_type":"order-items"\}\}$#'
            ),
            array(
                self::ID_TEST_2,
                '{"id":5,"public":false}',
                self::BAD_REQUEST_CODE,
                '#' . self::BAD_REQUEST . '#'
            ),
            array(
                self::ID_TEST_2,
                '{"metadata":{"doo":"mee"}}',
                self::BAD_REQUEST_CODE,
                '#' . self::BAD_REQUEST . '#'
            ),
        );
    }

    /**
     * Test PUT
     *
     * @param string $ownerExerciseModelId
     * @param string $content
     * @param int    $expectedStatusCode Expected status code
     * @param string $expectedContent
     *
     * @dataProvider putProvider
     */
    public function testEdit(
        $ownerExerciseModelId,
        $content,
        $expectedStatusCode,
        $expectedContent
    )
    {
        $response = $this->put(
            array('ownerExerciseModelId' => $ownerExerciseModelId),
            $content
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
