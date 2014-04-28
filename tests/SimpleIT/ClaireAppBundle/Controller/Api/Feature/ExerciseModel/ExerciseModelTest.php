<?php
namespace SimpleIT\ClaireExerciseBundle\Feature\ExerciseModel;

use OC\Tester\ApiFeatureTest;
use SimpleIT\ApiBundle\Model\ApiResponse;
use SimpleIT\Utils\FormatUtils;

/**
 * Test Exercise Models
 *
 * @author Baptiste Cable <baptiste.cable@liris.cnrs.fr>
 */
class ExerciseModelTest extends ApiFeatureTest
{
    const BAD_REQUEST_CODE = 400;

    const BAD_REQUEST = '#Bad Request#';

    const USER_TOKEN_1 = 'Bearer token-1';

    const NOT_FOUND = '#Not Found#';

    const ID_TEST_1 = 1;

    const ID_TEST_2 = 2;

    const ID_TEST_3 = 3;

    const ID_TEST_4 = 4;

    const ID_TEST_NOT_EXISTING = 250;

    const TEST_RESOURCE_1 = '#^\{"id":1,"type":"multiple-choice","title":"Bases de l\'anatomie","content":\{"wording":"Pour chacune des questions suivantes, indiquez toutes les reponses justes \(une ou plusieurs\).","documents":\[\],"question_blocks":\[\{"number_of_occurrences":3,"resources":\[\],"resource_constraint":\{"metadata_constraints":\[\{"key":"category","values":\["anatomie"\],"comparator":"in"\}\],"excluded":\[\]\},"max_number_of_propositions":0,"max_number_of_right_propositions":1\}\],"shuffle_questions_order":true,"exercise_model_type":"multiple-choice"\},"draft":false,"complete":true,"author":1000001,"required_exercise_resources":\[\]\}$#';

    const TEST_RESOURCE_2 = '#^\{"id":2,"type":"pair-items","title":"Appariement de test n 2","content":\{"wording":"Associez la sortie ecran ou la valeur de retour au programme correspondant.","documents":\[\],"pair_blocks":\[\{"number_of_occurrences":2,"resources":\[\{"id":109\},\{"id":110\},\{"id":111\},\{"id":112\},\{"id":113\},\{"id":114\},\{"id":115\},\{"id":116\}\],"pair_meta_key":"screen-output"\},\{"number_of_occurrences":2,"resources":\[\{"id":117\},\{"id":118\},\{"id":119\},\{"id":120\},\{"id":121\},\{"id":122\},\{"id":123\},\{"id":124\}\],"pair_meta_key":"return-value"\}\],"exercise_model_type":"pair-items"\},"draft":false,"complete":true,"author":1000001,"required_exercise_resources":\[[0-9]+,[0-9]+,[0-9]+,[0-9]+,[0-9]+,[0-9]+,[0-9]+,[0-9]+,[0-9]+,[0-9]+,[0-9]+,[0-9]+,[0-9]+,[0-9]+,[0-9]+,[0-9]+\]\}$#';

    const TEST_RESOURCE_3 = '#^\{"id":3,"type":"group-items","title":"Groupement de test n 1","content":\{"wording":"Classez les animaux par categorie de taille et indiquez la categorie.","documents":\[\],"object_blocks":\[\{"number_of_occurrences":6,"resources":\[\{"id":125\},\{"id":126\},\{"id":127\},\{"id":128\},\{"id":129\},\{"id":130\},\{"id":131\},\{"id":132\}\]\}\],"display_group_names":"ask","classif_constr":\{"other":"own","meta_keys":\["size"\],"groups":\[\]\},"exercise_model_type":"group-items"\},"draft":false,"complete":true,"author":1000001,"required_exercise_resources":\[[0-9]+,[0-9]+,[0-9]+,[0-9]+,[0-9]+,[0-9]+,[0-9]+,[0-9]+\]\}$#';

