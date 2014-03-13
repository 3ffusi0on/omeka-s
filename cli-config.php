<?php
require 'bootstrap.php';

$application = Zend\Mvc\Application::init(require 'config/application.config.php');

return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet(
    $application->getServiceManager()->get('Omeka\EntityManager')
);
