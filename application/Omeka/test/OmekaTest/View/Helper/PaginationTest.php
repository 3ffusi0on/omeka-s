<?php
namespace OmekaTest\View\Helper;

use Omeka\View\Helper\Pagination;
use Omeka\Test\TestCase;

class PaginationTest extends TestCase
{
    public function testToString()
    {
        $totalCount   = 1000;
        $currentPage  = 50;
        $perPage      = 10;
        $pageCount    = 100;
        $previousPage = 49;
        $nextPage     = 51;
        $name         = 'name';
        $query        = array('foo' => 'bar');

        // Request
        $request = $this->getMock(
            'Zend\Http\PhpEnvironment\Request',
            array('getQuery', 'toArray')
        );
        $request->expects($this->any())
            ->method('getQuery')
            ->will($this->returnSelf());
        $request->expects($this->any())
            ->method('toArray')
            ->will($this->returnValue($query));

        // Omeka\Pagination
        $pagination = $this->getMock('Omeka\Service\Pagination');
        $pagination->expects($this->any())
            ->method('setTotalCount')
            ->with($this->equalTo($totalCount));
        $pagination->expects($this->any())
            ->method('setCurrentPage')
            ->with($this->equalTo($currentPage));
        $pagination->expects($this->any())
            ->method('setPerPage')
            ->with($this->equalTo($perPage));
        $pagination->expects($this->any())
            ->method('getPageCount')
            ->will($this->returnValue($pageCount));
        $pagination->expects($this->any())
            ->method('getTotalCount')
            ->will($this->returnValue($totalCount));
        $pagination->expects($this->any())
            ->method('getPerPage')
            ->will($this->returnValue($perPage));
        $pagination->expects($this->any())
            ->method('getCurrentPage')
            ->will($this->returnValue($currentPage));
        $pagination->expects($this->any())
            ->method('getPreviousPage')
            ->will($this->returnValue($previousPage));
        $pagination->expects($this->any())
            ->method('getNextPage')
            ->will($this->returnValue($nextPage));

        // ServiceManager
        $serviceManager = $this->getServiceManager(array(
            'Request' => $request,
            'Omeka\Pagination' => $pagination,
        ));

        // View
        $view = $this->getMock(
            'Zend\View\Renderer\PhpRenderer',
            array('partial', 'url')
        );
        $view->expects($this->any())
            ->method('url');
        $view->expects($this->once())
            ->method('partial')
            ->with(
                $this->equalTo($name),
                $this->equalTo(array(
                    'totalCount'      => $totalCount,
                    'perPage'         => $perPage,
                    'currentPage'     => $currentPage,
                    'previousPage'    => $previousPage,
                    'nextPage'        => $nextPage,
                    'pageCount'       => $pageCount,
                    'query'           => $query,
                    'firstPageUrl'    => null,
                    'previousPageUrl' => null,
                    'nextPageUrl'     => null,
                    'lastPageUrl'     => null,
                ))
            );

        $pagination = new Pagination($serviceManager);
        $pagination->setView($view);
        $pagination->__invoke($totalCount, $currentPage, $perPage, $name);
        $pagination->__toString();
    }
}
