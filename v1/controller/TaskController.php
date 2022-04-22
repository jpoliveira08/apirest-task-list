<?php

namespace App\v1\controller;

require_once('Database.php');
require_once('../model/Task.php');
require_once('../model/Response.php');

use PDO;
use PDOException;
use App\v1\model\{Response, Task, TaskException};
use App\v1\controller\Database;

try {
    $writeDB = Database::connectWriteDb();
    $readDB = Database::connectReadDb();
} catch (PDOException $ex) {
    error_log('Connection error - ' . $ex, 0);
    $response = new Response();
    $response->setHttpStatusCode(500);
    $response->setSuccess(false);
    $response->addMessages('Database connection error');
    $response->send();
    exit();
}

if (array_key_exists('taskid', $_GET)) {
    $taskid = $_GET['taskid'];

    if (empty($taskid) || !is_numeric($taskid)) {
        $response = new Response();
        $response->setHttpStatusCode(400);
        $response->setSuccess(false);
        $response->addMessages('Task ID cannot be blank or must be numeric');
        $response->send();
    }

    switch($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            try {
                error_log($readDB->query("SELECT * FROM tbltasks"));
                exit();
                $query = $readDB->prepare("
                SELECT id, title, description, DATE_FORMAT(deadline, '%d/%m/%Y %H:%i') as deadline, completed 
                FROM tbltasks 
                WHERE id = :tasksid
                ");
                $query->bindParam(':tasksid', $taskid, PDO::PARAM_INT);
                $query->execute();

                $rowCount = $query->rowCount();

                if (!$rowCount) {
                    $response = new Response();
                    $response->setHttpStatusCode(404);
                    $response->setSuccess(false);
                    $response->addMessages('Task nout found');
                    $response->send();
                    exit();
                }

                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                    $task = new Task($row['id'], $row['title'], $row['description'], $row['deadline'], $row['completed']);
                    $taskArray[] = $task->returnTaskAsArray();
                }

                $returnData = array();
                $returnData['rows_returned'] = $rowCount;
                $returnData['tasks'] = $taskArray;

                $response = new Response();
                $response->setHttpStatusCode(200);
                $response->setSuccess(true);
                $response->setIsCacheEnabled(true);
                $response->setData($returnData);
                $response->send();
                exit();
            } catch (TaskException $ex) {
                $response = new Response();
                $response->setHttpStatusCode(500);
                $response->setSuccess(false);
                $response->addMessages($ex->getMessage());
                $response->send();
                exit();
            } catch (PDOException $ex) {
                error_log('Database query error - ' . $ex, 0);
                $response = new Response();
                $response->setHttpStatusCode(500);
                $response->setSuccess(false);
                $response->addMessages('Failed to get Task');
                $response->send();
                exit();
            }
            break;
        case 'DELETE':

            break;
        case 'PATCH':
        
            break;
        default:
            $response = new Response();
            $response->setHttpStatusCode(405);
            $response->setSuccess(false);
            $response->addMessages('Request method not allowed');
            $response->send();
            exit();
    }
}