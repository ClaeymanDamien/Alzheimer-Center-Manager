<?php
/**
 * Created by PhpStorm.
 * User: Damien CLAEYMAN CLEMENT LAMBLING
 * Date: 06/11/2018
 * Time: 11:09
 */

class Item
{
    /**declaration of the attributes*/
    private $id,
            $itemDesc,
            $quantity,
            $oldQuantity,
            $pictureName,
            $pictureTmpName,
            $picturePath,
            $stockQuantity,
            $minItem,
            $errors = [];


    const INVALID_DESCRIPTION = 1;
    const INVALID_EXTENSION = 2;
    const FAIL_MOVE_PICTURE = 3;
    const INVALID_QUANTITY = 4;
    const NOT_ENOUGH_QUANTITY = 5;
    const INVALID_MIN_QUANTITY = 6;

    const MIN_STOCK_QUANTITY = 5;


    const FILE_PATH_ROOT = 'images/item/';
    const FILE_PATH = '../images/item/';

    /**the function constructor */
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

    /**function used to call the setters */
    public function hydrate($data)
    {
        foreach ($data as $attribute => $values)
        {
            $method = 'set'.ucfirst($attribute); /** Call the setters of the attributes in the constructor */

            if (is_callable([$this, $method]))
            {
                $this->$method($values);
            }
        }
    }
    /**function to check if we need to send an email to alert the user */
    function checkStockQuantityMailNotification()
    {
        if($this->quantity < $this->minItem)
            return true;
        else
            return false;
    }
    /**function to verify the number of items */
    public function checkEnoughItem($quantity)
    {
        if($this->stockQuantity - $quantity < 0)
        {
            $this->errors[] = self::NOT_ENOUGH_QUANTITY;
            return false;
        }
        else
            return true;
    }
    /**functions "isValid..." checks the information before sending to the database */
    public function isValid()
    {
        if(empty($this->itemDesc) || empty($this->stockQuantity) || empty($this->minItem))
            return false;
        else
            return true;
    }

    public function isValidAddPatient()
    {
        if(empty($this->quantity) || empty($this->id))
            return false;
        else
        {
            if($this->checkEnoughItem($this->quantity))
                return true;
            else
                return false;
        }
    }

    public function isValidUpdatePatient($quantity)
    {
        if(empty($this->quantity) || empty($this->id))
            return false;
        else
        {
            if($this->checkEnoughItem($quantity))
                return true;
            else
                return false;
        }
    }
    /**
     * Check if the file respect the chart
     * @return bool
     */
    public function isValidPictureFormat()
    {
        $allowedExtension = array('png','gif','jpg','jpeg');

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

    /** We get the extension of the picture, we rename it, and put it in the folder, else it returns an error */
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

    /** check the picture condition and then add it */
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

    /**
     * @param mixed $id
     */
    /**SETTERS: if information are not valid it fills the array errors*/
    public function setId($id)
    {
        $this->id = (int) $id;
    }

    /**
     * @param mixed $itemDesc
     */

    public function setItemDesc($itemDesc)
    {
        if(empty($itemDesc) || !is_string($itemDesc))
            $this->errors[] = self::INVALID_DESCRIPTION;
        else
            $this->itemDesc = $itemDesc;
    }

    /**
     * @param mixed $pictureName
     */
    public function setPictureName($pictureName)
    {
        $this->pictureName = $pictureName;
    }

    /**
     * @param mixed $pictureTmpName
     */
    public function setPictureTmpName($pictureTmpName)
    {
        $this->pictureTmpName = $pictureTmpName;
    }

    /**
     * @param mixed $picturePath
     */
    public function setPicturePath($picturePath)
    {
        $this->picturePath = $picturePath;
    }

    /**
     * @param array $errors
     */
    public function setErrors($errors)
    {
        $this->errors = $errors;
    }

    /**
     * @param mixed $quantity
     */
    public function setQuantity($quantity)
    {

        if(empty($quantity))
            $this->errors[] = self::INVALID_QUANTITY;
        else
            $this->quantity = (int) $quantity;
    }

    /**
     * @param mixed $oldQuantity
     */
    public function setOldQuantity($oldQuantity)
    {
        $this->oldQuantity = (int) $oldQuantity;
    }

    /**
     * @param mixed $stockQuantity
     */
    public function setStockQuantity($stockQuantity)
    {
        if($stockQuantity<0)
            $this->errors[] = self::INVALID_QUANTITY;
        else
            $this->stockQuantity = (int) $stockQuantity;
    }

    /**
     * @param mixed $minItem
     */
    public function setMinItem($minItem)
    {
        if(empty($minItem))
            $this->errors[] = self::INVALID_MIN_QUANTITY;
        else
            $this->minItem = $minItem;
    }

    /**
     * @return array
     */
    /**GETTERS */
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
    public function getItemDesc()
    {
        return $this->itemDesc;
    }

    /**
     * @return mixed
     */
    public function getPictureName()
    {
        return $this->pictureName;
    }

    /**
     * @return mixed
     */
    public function getPicturePath()
    {
        return $this->picturePath;
    }

    /**
     * @return mixed
     */
    public function getPictureTmpName()
    {
        return $this->pictureTmpName;
    }

    /**
     * @return mixed
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @return mixed
     */
    public function getOldQuantity()
    {
        return $this->oldQuantity;
    }

    /**
     * @return mixed
     */
    public function getStockQuantity()
    {
        return $this->stockQuantity;
    }

    /**
     * @return mixed
     */
    public function getMinItem()
    {
        return $this->minItem;
    }

}