<?php

/**
 * Created by PhpStorm.
 * User: dclae
 * Date: 26/10/2018
 * Time: 13:03
 */
class Patient extends User
{
    private $roomNo,
            $nextOfKind,
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
        $output = $output . "NextOfKind = ".$this->nextOfKind ."<br>";
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

    public function setNextOfKind($nextOfKind)
    {
        $this->nextOfKind = $nextOfKind;
    }

    public function setRoomNo($roomNo)
    {
        $this->roomNo = $roomNo;
    }

    public function setPrescript($prescript)
    {
        $this->prescript = $prescript;
    }

    /** GETTER */

    public function getGradeClassification()
    {
        return $this->gradeClassification;
    }

    public function getNextOfKind()
    {
        return $this->nextOfKind;
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