    const TEST_RESOURCE_4 = '#^\{"id":4,"type":"order-items","title":"Ordonnancement de test 2","content":\{"wording":"Classez du plus petit au plus grand.","documents":\[\],"sequence_block":\{"resources":\[\{"id":154\}\],"keep_all":false,"number_of_parts":5,"use_first":true,"use_last":true\},"object_blocks":\[\],"give_first":true,"give_last":true,"exercise_model_type":"order-items"\},"draft":false,"complete":true,"author":1000001,"required_exercise_resources":\[154\]\}$#';

    const TEST_RESOURCE_IN_LIST_1 = '{"id":1,"type":"multiple-choice","title":"Bases de l\'anatomie","draft":false,"complete":true,"author":1000001}';

    const TEST_RESOURCE_IN_LIST_2 = '{"id":2,"type":"pair-items","title":"Appariement de test n 2","draft":false,"complete":true,"author":1000001}';

    const TEST_RESOURCE_IN_LIST_3 = '{"id":3,"type":"group-items","title":"Groupement de test n 1","draft":false,"complete":true,"author":1000001}';

    const TEST_RESOURCE_IN_LIST_4 = '{"id":4,"type":"order-items","title":"Ordonnancement de test 2","draft":false,"complete":true,"author":1000001}';

    const TEST_RESOURCE_IN_LIST_5 = '{"id":5,"type":"pair-items","title":"Appariement de test 3","draft":false,"complete":true,"author":1000001}';

    const TEST_RESOURCE_IN_LIST_6 = '{"id":6,"type":"order-items","title":"Ordonnancement de test 1","draft":false,"complete":true,"author":1000001}';

    const TEST_RESOURCE_IN_LIST_7 = '{"id":7,"type":"group-items","title":"Groupement de test n 1","draft":false,"complete":true,"author":1000001}';

    const TEST_RESOURCE_PUT_1 = '#^\{"id":1,"type":"multiple-choice","title":"Nouveau titre","content":\{"wording":"Pour chacune des questions suivantes, indiquez toutes les reponses justes \(une ou plusieurs\).","documents":\[\],"question_blocks":\[\{"number_of_occurrences":3,"resources":\[\],"resource_constraint":\{"metadata_constraints":\[\{"key":"category","values":\["anatomie"\],"comparator":"in"\}\],"excluded":\[\]\},"max_number_of_propositions":0,"max_number_of_right_propositions":1\}\],"shuffle_questions_order":true,"exercise_model_type":"multiple-choice"\},"draft":false,"complete":true,"author":1000001,"required_exercise_resources":\[\]\}$#';

    const TEST_RESOURCE_PUT_2 = '#^\{"id":2,"type":"pair-items","title":"Appariement de test n 2","content":\{"wording":"Nouvelle consigne.","documents":\[\],"pair_blocks":\[\{"number_of_occurrences":2,"resources":\[\{"id":109\},\{"id":110\},\{"id":111\},\{"id":112\},\{"id":113\},\{"id":114\},\{"id":115\},\{"id":116\}\],"pair_meta_key":"screen-output"\},\{"number_of_occurrences":2,"resources":\[\{"id":117\},\{"id":118\},\{"id":119\},\{"id":120\},\{"id":121\},\{"id":122\},\{"id":123\},\{"id":124\}\],"pair_meta_key":"return-value"\}\],"exercise_model_type":"pair-items"\},"draft":false,"complete":true,"author":1000001,"required_exercise_resources":\[[0-9]+,[0-9]+,[0-9]+,[0-9]+,[0-9]+,[0-9]+,[0-9]+,[0-9]+,[0-9]+,[0-9]+,[0-9]+,[0-9]+,[0-9]+,[0-9]+,[0-9]+,[0-9]+\]\}$#';

