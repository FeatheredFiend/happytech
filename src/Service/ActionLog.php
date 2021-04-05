<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class ActionLog
{
    private $actionLog;
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, $actionLog)
    {
        $this->actionLog = $actionLog;
        $this->entityManager = $entityManager;

    }

    public function addAction($action, $user, $tablename, $rownumber)
    {
$em = $this->entityManager;
        $RAW_QUERY = "INSERT INTO action_log(timestamp, action, user_id, tablename_id, rownumber) VALUES (now(),'$action','$user','$tablename','$rownumber')";         
        $statement = $em->getConnection()->prepare($RAW_QUERY);
        $statement->execute();
    }

    public function getActionLog()
    {
        return $this->actionLog;
    }

}