<?php

/**
 * Created by PhpStorm.
 * User: Damien CLAEYMAN CLEMENT LAMBLING
 * Date: 26/10/2018
 * Time: 13:03
 */

/** same think as the Item class, just patient inherits from User */

class Patient extends User
{
    private $roomNo,
            $nextOfKin,
            $prescript,
            $gradeClassification;


    const INVALID_GRADE_CLASSIFICATION = 1;

    /** SETTER */

    /**
     * Patient constructor.
     * @param array $values
     */
    public function __construct($values = [])
    {
        if (!empty($values))
        {
            parent::__construct($values);
            $this->hydrate($values);
        }
    }

    public function description()
    {
        $output = parent::description();
        $output = $output . "Room number = ".$this->roomNo ."<br>";
        $output = $output . "NextOfkin = ".$this->nextOfKin ."<br>";
        $output = $output . "Prescript = " . $this->prescript ."<br>";
        $output = $output . "Grade Classification = " . $this->gradeClassification ."<br>";
        return $output;

    }

    public function isValid()
    {
        if(empty($this->lName) || empty($this->fName))
            return false;
        else
            return true;
    }

    public function isValidAdd()
    {
        if($this->isValid())
        {
            if(empty($this->password))
                return false;
            else
                return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * @param $gradeClassification
     */
    public function setGradeClassification($gradeClassification)
    {
        if($gradeClassification == 'A' || $gradeClassification == 'B' || $gradeClassification == 'C')
        {
            $this->gradeClassification = $gradeClassification;
        }
        else
        {
            $this->errors[] = self::INVALID_GRADE_CLASSIFICATION;
        }
    }

    public function setRoomNo($roomNo)
    {
        $this->roomNo = $roomNo;
    }

    public function setPrescript($prescript)
    {
        $this->prescript = $prescript;
    }

    /**
     * @param mixed $nextOfKin
     */
    public function setNextOfKin($nextOfKin)
    {
        $this->nextOfKin = $nextOfKin;
    }

    /** GETTER */

    public function getGradeClassification()
    {
        return $this->gradeClassification;
    }

    public function getNextOfKin()
    {
        return $this->nextOfKin;
    }

    public function getPrescript()
    {
        return $this->prescript;
    }

    public function getRoomNo()
    {
        return $this->roomNo;
    }
}
