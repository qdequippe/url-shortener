<?php

namespace App\Repository;

use App\Entity\Link;
use App\Entity\Visit;
use DeviceDetector\Parser\Client\Browser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr;

/**
 * @method Visit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Visit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Visit[]    findAll()
 * @method Visit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VisitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Visit::class);
    }

    public function findVisitInLastHour(Link $link)
    {
        return $this->createQueryBuilder('v')
            ->where('v.link = :link')
            ->andWhere('HOUR(v.createdAt) = HOUR(NOW())')
            ->setParameter('link', $link)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function countPerPeriod(Link $link)
    {
        return $this->createQueryBuilder('v')
            ->select('COUNT(v.id), MONTH(v.createdAt) as gBMonth')
            ->where('v.link = :link')
            ->groupBy('gBMonth')
            ->setParameter('link', $link)
            ->getQuery()
            ->getResult();
    }

    public function countPerReferrer(Link $link)
    {
        return $this->createQueryBuilder('v')
            ->where('v.link = :link')
            ->setParameter('link', $link)
            ->getQuery()
            ->getResult();
    }

    public function countPerBrowser(Link $link)
    {
        $qb = $this->createQueryBuilder('v')
            ->select('SUM(v.brOther) AS Other');

        foreach (Visit::BROWSERS_LIST as $browser) {
            switch ($browser) {
                case 'Internet Explorer':
                    $qb->addSelect('SUM(v.brIe) AS IE');
                    break;
                case 'Chrome':
                    $qb->addSelect('SUM(v.brChrome) AS CH');
                    break;
                case 'Opera':
                    $qb->addSelect('SUM(v.brOpera) AS OP');
                    break;
                case 'Safari':
                    $qb->addSelect('SUM(v.brSafari) AS SF');
                    break;
                case 'Firefox':
                    $qb->addSelect('SUM(v.brFirefox) AS FF');
                    break;
                case 'Microsoft Edge':
                    $qb->addSelect('SUM(v.brEdge) AS PS');
                    break;
            }
        }

        return $qb
            ->where('v.link = :link')
            ->setParameter('link', $link)
            ->getQuery()
            ->getSingleResult();
    }

    public function countPerOs(Link $link)
    {
        $qb = $this->createQueryBuilder('v')
            ->select('SUM(v.osOther)');

        // Todo find a way to improve this
        foreach (Visit::OS_LIST as $os) {
            switch ($os) {
                case 'Windows':
                    $qb->addSelect('SUM(v.osWindows)');
                    break;
                case 'Mac':
                    $qb->addSelect('SUM(v.osMacos)');
                    break;
                case 'Linux':
                    $qb->addSelect('SUM(v.osLinux)');
                    break;
                case 'Android':
                    $qb->addSelect('SUM(v.osAndroid)');
                    break;
                case 'iOS':
                    $qb->addSelect('SUM(v.osIos)');
                    break;
            }
        }

        return $qb
            ->where('v.link = :link')
            ->setParameter('link', $link)
            ->getQuery()
            ->getSingleResult();
    }
}
