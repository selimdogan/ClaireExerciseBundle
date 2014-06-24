<?php
namespace SimpleIT\ClaireExerciseBundle\Feature\ExerciseModel;

use SimpleIT\ApiBundle\Model\ApiResponse;
use OC\Tester\ApiFeatureTest;
use SimpleIT\Utils\FormatUtils;

/**
 * Test OwnerExerciseModelByExerciseModel Controller
 *
 * @author Baptiste Cable <baptiste.cable@liris.cnrs.fr>
 */
class OwnerExerciseModelByExerciseModelTest extends ApiFeatureTest
{
    const BAD_REQUEST_CODE = 400;

    const BAD_REQUEST = 'Bad Request';

    const NOT_FOUND = 'Not Found';

    const USER_TOKEN_1 = 'Bearer token-1';

    const USER_WRONG_TOKEN = 'Bearer toto';

    const ID_MODEL_1 = 1;

    const ID_MODEL_2 = 2;

    const ID_OWNER_MODEL_1 = 1;

    const ID_OWNER_MODEL_2 = 2;

    const ID_NOT_EXISTING = 250;

    /**
     * @var string
     */
    protected $path = '/exercise-models/{exerciseModelId}/owner-exercise-models/{ownerExerciseModelId}';

    /**
     * Get provider
     *
     * @return array
     */
    public static function getProvider()
    {
        return array(
            array(
                self::ID_MODEL_1,
                self::ID_OWNER_MODEL_1,
                ApiResponse::STATUS_CODE_OK,
                '^\{"id":1,"exercise_model":1,"owner":1000001,"public":true,"metadata":\{".*"\},"type":"multiple-choice","title":"Bases de l\'anatomie","draft":false,"complete":true\}$'
            ),
            array(
                self::ID_MODEL_2,
                self::ID_OWNER_MODEL_2,
                ApiResponse::STATUS_CODE_OK,
                '^\{"id":2,"exercise_model":2,"owner":1000001,"public":true,"metadata":\{".*"\},"type":"pair-items","title":"Appariement de test n 2","draft":false,"complete":true\}$'
            ),
            array(
                self::ID_MODEL_1,
                self::ID_OWNER_MODEL_2,
                ApiResponse::STATUS_CODE_CONTENT_NOT_FOUND,
                self::NOT_FOUND
            ),
            array(
                self::ID_MODEL_1,
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
     * @param int    $exerciseModelId
     * @param int    $ownerExerciseModelId
     * @param int    $expectedStatusCode Expected status code
     * @param string $expectedContent    Expected content
     *
     * @dataProvider getProvider
     */
    public function testView(
        $exerciseModelId,
        $ownerExerciseModelId,
        $expectedStatusCode,
        $expectedContent
    )
    {
        $response = $this->get(
            array(
                'exerciseModelId'      => $exerciseModelId,
                'ownerExerciseModelId' => $ownerExerciseModelId
            )
        );

        $this->assertEquals($expectedStatusCode, $response->getStatusCode());
        $this->assertRegExp('#' . $expectedContent . '#', $response->getContent());
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
                self::ID_MODEL_1,
                ApiResponse::STATUS_CODE_OK,
                '^\[\{"id":1,"exercise_model":1,"owner":1000001,"public":true,"metadata":\{".*"\},"type":"multiple-choice","title":"Bases de l\'anatomie","draft":false,"complete":true,"content":\{"wording":"Pour chacune des questions suivantes, indiquez toutes les reponses justes \(une ou plusieurs\).","exercise_model_type":"multiple-choice"\}\}\]$'
            ),
            array(
                self::ID_MODEL_2,
                ApiResponse::STATUS_CODE_OK,
                '^\[\{"id":2,"exercise_model":2,"owner":1000001,"public":true,"metadata":\{".*"\},"type":"pair-items","title":"Appariement de test n 2","draft":false,"complete":true,"content":\{"wording":"Associez la sortie ecran ou la valeur de retour au programme correspondant.","exercise_model_type":"pair-items"\}\}\]$'
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
     * @param int | string $inputIdentifier    Resource id
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
            array('exerciseModelId' => $inputIdentifier),
            $inputParameters,
            array(),
            $inputRange,
            FormatUtils::JSON
        );

        $this->assertEquals($expectedStatusCode, $response->getStatusCode());
        $this->assertRegExp('#' . $expectedContent . '#', $response->getContent());
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
                self::ID_MODEL_1,
                array('Authorization' => self::USER_TOKEN_1),
                '{"public":true,"metadata":{"doo":"mee","doo2":"mee2"}}',
                ApiResponse::STATUS_CODE_CREATED,
                '#^\{"id":[0-9]+,"exercise_model":1,"owner":1000001,"public":true,"metadata":\{"doo":"mee","doo2":"mee2"\},"type":"multiple-choice","title":"Bases de l\'anatomie","draft":false,"complete":true,"content":\{"wording":"Pour chacune des questions suivantes, indiquez toutes les reponses justes \(une ou plusieurs\).","documents":\[\],"question_blocks":\[\{"number_of_occurrences":3,"resources":\[\],"resource_constraint":\{"metadata_constraints":\[\{"key":"category","values":\["anatomie"\],"comparator":"in"\}\],"excluded":\[\]\},"max_number_of_propositions":0,"max_number_of_right_propositions":1\}\],"shuffle_questions_order":true,"exercise_model_type":"multiple-choice"\}\}$#'
            ),
            array(
                self::ID_MODEL_1,
                array(),
                '{"public":true,"metadata":{"doo":"mee","doo2":"mee2"}}',
                self::BAD_REQUEST_CODE,
                '#' . self::BAD_REQUEST . '#'
            ),
            array(
                self::ID_MODEL_1,
                array('Authorization' => self::USER_WRONG_TOKEN),
                '{"public":true,"metadata":{"doo":"mee","doo2":"mee2"}}',
                401,
                '#^Unauthorized$#'
            ),
            array(
                self::ID_MODEL_1,
                array('Authorization' => self::USER_TOKEN_1),
                '{"id":30,"public":true,"metadata":{"doo":"mee","doo2":"mee2"}}',
                self::BAD_REQUEST_CODE,
                '#' . self::BAD_REQUEST . '#'
            ),
            array(
                self::ID_MODEL_1,
                array('Authorization' => self::USER_TOKEN_1),
                '{"metadata":{"doo":"mee","doo2":"mee2"}}',
                self::BAD_REQUEST_CODE,
                '#' . self::BAD_REQUEST . '#'
            ),
            array(
                self::ID_MODEL_1,
                array('Authorization' => self::USER_TOKEN_1),
                '{"public":true}',
                self::BAD_REQUEST_CODE,
                '#' . self::BAD_REQUEST . '#'
            ),
            array(
                self::ID_NOT_EXISTING,
                array('Authorization' => self::USER_TOKEN_1),
                '{"public":true,"metadata":{"doo":"mee","doo2":"mee2"}}',
                ApiResponse::STATUS_CODE_CONTENT_NOT_FOUND,
                '#' . self::NOT_FOUND . '#'
            )
        );
    }