    const TEST_RESOURCE_PUT_2b = '#^\{"id":2,"type":"pair-items","title":"Appariement de test n 2","content":\{"wording":"Nouvelle consigne.","documents":\[\],"pair_blocks":\[\{"number_of_occurrences":2,"resources":\[\],"pair_meta_key":"screen-output"\},\{"number_of_occurrences":2,"resources":\[\{"id":117\},\{"id":118\},\{"id":119\},\{"id":120\},\{"id":121\},\{"id":122\},\{"id":123\},\{"id":124\}\],"pair_meta_key":"return-value"\}\],"exercise_model_type":"pair-items"\},"draft":false,"complete":false,"author":1000001,"required_exercise_resources":\[[0-9]+,[0-9]+,[0-9]+,[0-9]+,[0-9]+,[0-9]+,[0-9]+,[0-9]+,[0-9]+,[0-9]+,[0-9]+,[0-9]+,[0-9]+,[0-9]+,[0-9]+,[0-9]+\]\}$#';

    const TEST_RESOURCE_PUT_3 = '#^\{"id":3,"type":"pair-items","title":"Appariement de test n 2","content":\{"wording":"Nouvelle consigne.","documents":\[\],"pair_blocks":\[\{"number_of_occurrences":2,"resources":\[\{"id":109\},\{"id":110\},\{"id":111\},\{"id":112\},\{"id":113\},\{"id":114\},\{"id":115\},\{"id":116\}\],"pair_meta_key":"screen-output"\},\{"number_of_occurrences":2,"resources":\[\{"id":117\},\{"id":118\},\{"id":119\},\{"id":120\},\{"id":121\},\{"id":122\},\{"id":123\},\{"id":124\}\],"pair_meta_key":"return-value"\}\],"exercise_model_type":"pair-items"\},"draft":false,"complete":true,"author":1000001,"required_exercise_resources":\[[0-9]+,[0-9]+,[0-9]+,[0-9]+,[0-9]+,[0-9]+,[0-9]+,[0-9]+,[0-9]+,[0-9]+,[0-9]+,[0-9]+,[0-9]+,[0-9]+,[0-9]+,[0-9]+\]\}$#';

    const TEST_RESOURCE_PUT_4 = '#^\{"id":4,"type":"order-items","title":"Ordonnancement de test 2","content":\{"wording":"Classez du plus petit au plus grand.","documents":\[\],"sequence_block":\{"resources":\[\{"id":154\}\],"keep_all":false,"number_of_parts":5,"use_first":true,"use_last":true\},"object_blocks":\[\],"give_first":true,"give_last":true,"exercise_model_type":"order-items"\},"draft":false,"complete":true,"author":1000001,"required_exercise_resources":\[\]\}$#';

