<?php
use Phalcon\Mvc\Model\Validator\Uniqueness as Uniqueness;
class Team extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $master_id;

    /**
     *
     * @var integer
     */
    public $name;

    /**
     *
     * @var string
     */
    public $description;

    /**
     *
     * @var integer
     */
    public $status;

    /**
     *
     * @var string
     */
    public $date_created;

    /**
     *
     * @var string
     */
    public $date_modified;

    public function validation()
    {
        $this->validate(new Uniqueness(array(
          "field" => 'name',
          "message"=> 'Team has already been registered please try again another'
        )));
        if ($this->validationHasFailed() == true) {
          return false;
        }
    }
    
    public function beforeValidationOnCreate()
    {
        $this->date_created = CURR_DATE;
        $this->status = 1;
    }
    public function beforeValidationOnUpdate()
    {
        $this->date_modified = CURR_DATE;
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id', 
            'master_id' => 'master_id', 
            'name' => 'name', 
            'description' => 'description', 
            'status' => 'status', 
            'date_created' => 'date_created', 
            'date_modified' => 'date_modified'
        );
    }

    public function listTeams($master_id)
    {
        $phql = "SELECT t.id as team_id,t.name as team_name, t.description,t.status,ts.name as status_name
                 FROM Team t
                 INNER JOIN TeamStatus ts ON ts.id = t.status
                 WHERE t.master_id = ?1
                 ORDER BY t.date_created,t.date_modified DESC";
        $data = $this->modelsManager->executeQuery($phql,array(1=>$master_id));
        return $data;
    }

}
