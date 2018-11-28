<?php

/**
 * Created by PhpStorm.
 * User: Damien CLAEYMAN CLEMENT LAMBLING
 * Date: 24/10/2018
 * Time: 11:47
 */

/** same think as the Item class */
class User
{


    private $id,
            $cellNum,
            $status;

    protected   $fName,
                $lName,
                $address1,
                $address2,
                $postalCode,
                $email,
                $password,
                $passwordConfirmed,
                $pictureName,
                $pictureTmpName,
                $picturePath,
                $items = [],
                $medicines = [],
                $errors = [];


    const FILE_PATH_ROOT = 'images/user/';
    const FILE_PATH = '../images/user/';

    const NOT_SAME_PASSWORD = 1;
    const INVALID_F_NAME = 2;
    const INVALID_L_NAME = 3;
    const INVALID_EMAIL = 4;
    const INVALID_PASSWORD = 5;
    const INVALID_SUPER_PASSWORD = 6;
    const INVALID_EXTENSION = 7;
    const FAIL_MOVE_PICTURE = 8;

    const SUPER_PASSWORD = "Admin2018";


    public function __construct($values = [])
    {
        $this->picturePath = "unknown";
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

    /**
     * @param $password
     * @return bool
     */

    /** Function superpassword to be able to modify user and patient */
    public function superPassword($password)
    {
        if($password == self::SUPER_PASSWORD && !empty($password))
        {
            return true;
        }
        else
        {
            $this->errors[] = self::INVALID_SUPER_PASSWORD;
            return false;
        }

    }

    /** Display the object user */
    public function description()
    {
        $output = "Id = ". $this->id ."<br>";
        $output = $output . "First Name = ". $this->fName ."<br>";
        $output = $output . "Last Name = ". $this->lName."<br>";
        $output = $output . "Address 1 = ". $this->address1."<br>";
        $output = $output . "Address 2 = ". $this->address2."<br>";
        $output = $output . "Postal Code = ". $this->postalCode."<br>";
        $output = $output . "Email = ". $this->email."<br>";
        $output = $output . "Cell Num = ". $this->cellNum."<br>";
        $output = $output . "Password = ". $this->password."<br>";
        $output = $output . "User Image = ". $this->picturePath."<br>";
        $output = $output . "Status = ". $this->status."<br>";

        return $output;
    }

    public function checkSamePassword()
    {
        if($this->password != $this->passwordConfirmed)
        {
            $this->errors[] = self::NOT_SAME_PASSWORD;
            return false;
        }
        else
        {
            return true;
        }
    }

    public function isValidUpdate()
    {
        $valid = true;
        if(!empty($this->password))
        {
            if(!$this->checkSamePassword())
            {
                $valid = false;
            }
        }
        if(empty($this->lName) || empty($this->fName) || empty($this->email))
        {
            $valid = false;
        }

        if($valid)
            return true;
        else
            return false;
    }

    public function isValid()
    {
        $valid = true;
        if(!$this->checkSamePassword())
        {
            $valid = false;
        }
        if(empty($this->fName) || empty($this->lName) || empty($this->email) || empty($this->password))
        {
            $valid = false;
        }

        if($valid == true)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Check if the file respect the chart
     * @return bool
     */
    public function isValidPictureFormat()
    {
        $allowedExtension = array('png','gif','jpg','jpeg','JPG','PNG','JPEG');

        $extension = substr(strrchr($this->pictureName,'.'),1);

        if(!in_array($extension,$allowedExtension))
        {
            $this->errors[] = self::INVALID_EXTENSION;
            return false;
        }
        return true;
    }

    /**
     * Move the upload image in the folder
     * @return bool
     */
    public function movePicture()
    {
        $extension = substr(strrchr($this->pictureName,'.'),1);

        $newName = basename($this->id.'.'.$extension);

        $newNames = basename($newName);

        $this->pictureName = $newNames;

        $this->picturePath = self::FILE_PATH_ROOT.$this->pictureName;

        if(file_exists(self::FILE_PATH_ROOT))
        {
            return move_uploaded_file($this->pictureTmpName,self::FILE_PATH_ROOT.$newNames);
        }
        else if(file_exists(self::FILE_PATH))
        {
            return move_uploaded_file($this->pictureTmpName,self::FILE_PATH.$newNames);
        }
        else
        {
            $this->errors[] = self::FAIL_MOVE_PICTURE;
            return false;
        }

    }

    /**
     * @return bool
     */
    public function addPicture()
    {
        if($this->isValidPictureFormat())
        {
            if($this->movePicture())
                return true;
            else
                return false;
        }
        else
        {
            return false;
        }
    }

    /**Getter*/

    public function getId()
    {
        return $this->id;
    }

    public function getLName()
    {
        return $this->lName;
    }

    public function getFName()
    {
        return $this->fName;
    }

    public function getAddress1()
    {
        return $this->address1;
    }

    public function getAddress2()
    {
        return $this->address2;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getCellNum()
    {
        return $this->cellNum;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getPostalCode()
    {
        return $this->postalCode;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getPasswordConfirmed()
    {
        return $this->passwordConfirmed;
    }

    public function getPictureName()
    {
        return $this->pictureName;
    }

    public function getPictureTmpName()
    {
        return $this->pictureTmpName;
    }

    public function getPicturePath()
    {
        return $this->picturePath;
    }

    /**
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @return array
     */
    public function getMedicines()
    {
        return $this->medicines;
    }

    /** Setter */

    /**
     * @param $id
     */
    public function setId($id)
    {
        $this->id = (int) $id;
    }

    /**
     * @param $lName
     */
    public function setLName($lName)
    {
        if(empty($lName) || !is_string($lName))
        {
            $this->errors[] = self::INVALID_L_NAME;
        }
        else
        {
            $this->lName = $lName ;
        }
    }

    public function setFName($fName)
    {
        if(empty($fName) || !is_string($fName))
        {
            $this->errors[] = self::INVALID_F_NAME;
        }
        else
        {
            $this->fName = $fName;
        }
    }

    /**
     * @param $address1
     */
    public function setAddress1($address1)
    {
        $this->address1 = $address1;
    }

    /**
     * @param $address2
     */
    public function setAddress2($address2)
    {
        $this->address2 = $address2;
    }

    /**
     * @param $cellNum
     */
    public function setCellNum($cellNum)
    {
        $this->cellNum = $cellNum;
    }

    /**
     * @param $email
     */
    public function setEmail($email)
    {
        if(empty($email) || !is_string($email))
        {
            $this->errors[] = self::INVALID_EMAIL;
        }
        else
        {
            $this->email = $email;
        }
        $this->email = $email;
    }

    /**
     * @param $password
     */
    public function setPassword($password)
    {
        if(empty($password))
        {
            $this->errors[] = self::INVALID_PASSWORD;
        }
        else
        {
            $this->password = $password;
        }
    }

    /**
     * @param $postalCode
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;
    }

    /**
     * @param $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @param $passwordConfirmed
     */
    public function setPasswordConfirmed($passwordConfirmed)
    {
        $this->passwordConfirmed = $passwordConfirmed;
    }

    public function setErrors($errors)
    {
        $this->errors = $errors;
    }

    public function setPictureName($pictureName)
    {
        $this->pictureName = $pictureName;
    }

    public function setPictureTmpName($pictureTmpName)
    {
        $this->pictureTmpName = $pictureTmpName;
    }

    public function setPicturePath($picturePath)
    {
        $this->picturePath = $picturePath;
    }

    /**
     * @param array $items
     */
    public function setItems($items)
    {
        $this->items = $items;
    }

    /**
     * @param array $medicines
     */
    public function setMedicines($medicines)
    {
        $this->medicines = $medicines;
    }
}