    /**
     * Test POST
     *
     * @param int    $inputIdentifier    Resource id
     * @param array  $headers
     * @param string $content
     * @param int    $expectedStatusCode Expected status code
     * @param string $expectedContent
     *
     * @dataProvider postProvider
     */
    public function testCreate(
        $inputIdentifier,
        array $headers,
        $content,
        $expectedStatusCode,
        $expectedContent
    )
    {
        $response = $this->post(
            array('exerciseModelId' => $inputIdentifier),
            $content,
            array(),
            $headers
        );

        $this->assertEquals($expectedStatusCode, $response->getStatusCode());
        $this->assertRegExp($expectedContent, $response->getContent());
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
                self::ID_MODEL_2,
                self::ID_OWNER_MODEL_2,
                '{"public":false}',
                ApiResponse::STATUS_CODE_OK,
                '#^\{"id":2,"exercise_model":2,"owner":1000001,"public":false,"metadata":\{"niveau":"L1","matiere":"Programmation"\},"type":"pair-items","title":"Appariement de test n 2","draft":false,"complete":true,"content":\{"wording":"Associez la sortie ecran ou la valeur de retour au programme correspondant.","documents":\[\],"pair_blocks":\[\{"number_of_occurrences":2,"resources":\[\{"id":109\},\{"id":110\},\{"id":111\},\{"id":112\},\{"id":113\},\{"id":114\},\{"id":115\},\{"id":116\}\],"pair_meta_key":"screen-output"\},\{"number_of_occurrences":2,"resources":\[\{"id":117\},\{"id":118\},\{"id":119\},\{"id":120\},\{"id":121\},\{"id":122\},\{"id":123\},\{"id":124\}\],"pair_meta_key":"return-value"\}\],"exercise_model_type":"pair-items"\}\}$#'
            ),
            array(
                self::ID_MODEL_2,
                self::ID_OWNER_MODEL_2,
                '{"id":5,"public":false}',
                self::BAD_REQUEST_CODE,
                '#' . self::BAD_REQUEST . '#'
            ),
            array(
                self::ID_MODEL_2,
                self::ID_OWNER_MODEL_2,
                '{"metadata":{"doo":"mee"}}',
                self::BAD_REQUEST_CODE,
                '#' . self::BAD_REQUEST . '#'
            ),
            array(
                self::ID_NOT_EXISTING,
                self::ID_OWNER_MODEL_2,
                '{"value":"222"}',
                ApiResponse::STATUS_CODE_CONTENT_NOT_FOUND,
                '#' . self::NOT_FOUND . '#'
            ),
            array(
                self::ID_MODEL_2,
                self::ID_NOT_EXISTING,
                '{"value":"222"}',
                ApiResponse::STATUS_CODE_CONTENT_NOT_FOUND,
                '#' . self::NOT_FOUND . '#'
            ),
        );
    }

    /**
     * Test PUT
     *
     * @param int    $emId              Resource id
     * @param string $ownerExerciseModelId
     * @param string $content
     * @param int    $expectedStatusCode Expected status code
     * @param string $expectedContent
     *
     * @dataProvider putProvider
     */
    public function testEdit(
        $emId,
        $ownerExerciseModelId,
        $content,
        $expectedStatusCode,
        $expectedContent
    )
    {
        $response = $this->put(
            array('exerciseModelId' => $emId, 'ownerExerciseModelId' => $ownerExerciseModelId),
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
