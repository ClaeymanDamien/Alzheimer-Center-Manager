<?php
/**
 * Created by PhpStorm.
 * User: dclae
 * Date: 06/10/2018
 * Time: 11:36
 */

class UserManagerPDO
{

    protected $db;
    protected $lastInsertId;


    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function ifExists(User $user)
    {
        $req = $this->db->prepare('SELECT * FROM tbl_user WHERE Email = :email');
        $req->bindValue(':email',$user->getEmail());
        $req->execute();

        if($req->fetch())
            return true;
        else
            return false;
    }

    public function selectAllPatient()
    {
        $req = $this->db->query('SELECT * FROM tbl_patient ORDER BY LName');
        return $req;
    }

    public function selectPatient($id)
    {
        $req = $this->db->prepare('SELECT * FROM tbl_patient WHERE ID = :id');
        $req->bindValue(':id',$id);
        $req->execute();
        return $req;
    }
    
    public function selectForeignPatient($id)
    {
        $req = $this->db->prepare('SELECT * FROM tbl_patient WHERE NextOfKin = :id');
        $req->bindValue(':id',$id);
        $req->execute();
        return $req;
    }

    /**
     * @param User $user
     */
    public function register(User $user)
    {
        $req = $this->db->prepare('INSERT INTO tbl_user(FName, LName, Address1, Address2, PostalCode, Email, CellNum, Password, UserImage, Status)
        VALUE (:FName, :LName, :address1, :address2, :PostalCode, :Email, :CellNum, :Password, :userImage, :Status)');
        $req->bindValue(':FName', $user->getFName());
        $req->bindValue(':LName', $user->getLName());
        $req->bindValue(':address1', $user->getAddress1());
        $req->bindValue(':address2', $user->getAddress2());
        $req->bindValue(':PostalCode', $user->getPostalCode());
        $req->bindValue(':Email', $user->getEmail());
        $req->bindValue(':CellNum', $user->getCellNum());
        $req->bindValue(':Password', sha1($user->getPassword()));
        $req->bindValue(':userImage', $user->getPicturePath());
        $req->bindValue(':Status', $user->getStatus());
        $req->execute();
    }

    /**
     * @param $name
     * @param $surname
     * @param $email
     * @param $password
     * @return null|User
     */
    public function login($name, $surname, $email, $password)
    {
        $login = $this->db->prepare('SELECT * FROM tbl_user WHERE FName = :FName AND LName = :LName AND Email = :email AND Password = :password');// verif des informations
        $login->execute(array(
            'FName' => $name,
            'LName' => $surname,
            'email' => $email,
            'password' => $password
        ));

        if($userInformation = $login->fetch())
        {
            $user = new User(array(
                'id' => $userInformation['ID'],
                'fName' => $userInformation['FName'],
                'lName' => $userInformation['LName'],
                'address1' => $userInformation['Address1'],
                'address2' => $userInformation['Address2'],
                'postalCode' => $userInformation['PostalCode'],
                'email'  => $userInformation['Email'],
                'cellNum' => $userInformation['CellNum'],
                'password' => $userInformation['Password'],
                'picturePath' => $userInformation['UserImage'],
                'status'  => $userInformation['Status'],
            ));
            return $user;
        }
        else
        {
            return NULL;
        }
    }

    public function updatePatient(Patient $patient)
    {
        $req = $this->db->prepare('
          UPDATE tbl_patient
          SET FName = :FName, LName = :LName, RoomNb = :RoomNb, GradeClassification = :GradeClassification,
              PostalCode = :PostalCode, Address1 = :address1, Address2 = :address2
          WHERE ID = :id');
        $req->bindValue(':id', $patient->getId());
        $req->bindValue(':FName', $patient->getFName());
        $req->bindValue(':LName', $patient->getLName());
        $req->bindValue(':RoomNb', $patient->getRoomNo());
        $req->bindValue(':GradeClassification', $patient->getGradeClassification());
        $req->bindValue(':PostalCode', $patient->getPostalCode());
        $req->bindValue(':address1', $patient->getAddress1());
        $req->bindValue(':address2', $patient->getAddress2());
        $req->execute();

        if(!empty($patient->getPassword()))
        {
            $reqPassword = $this->db->prepare('UPDATE tbl_patient SET Password = :Password WHERE ID = :ID');
            $reqPassword->bindValue(':ID', $patient->getId());
            $reqPassword->bindValue(':Password', sha1($patient->getPassword()));
            $reqPassword->execute();
        }

        if($patient->getPicturePath() != "unknown")
        {
            $reqPicture = $this->db->prepare('UPDATE tbl_patient SET patientImage = :patientImage WHERE ID = :ID');
            $reqPicture->bindValue(':ID', $patient->getId());
            $reqPicture->bindValue(':patientImage', $patient->getPicturePath());
            $reqPicture->execute();
        }
    }

    /** SETTER */

    /**
     * @param $lastInsertId : int
     */
    public function setLastInsertId($lastInsertId)
    {
        $this->lastInsertId = (int) $lastInsertId;
    }

    /** GETTER */
    public function getLastInsertId()
    {
        return $this->lastInsertId;
    }
}

