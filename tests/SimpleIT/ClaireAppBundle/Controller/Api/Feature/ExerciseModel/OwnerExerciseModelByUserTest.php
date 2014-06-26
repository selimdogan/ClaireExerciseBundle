<?php
namespace SimpleIT\ClaireExerciseBundle\Feature\ExerciseModel;

use SimpleIT\ApiBundle\Model\ApiResponse;
use OC\Tester\ApiFeatureTest;
use SimpleIT\Utils\FormatUtils;

/**
 * Test OwnerExerciseModelByUser Controller
 *
 * @author Baptiste Cable <baptiste.cable@liris.cnrs.fr>
 */
class OwnerExerciseModelByUserTest extends ApiFeatureTest
{
    const BAD_REQUEST_CODE = 400;

    const BAD_REQUEST = 'Bad Request';

    const NOT_FOUND = 'Not Found';

    const ID_USER_1 = 1000001;

    const ID_USER_2 = 1000002;

    const ID_OWNER_MODEL_1 = 1;

    const ID_OWNER_MODEL_2 = 2;

    const ID_NOT_EXISTING = 250;

    /**
     * @var string
     */
    protected $path = '/users/{userId}/owner-exercise-models/{ownerExerciseModelId}';

    /**
     * Get provider
     *
     * @return array
     */
    public static function getProvider()
    {
        return array(
            array(
                self::ID_USER_1,
                self::ID_OWNER_MODEL_1,
                ApiResponse::STATUS_CODE_OK,
                '{"id":1,"exercise_model":1,"owner":1000001,"public":true,"metadata":{"matiere":"Anatomie","niveau":"L1","_misc":"anatomie;test;basique"},"type":"multiple-choice","title":"Bases de l\'anatomie","draft":false,"complete":true}'
            ),
            array(
                self::ID_USER_1,
                self::ID_OWNER_MODEL_2,
                ApiResponse::STATUS_CODE_OK,
                '{"id":2,"exercise_model":2,"owner":1000001,"public":true,"metadata":{"niveau":"L1","matiere":"Programmation"},"type":"pair-items","title":"Appariement de test n 2","draft":false,"complete":true}'
            ),
            array(
                self::ID_USER_2,
                self::ID_OWNER_MODEL_2,
                ApiResponse::STATUS_CODE_CONTENT_NOT_FOUND,
                self::NOT_FOUND
            ),
            array(
                self::ID_USER_1,
                self::ID_NOT_EXISTING,
                ApiResponse::STATUS_CODE_CONTENT_NOT_FOUND,
                self::NOT_FOUND
            ),
            array(
                self::ID_NOT_EXISTING,
                self::ID_OWNER_MODEL_2,
                ApiResponse::STATUS_CODE_CONTENT_NOT_FOUND,
                self::NOT_FOUND
            ),
        );
    }

    /**
     * Test GET
     *
     * @param int    $userId
     * @param int    $ownerExerciseModelId
     * @param int    $expectedStatusCode Expected status code
     * @param string $expectedContent    Expected content
     *
     * @dataProvider getProvider
     */
    public function testView(
        $userId,
        $ownerExerciseModelId,
        $expectedStatusCode,
        $expectedContent
    )
    {
        $response = $this->get(
            array(
                'userId'               => $userId,
                'ownerExerciseModelId' => $ownerExerciseModelId
            )
        );

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
                self::ID_USER_1,
                ApiResponse::STATUS_CODE_OK,
                '[{"id":1,"exercise_model":1,"owner":1000001,"public":true,"metadata":{"matiere":"Anatomie","niveau":"L1","_misc":"anatomie;test;basique"},"type":"multiple-choice","title":"Bases de l\'anatomie","draft":false,"complete":true,"content":{"wording":"Pour chacune des questions suivantes, indiquez toutes les reponses justes (une ou plusieurs).","exercise_model_type":"multiple-choice"}},{"id":2,"exercise_model":2,"owner":1000001,"public":true,"metadata":{"niveau":"L1","matiere":"Programmation"},"type":"pair-items","title":"Appariement de test n 2","draft":false,"complete":true,"content":{"wording":"Associez la sortie ecran ou la valeur de retour au programme correspondant.","exercise_model_type":"pair-items"}},{"id":3,"exercise_model":3,"owner":1000001,"public":true,"metadata":{"niveau":"TS","matiere":"SVT"},"type":"group-items","title":"Groupement de test n 1","draft":false,"complete":true,"content":{"wording":"Classez les animaux par categorie de taille et indiquez la categorie.","exercise_model_type":"group-items"}},{"id":4,"exercise_model":4,"owner":1000001,"public":true,"metadata":{"niveau":"TS","matiere":"SVT"},"type":"order-items","title":"Ordonnancement de test 2","draft":false,"complete":true,"content":{"wording":"Classez du plus petit au plus grand.","exercise_model_type":"order-items"}}]'
            ),
            array(
                self::ID_USER_2,
                ApiResponse::STATUS_CODE_OK,
                '[]'
            ),
            array(
                self::ID_NOT_EXISTING,
                ApiResponse::STATUS_CODE_CONTENT_NOT_FOUND,
                'Not Found'
            )
        );
    }

    /**
     * Test GET on collection
     *
     * @param int     $inputIdentifier    User id
     * @param integer $expectedStatusCode Expected status code
     * @param string  $expectedContent    Expected content
     * @param array   $inputParameters    Parameters
     * @param array   $inputRange         Range (min, max)
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
            array('userId' => $inputIdentifier),
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
