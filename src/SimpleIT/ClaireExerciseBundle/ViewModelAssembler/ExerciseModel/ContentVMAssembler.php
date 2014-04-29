<?php
namespace SimpleIT\ClaireExerciseBundle\ViewModelAssembler\ExerciseModel;

use SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseModel\Common\CommonModel;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseModel\GroupItems\ClassificationConstraints;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseModel\GroupItems\Group;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseModel\GroupItems\Model as GroupItems;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseModel\GroupItems\ObjectBlock;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseModel\MultipleChoice\Model as MultipleChoice;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseModel\MultipleChoice\QuestionBlock;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseModel\OrderItems\Model as OrderItems;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseModel\OrderItems\SequenceBlock;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseModel\PairItems\Model as PairItems;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseModel\PairItems\PairBlock;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseModelResource;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ModelObject\MetadataConstraint;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ModelObject\ModelDocument;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ModelObject\ObjectConstraints;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ModelObject\ObjectId;

/**
 * Class ExerciseModelVMAssembler
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class ContentVMAssembler
{
    /**
     * Fill an exercise model to avoid any empty array
     *
     * @param ExerciseModelResource $exerciseModel
     *
     * @return ExerciseModelResource
     */
    public static function write(ExerciseModelResource $exerciseModel)
    {
        $content = $exerciseModel->getContent();

        $documents = $content->getDocuments();
        if (empty($documents)) {
            $content->setDocuments(array(new ModelDocument()));
        }

        switch ($exerciseModel->getType()) {
            case CommonModel::GROUP_ITEMS:
                /** @var GroupItems $content */
                self::writeGroupItemsContent($content);
                break;
            case CommonModel::MULTIPLE_CHOICE:
                /** @var MultipleChoice $content */
                self::writeMultipleChoiceContent($content);
                break;
            case CommonModel::ORDER_ITEMS:
                /** @var OrderItems $content */
                self::writeOrderItemsContent($content);
                break;
            case CommonModel::PAIR_ITEMS:
                /** @var PairItems $content */
                self::writePairItemsContent($content);
                break;
        }
        $exerciseModel->setContent($content);

        return $exerciseModel;
    }

    /**
     * Modify an order items model to fill all empty arrays with one empty object
     *
     * @param OrderItems $content
     */
    private static function writeOrderItemsContent(OrderItems &$content)
    {
        $objectBlocks = $content->getObjectBlocks();
        if (empty($objectBlocks)) {
            $objectBlocks = array(
                new
                \SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseModel\OrderItems\ObjectBlock(0, '')
            );
        }
        /** @var \SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseModel\OrderItems\ObjectBlock $block */
        foreach ($objectBlocks as &$block) {
            $block->setResources(self::fillResourceList($block->getResources()));
            $block->setResourceConstraint(
                self::fillObjectConstraint($block->getResourceConstraint())
            );
        }
        $content->setObjectBlocks($objectBlocks);

        $sequenceBlock = $content->getSequenceBlock();
        if (is_null($sequenceBlock)) {
            $sequenceBlock = new SequenceBlock();
        }
        $sequenceBlock->setResources(self::fillResourceList($sequenceBlock->getResources()));
        $sequenceBlock->setResourceConstraint(
            self::fillObjectConstraint($sequenceBlock->getResourceConstraint())
        );
        $content->setSequenceBlock($sequenceBlock);
    }

    /**
     * Modify a pair items model to fill all empty arrays with one empty object
     *
     * @param PairItems $content
     */
    private static function writePairItemsContent(PairItems &$content)
    {
        $pairBlocks = $content->getPairBlocks();
        if (empty($pairBlocks)) {
            $pairBlocks = array(new PairBlock(0));
        }
        /** @var PairBlock $block */
        foreach ($pairBlocks as &$block) {
            $block->setResources(self::fillResourceList($block->getResources()));
            $block->setResourceConstraint(
                self::fillObjectConstraint($block->getResourceConstraint())
            );
        }
        $content->setPairBlocks($pairBlocks);
    }

    /**
     * Modify a multiple choice model to fill all empty arrays with one empty object
     *
     * @param MultipleChoice $content
     */
    private static function writeMultipleChoiceContent(MultipleChoice &$content)
    {
        $questionBlocks = $content->getQuestionBlocks();
        if (empty($questionBlocks)) {
            $questionBlocks = array(new QuestionBlock(0));
        }
        /** @var QuestionBlock $block */
        foreach ($questionBlocks as &$block) {
            $block->setResources(self::fillResourceList($block->getResources()));
            $block->setResourceConstraint(
                self::fillObjectConstraint($block->getResourceConstraint())
            );
        }
        $content->setQuestionBlocks($questionBlocks);
    }

    /**
     * Modify a group items Model to fill all empty arrays with one empty object
     *
     * @param GroupItems $content
     */
    private static function writeGroupItemsContent(GroupItems &$content)
    {
        $classifConstr = $content->getClassifConstr();
        if ($classifConstr === null) {
            $classifConstr = new ClassificationConstraints();
        }
        $content->setClassifConstr(self::fillClassificationConstraint($classifConstr));

        $objectBlocks = $content->getObjectBlocks();
        if (empty($objectBlocks)) {
            $objectBlocks = array(new ObjectBlock(0));
        }
        /** @var ObjectBlock $block */
        foreach ($objectBlocks as &$block) {
            $block->setClassifConstr(
                self::fillClassificationConstraint($block->getClassifConstr())
            );
            $block->setResources(self::fillResourceList($block->getResources()));
            $block->setResourceConstraint(
                self::fillObjectConstraint($block->getResourceConstraint())
            );
        }
        $content->setObjectBlocks($objectBlocks);
    }

    /**
     * Create a filled classification constraint
     *
     * @param ClassificationConstraints $classificationConstraint
     *
     * @return ClassificationConstraints
     */
    private static function fillClassificationConstraint($classificationConstraint)
    {
        if ($classificationConstraint === null) {
            $classificationConstraint = new ClassificationConstraints();
        }

        $groups = $classificationConstraint->getGroups();
        if (empty($groups)) {
            $groups = array(new Group());
        }
        foreach ($groups as &$group) {
            $group = self::fillGroup($group);
        }
        $classificationConstraint->setGroups($groups);

        $metaKeys = $classificationConstraint->getMetaKeys();
        if (empty($metaKeys)) {
            $classificationConstraint->setMetaKeys(array(''));
        }

        return $classificationConstraint;
    }

    /**
     * Fill a resource list
     *
     * @param array $resources Array of ObjectId
     *
     * @return array Array of ObjectId
     */
    private static function fillResourceList($resources)
    {
        if (empty($resources)) {
            $resources = array(new ObjectId());
        }

        return $resources;
    }

    /**
     * Fill a group
     *
     * @param Group $group
     *
     * @return Group
     */
    private static function fillGroup(Group $group)
    {
        $group->setMDConstraints(self::fillMetadataConstraints($group->getMDConstraints()));

        return $group;
    }

    /**
     * Create a new empty MetadataConstraint
     *
     * @param array $mdcs
     *
     * @return array
     */
    private static function fillMetadataConstraints($mdcs)
    {
        if (empty($mdcs)) {
            $mdcs = array(new MetadataConstraint());
        }

        /** @var MetadataConstraint $mdc */
        foreach ($mdcs as &$mdc) {
            $values = $mdc->getValues();
            if (empty($values)) {
                $mdc->setValues(array(''));
            }
        }

        return $mdcs;
    }

    /**
     * Fill an ObjectConstraints
     *
     * @param ObjectConstraints $oc
     *
     * @return ObjectConstraints
     */
    private static function fillObjectConstraint($oc)
    {
        if ($oc === null) {
            $oc = new ObjectConstraints();
        }

        $oc->setExcluded(self::fillResourceList($oc->getExcluded()));
        $oc->setMetadataConstraints(self::fillMetadataConstraints($oc->getMetadataConstraints()));
        return $oc;
    }
}
