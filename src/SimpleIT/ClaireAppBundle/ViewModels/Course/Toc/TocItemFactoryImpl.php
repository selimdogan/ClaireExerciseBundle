<?php

namespace SimpleIT\ClaireAppBundle\ViewModels\Course\Toc;

/**
 * Class TocItemFactoryImpl
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class TocItemFactoryImpl implements TocItemFactory
{
    /**
     * @return TocItem
     * @throws \InvalidArgumentException
     */
    public function make($tocItemSubtype)
    {
        switch ($tocItemSubtype) {
            case self::COURSE:
                $item = new CourseItem();
                break;
            case self::PART:
                $item = new PartItem();
                break;
            case self::PART_CREATION:
                $item = new PartCreationItem();
                break;
            case self::CHAPTER:
                $item = new ChapterItem();
                break;
            case self::CHAPTER_CREATION:
                $item = new ChapterCreationItem();
                break;
            default:
                throw new UnsupportedSubtypeException();
        }

        return $item;
    }
}
