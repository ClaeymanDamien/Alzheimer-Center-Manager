<?php
/**
 * Created by PhpStorm.
 * User: Damien CLAEYMAN CLEMENT LAMBLING
 * Date: 06/11/2018
 * Time: 11:10
 */

/** same think as the Item class */
class Medicine
{
    private $id,
            $medDesc,
            $schedule,
            $dosage,
            $errors = [];

    const INVALID_DESCRIPTION = 1;
    const INVALID_SCHEDULE = 2;


    public function __construct($values = [])
    {
        if (!empty($values))
        {
            $this->hydrate($values);
        }
    }

    /**
     * @param $data array
     * @return void
     */
    public function hydrate($data)
    {
        foreach ($data as $attribute => $values)
        {
            $method = 'set'.ucfirst($attribute);

            if (is_callable([$this, $method]))
            {
                $this->$method($values);
            }
        }
    }

    public function isValid()
    {
        if(empty($this->medDesc) || empty($this->schedule))
            return false;
        else
            return true;
    }

    public function isValidAddPatient()
    {
        if(empty($this->dosage) || empty($this->id))
            return false;
        else
            return true;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = (int) $id;
    }

    /**
     * @param $medDesc
     */
    public function setMedDesc($medDesc)
    {
        if(empty($medDesc) || !is_string($medDesc))
            $this->errors[] = self::INVALID_DESCRIPTION;
        else
            $this->medDesc = $medDesc;
    }

    /**
     * @param mixed $schedule
     */
    public function setSchedule($schedule)
    {
        if(empty($schedule) || !is_string($schedule))
            $this->errors[] = self::INVALID_SCHEDULE;
        else
            $this->schedule = $schedule;
    }


    /**
     * @param array $errors
     */
    public function setErrors($errors)
    {
        $this->errors = $errors;
    }

    /**
     * @param mixed $dosage
     */
    public function setDosage($dosage)
    {
        $this->dosage = $dosage;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getMedDesc()
    {
        return $this->medDesc;
    }

    /**
     * @return mixed
     */
    public function getSchedule()
    {
        return $this->schedule;
    }

    /**
     * @return mixed
     */
    public function getDosage()
    {
        return $this->dosage;
    }

}