    /**
     * @var string
     */
    protected $path = '/exercise-models/{exerciseModelId}';

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
                self::NOT_FOUND
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
        $response = $this->get(array('exerciseModelId' => $inputIdentifier));

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
        $expectedListNormal = '[' .
            self::TEST_RESOURCE_IN_LIST_1 . ',' .
            self::TEST_RESOURCE_IN_LIST_2 . ',' .
            self::TEST_RESOURCE_IN_LIST_3 . ',' .
            self::TEST_RESOURCE_IN_LIST_4 . ',' .
            self::TEST_RESOURCE_IN_LIST_5 . ',' .
            self::TEST_RESOURCE_IN_LIST_6 . ',' .
            self::TEST_RESOURCE_IN_LIST_7 . ']';

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
     * Post provider
     *
     * @return array
     */
    public static function postProvider()
    {
        return array(
            array(
                '{"id":1,"type":"multiple-choice","title":"Bases de l\'anatomie", "content":{"wording":"Pour chacune des questions suivantes, indiquez toutes les reponses justes (une ou plusieurs).","documents":[],"question_blocks":[{"number_of_occurrences":3,"resources":[],"resource_constraint":{"metadata_constraints":[{"key":"category","values":["anatomie"],"comparator":"in"}],"excluded":[]},"max_number_of_propositions":0,"max_number_of_right_propositions":1}],"shuffle_questions_order":true,"exercise_model_type":"multiple-choice"},"required_exercise_resources":[],"draft":false}',
                self::BAD_REQUEST_CODE,
                self::BAD_REQUEST,
                array("Authorization" => self::USER_TOKEN_1),
            ),
            array(
                '{"type":"multiple-choice","title":"Bases de l\'anatomie","content":{"wording":"Pour chacune des questions suivantes, indiquez toutes les reponses justes (une ou plusieurs).","documents":[],"question_blocks":[{"number_of_occurrences":3,"resources":[],"resource_constraint":{"metadata_constraints":[{"key":"category","values":["anatomie"],"comparator":"in"}],"excluded":[]},"max_number_of_propositions":0,"max_number_of_right_propositions":1}],"shuffle_questions_order":true,"exercise_model_type":"multiple-choice"},"required_exercise_resources":[],"draft":false}',
                ApiResponse::STATUS_CODE_CREATED,
                '#^\{"id":[0-9]+,"type":"multiple-choice","title":"Bases de l\'anatomie","content":\{"wording":"Pour chacune des questions suivantes, indiquez toutes les reponses justes \(une ou plusieurs\).","documents":\[\],"question_blocks":\[\{"number_of_occurrences":3,"resources":\[\],"resource_constraint":\{"metadata_constraints":\[\{"key":"category","values":\["anatomie"\],"comparator":"in"\}\],"excluded":\[\]\},"max_number_of_propositions":0,"max_number_of_right_propositions":1\}\],"shuffle_questions_order":true,"exercise_model_type":"multiple-choice"\},"draft":false,"complete":true,"author":1000001,"required_exercise_resources":\[\]\}$#',
                array("Authorization" => self::USER_TOKEN_1),
            ),
            array(
                '{"type":"pair-items","title":"Appariement de test n 2","content":{"wording":"Associez la sortie ecran ou la valeur de retour au programme correspondant.","documents":[],"pair_blocks":[{"number_of_occurrences":2,"resources":[{"id":109},{"id":110},{"id":111},{"id":112},{"id":113},{"id":114},{"id":115},{"id":116}],"pair_meta_key":"screen-output"},{"number_of_occurrences":2,"resources":[{"id":117},{"id":118},{"id":119},{"id":120},{"id":121},{"id":122},{"id":123},{"id":124}],"pair_meta_key":"return-value"}],"exercise_model_type":"pair-items"},"required_exercise_resources":[109,110,111,112,113,114,115,116,117,118,119,120,121,122,123,124],"draft":false}',
                ApiResponse::STATUS_CODE_CREATED,
                '#^\{"id":[0-9]+,"type":"pair-items","title":"Appariement de test n 2","content":\{"wording":"Associez la sortie ecran ou la valeur de retour au programme correspondant.","documents":\[\],"pair_blocks":\[\{"number_of_occurrences":2,"resources":\[\{"id":109\},\{"id":110\},\{"id":111\},\{"id":112\},\{"id":113\},\{"id":114\},\{"id":115\},\{"id":116\}\],"pair_meta_key":"screen-output"\},\{"number_of_occurrences":2,"resources":\[\{"id":117\},\{"id":118\},\{"id":119\},\{"id":120\},\{"id":121\},\{"id":122\},\{"id":123\},\{"id":124\}\],"pair_meta_key":"return-value"\}\],"exercise_model_type":"pair-items"\},"draft":false,"complete":true,"author":1000001,"required_exercise_resources":\[109,110,111,112,113,114,115,116,117,118,119,120,121,122,123,124\]\}$#',
                array("Authorization" => self::USER_TOKEN_1),
            ),
            array(
                '{"type":"group-items","title":"Groupement de test n 1","content":{"wording":"Classez les animaux par categorie de taille et indiquez la categorie.","documents":[],"object_blocks":[{"number_of_occurrences":6,"resources":[{"id":125},{"id":126},{"id":127},{"id":128},{"id":129},{"id":130},{"id":131},{"id":132}]}],"display_group_names":"ask","classif_constr":{"other":"own","meta_keys":["size"],"groups":[]},"exercise_model_type":"group-items"},"required_exercise_resources":[125,126,127,128,129,130,131,132],"draft":false}',
                ApiResponse::STATUS_CODE_CREATED,
                '#^\{"id":[0-9]+,"type":"group-items","title":"Groupement de test n 1","content":\{"wording":"Classez les animaux par categorie de taille et indiquez la categorie.","documents":\[\],"object_blocks":\[\{"number_of_occurrences":6,"resources":\[\{"id":125\},\{"id":126\},\{"id":127\},\{"id":128\},\{"id":129\},\{"id":130\},\{"id":131\},\{"id":132\}\]\}\],"display_group_names":"ask","classif_constr":\{"other":"own","meta_keys":\["size"\],"groups":\[\]\},"exercise_model_type":"group-items"\},"draft":false,"complete":true,"author":1000001,"required_exercise_resources":\[125,126,127,128,129,130,131,132\]\}$#',
                array("Authorization" => self::USER_TOKEN_1),
            ),
            array(
                '{"type":"order-items","title":"Ordonnancement de test 2","content":{"wording":"Classez du plus petit au plus grand.","documents":[],"sequence_block":{"resources":[{"id":154}],"keep_all":false,"number_of_parts":5,"use_first":true,"use_last":true},"object_blocks":[],"give_first":true,"give_last":true,"exercise_model_type":"order-items"},"required_exercise_resources":[154],"draft":false}',
                ApiResponse::STATUS_CODE_CREATED,
                '#^\{"id":[0-9]+,"type":"order-items","title":"Ordonnancement de test 2","content":\{"wording":"Classez du plus petit au plus grand.","documents":\[\],"sequence_block":\{"resources":\[\{"id":154\}\],"keep_all":false,"number_of_parts":5,"use_first":true,"use_last":true\},"object_blocks":\[\],"give_first":true,"give_last":true,"exercise_model_type":"order-items"\},"draft":false,"complete":true,"author":1000001,"required_exercise_resources":\[154\]\}$#',
                array("Authorization" => self::USER_TOKEN_1),
            ),
            // complete --> false
            array(
                '{"type":"pair-items","title":"Appariement de test n 2","content":{"wording":"Associez la sortie ecran ou la valeur de retour au programme correspondant.","documents":[],"pair_blocks":[],"exercise_model_type":"pair-items"},"required_exercise_resources":[],"draft":false}',
                ApiResponse::STATUS_CODE_CREATED,
                '#^\{"id":[0-9]+,"type":"pair-items","title":"Appariement de test n 2","content":\{"wording":"Associez la sortie ecran ou la valeur de retour au programme correspondant.","documents":\[\],"pair_blocks":\[\],"exercise_model_type":"pair-items"\},"draft":false,"complete":false,"author":1000001,"required_exercise_resources":\[\]\}$#',
                array("Authorization" => self::USER_TOKEN_1),
            ),
            array(
                '{"type":"order-items","title":"Ordonnancement de test 2","content":{"wording":"Classez du plus petit au plus grand.","documents":[],"sequence_block":{"resources":[{"id":154}],"keep_all":false,"number_of_parts":5,"use_first":true,"use_last":true},"object_blocks":[],"give_first":true,"give_last":true,"exercise_model_type":"order-items"},"required_exercise_resources":[2500],"draft":false}',
                ApiResponse::STATUS_CODE_CONTENT_NOT_FOUND,
                self::NOT_FOUND,
                array("Authorization" => self::USER_TOKEN_1),
            ),
            array(
                '{"type":"group-items","title":"Groupement de test n 1","content":{"wording":"Classez les animaux par categorie de taille et indiquez la categorie.","documents":[],"object_blocks":[{"number_of_occurrences":6,"resources":[{"id":125},{"id":126},{"id":127},{"id":128},{"id":129},{"id":130},{"id":131},{"id":132}]}],"display_group_names":"ask","classif_constr":{"other":"own","meta_keys":["size"],"groups":[]},"exercise_model_type":"group-items"},"required_exercise_resources":[125,126,127,128,129,130,131,132]}',
                self::BAD_REQUEST_CODE,
                self::BAD_REQUEST,
                array("Authorization" => self::USER_TOKEN_1),
            ),
            array(
                '{"type":"multiple-choice","title":"Bases de l\'anatomie","content":{"wording":"Pour chacune des questions suivantes, indiquez toutes les reponses justes (une ou plusieurs).","documents":[],"question_blocks":[{"number_of_occurrences":3,"resources":[],"resource_constraint":{"metadata_constraints":[{"key":"category","values":["anatomie"],"comparator":"in"}],"excluded":[]},"max_number_of_propositions":0,"max_number_of_right_propositions":1}],"shuffle_questions_order":true,"exercise_model_type":"multiple-choice"},"required_exercise_resources":[],"draft":false,"complete":true}',
                self::BAD_REQUEST_CODE,
                self::BAD_REQUEST,
                array("Authorization" => self::USER_TOKEN_1),
            ),
            array(
                '{"type":"order-items","title":"Ordonnancement de test 2","content":{"wording":"Classez du plus petit au plus grand.","documents":[],"sequence_block":{"resources":[{"id":154}],"keep_all":false,"number_of_parts":5,"use_first":true,"use_last":true},"object_blocks":[],"give_first":true,"give_last":true,"exercise_model_type":"order-items"},"required_exercise_resources":[154,"draft":false,"complete":true}',
                self::BAD_REQUEST_CODE,
                self::BAD_REQUEST,
                array("Authorization" => self::USER_TOKEN_1),
            )
        );
    }

