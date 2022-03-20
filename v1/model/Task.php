<?php

use App\v1\model\TaskException;

class Task
{
    private $id;
    private $title;
    private ?string $description;
    private $deadline;
    private $completed;

    public function __construct($id, $title, $description, $deadline, $completed)
    {
        $this->setId($id);
        $this->setTitle($title);
        $this->setDescription($description);
        $this->setDeadline($deadline);
        $this->setCompleted($completed);

    }

    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getDeadLine()
    {
        return $this->deadline;
    }

    public function getCompleted()
    {
        return $this->completed;
    }

    public function setId($id)
    {
        if (
            !is_null($id) &&
            !is_numeric($id) ||
            !is_null($this->id) ||
            $id <= 0 ||
            $id > 9223372036854775807
        ) {
            throw new TaskException("Task ID Error");
        }

        $this->id = $id;
    }

    public function setTitle($title)
    {
        if (strlen($title) < 0 || strlen($title) > 255) {
            throw new TaskException("Task Title Error");
        }
        $this->title = $title;
    }

    public function setDescription($description)
    {
        error_log($description);
        if (is_null($description) && (strlen($description > 16777215))) {
            throw new ("Description Title Error");
        }
        $this->description = $description;
    }

    public function setCompleted($completed)
    {
        if (strtoupper($completed) !== 'Y' && strtoupper($completed) !== 'N') {
            throw new TaskException("Task completed must be Y or N");
        }
        $this->completed = $completed;
    }

    public function setDeadline($deadline)
    {
        if (
            !is_null($deadline) &&
            date_format(date_create_from_format('d/m/Y H:i', $deadline), 'd/m/Y H:i') != $deadline
        ) {
            throw new TaskException("Task deadline date time error");
        }

        $this->deadline = $deadline;
    }

    public function returnTaskAsArray()
    {
        $task = array();
        $task['id'] = $this->getId();
        $task['title'] = $this->getTitle();
        $task['description'] = $this->getDescription();
        $task['deadline'] = $this->getDeadLine();
        $task['completed'] = $this->getCompleted();

        return $task;
    }
}
