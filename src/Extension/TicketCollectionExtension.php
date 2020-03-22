<?php

namespace App\Extension;

use App\Entity\Ticket;
use App\Entity\Types\TicketStatus;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\RequestStack;

class TicketCollectionExtension extends BaseExtension
{
    /**
     * @var RequestStack $requestStack
     */
    protected $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param string $resourceClass
     * @param string $rootAlias
     * @param array $context
     * @throws \Exception
     */
    protected function addWhereWithRootAlias(QueryBuilder $queryBuilder, string $resourceClass, string $rootAlias, array $context = [])
    {
        if (Ticket::class === $resourceClass) {
            $request   = $this->requestStack->getCurrentRequest();
            $latitude  = $request->headers->get('latitude');
            $longitude = $request->headers->get('longitude');

            if ($latitude) {
                $queryBuilder
                    ->andWhere(sprintf('%s.status >= :open', $rootAlias))
                    ->addSelect(sprintf(
                        '( 6371 * acos( cos( radians(:latitude) )
                                * cos( radians( %1$s.address.latitude ) )
                                * cos( radians( %1$s.address.longitude )
                                - radians(:longitude) )
                                + sin( radians(:latitude) )
                                * sin( radians( %1$s.address.latitude ) ) ) ) AS distance',
                        $rootAlias
                    ))
                    ->orderBy('distance', Criteria::ASC)
                    ->setParameter('open', TicketStatus::OPEN)
                    ->setParameter('longitude', $longitude)
                    ->setParameter('latitude', $latitude)
                ;
                // TODO: Remove distance
            }
        }
    }
}