<?php

namespace Songbird\NestablePageBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * PageRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PageRepository extends EntityRepository
{

    public function findPageMetaByLocale($slug, $locale) {

        $query = $this->createQueryBuilder('p')
            ->select('p', 'pm')
            ->Join('p.pageMetas','pm')
            ->where('p.isPublished = :isPublished')
            ->andWhere('pm.locale = :locale')
            ->andWhere('p.slug = :slug')
            ->setParameter('isPublished', '1')
            ->setParameter('locale', $locale)
            ->setParameter('slug', $slug)
            ->getQuery();

        return $query->getOneOrNullResult();

    }

    public function findParent() {

        $query = $this->createQueryBuilder('p')
            ->select('p')
            ->where('p.isPublished = :isPublished')
            ->andWhere('p.parent is null')
            ->setParameter('isPublished', '1')
            ->orderBy('p.sequence', 'asc')
            ->getQuery();

        return $query->getResult();

    }
}