    /**
     * Test POST
     *
     * @param string $inputContent       Answer content
     * @param int    $expectedStatusCode Expected status code
     * @param string $expectedContent    Expected response content
     * @param array  $headers
     *
     * @dataProvider postProvider
     */
    public function testCreate(
        $inputContent,
        $expectedStatusCode,
        $expectedContent,
        array $headers = array()
    )
    {
        $response = $this->post(array(), $inputContent, array(), $headers);

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
                self::ID_TEST_1,
                '{"title":"Nouveau titre"}',
                ApiResponse::STATUS_CODE_OK,
                self::TEST_RESOURCE_PUT_1,
            ),
            array(
                self::ID_TEST_2,
                '{"content":{"wording":"Nouvelle consigne.","documents":[],"pair_blocks":[{"number_of_occurrences":2,"resources":[{"id":109},{"id":110},{"id":111},{"id":112},{"id":113},{"id":114},{"id":115},{"id":116}],"pair_meta_key":"screen-output"},{"number_of_occurrences":2,"resources":[{"id":117},{"id":118},{"id":119},{"id":120},{"id":121},{"id":122},{"id":123},{"id":124}],"pair_meta_key":"return-value"}],"exercise_model_type":"pair-items"}}',
                ApiResponse::STATUS_CODE_OK,
                self::TEST_RESOURCE_PUT_2,
            ),
            // complete --> false
            array(
                self::ID_TEST_2,
                '{"content":{"wording":"Nouvelle consigne.","documents":[],"pair_blocks":[{"number_of_occurrences":2,"resources":[],"pair_meta_key":"screen-output"},{"number_of_occurrences":2,"resources":[{"id":117},{"id":118},{"id":119},{"id":120},{"id":121},{"id":122},{"id":123},{"id":124}],"pair_meta_key":"return-value"}],"exercise_model_type":"pair-items"}}',
                ApiResponse::STATUS_CODE_OK,
                self::TEST_RESOURCE_PUT_2b,
            ),
            array(
                self::ID_TEST_3,
                '{"type":"pair-items","title":"Appariement de test n 2","content":{"wording":"Nouvelle consigne.","documents":[],"pair_blocks":[{"number_of_occurrences":2,"resources":[{"id":109},{"id":110},{"id":111},{"id":112},{"id":113},{"id":114},{"id":115},{"id":116}],"pair_meta_key":"screen-output"},{"number_of_occurrences":2,"resources":[{"id":117},{"id":118},{"id":119},{"id":120},{"id":121},{"id":122},{"id":123},{"id":124}],"pair_meta_key":"return-value"}],"exercise_model_type":"pair-items"},"required_exercise_resources":[109,110,111,112,113,114,115,116,117,118,119,120,121,122,123,124]}',
                ApiResponse::STATUS_CODE_OK,
                self::TEST_RESOURCE_PUT_3,
            ),
            // invalid type
            array(
                self::ID_TEST_3,
                '{"content":{"wording":"Nouvelle consigne.","documents":[],"pair_blocks":[{"number_of_occurrences":2,"resources":[{"id":109},{"id":110},{"id":111},{"id":112},{"id":113},{"id":114},{"id":115},{"id":116}],"pair_meta_key":"screen-output"},{"number_of_occurrences":2,"resources":[{"id":117},{"id":118},{"id":119},{"id":120},{"id":121},{"id":122},{"id":123},{"id":124}],"pair_meta_key":"return-value"}],"exercise_model_type":"pair-items"}}',
                self::BAD_REQUEST_CODE,
                self::BAD_REQUEST,
            ),
            array(
                self::ID_TEST_4,
                '{"required_exercise_resources":[]}',
                ApiResponse::STATUS_CODE_OK,
                self::TEST_RESOURCE_PUT_4,
            ),
            array(
                self::ID_TEST_1,
                '{"title":ouveau titre"}',
                self::BAD_REQUEST_CODE,
                self::BAD_REQUEST,
            ),
            array(
                self::ID_TEST_1,
                '{"id":"8"}',
                self::BAD_REQUEST_CODE,
                self::BAD_REQUEST,
            ),
            array(
                self::ID_TEST_1,
                '{"draft":false,"complete":true,"author":"8"}',
                self::BAD_REQUEST_CODE,
                self::BAD_REQUEST,
            ),
            array(
                self::ID_TEST_NOT_EXISTING,
                '{"title":"Nouveau titre"}',
                ApiResponse::STATUS_CODE_CONTENT_NOT_FOUND,
                self::NOT_FOUND,
            ),
        );
    }

    /**
     * Test PUT
     *
     * @param int    $exerciseModelId
     * @param string $inputContent          Answer content
     * @param int    $expectedStatusCode    Expected status code
     * @param string $expectedContent       Expected response content
     *
     * @dataProvider putProvider
     */
    public function testEdit(
        $exerciseModelId,
        $inputContent,
        $expectedStatusCode,
        $expectedContent
    )
    {
        $response = $this->put(
            array("exerciseModelId" => $exerciseModelId),
            $inputContent,
            array()
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
            array(
                self::ID_TEST_NOT_EXISTING,
                ApiResponse::STATUS_CODE_CONTENT_NOT_FOUND,
            ),
            array(
                self::ID_TEST_1,
                ApiResponse::STATUS_CODE_NO_CONTENT,
            ),
            array(
                self::ID_TEST_2,
                ApiResponse::STATUS_CODE_NO_CONTENT,
            ),
            array(
                self::ID_TEST_3,
                ApiResponse::STATUS_CODE_NO_CONTENT,
            ),
            array(
                self::ID_TEST_4,
                ApiResponse::STATUS_CODE_NO_CONTENT,
            ),
        );
    }

    /**
     * Test DELETE
     *
     * @param int $exerciseModelId
     * @param int $expectedStatusCode
     *
     * @dataProvider deleteProvider
     */
    public function testDelete(
        $exerciseModelId,
        $expectedStatusCode
    )
    {
        $response = $this->delete(array("exerciseModelId" => $exerciseModelId));

        $this->assertEquals($expectedStatusCode, $response->getStatusCode());
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
