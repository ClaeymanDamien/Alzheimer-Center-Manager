<?php
/**
 * Created by PhpStorm.
 * User: Damien CLAEYMAN CLEMENT LAMBLING
 * Date: 06/10/2018
 * Time: 11:36
 */

/** same thing as ItemManagerPDO, it is just SQL queries for both user and patient table as patient inherits of user*/
class UserManagerPDO
{

    protected $db;
    protected $lastInsertId;


    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /** We check if the email is already used */

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

    /** checking by ID */
    public function ifPatientExists($id)
    {
        $req = $this->selectPatient($id);

        if($req->fetch())
            return true;
        else
            return false;
    }

    public function ifUserExists($id)
    {
        $req = $this->selectUser($id);

        if($req->fetch())
            return true;
        else
            return false;
    }

    /** get the ID for the next of kin */

    /**
     * @param User $user
     * @return null
     */
    public function getForeignKey(Patient $patient)
    {
        $req = $this->db->prepare('SELECT * FROM tbl_user WHERE Email = :email');
        $req->bindValue(':email',$patient->getEmail());
        $req->execute();

        if($data = $req->fetch())
        {
            if($data['Status'] == 'user')
                return $data['ID'];
            else
                NULL;

        }
        else
        {
            return NULL;
        }
    }

    /** functions to return data in the table patient order by last name or just one user selected by id or next of kin */
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
    {;
        $req = $this->db->prepare('SELECT * FROM tbl_patient WHERE NextOfKin = :id');
        $req->bindValue(':id',$id);
        $req->execute();
        return $req;
    }

    /** functions to return data in the table user order by last name or just one user selected by id */
    public function selectAllUser()
    {
        $req = $this->db->query('SELECT * FROM tbl_user ORDER BY LName');
        return $req;
    }

    public function selectUser($id)
    {
        $req = $this->db->prepare('SELECT * FROM tbl_user WHERE ID = :id');
        $req->bindValue(':id',$id);
        $req->execute();
        return $req;
    }

    /**
     * Register a new user in the table user
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
        $req->closeCursor();
    }

        /** delete a user */
    public function deleteUser($id)
    {
        $req = $this->db->prepare('DELETE FROM tbl_user WHERE ID = :id');
        $req->bindValue(':id',$id);
        $req->execute();
        $req->closeCursor();
    }

        /** update a user in the table */
    public function updateUser(User $User)
    {
        $req = $this->db->prepare('
          UPDATE tbl_user
          SET FName = :FName, LName = :LName, Email = :Email, CellNum= :CellNum,
              PostalCode = :PostalCode, Address1 = :address1, Address2 = :address2, Status = :Status
          WHERE ID = :id');
        $req->bindValue(':id', $User->getId());
        $req->bindValue(':FName', $User->getFName());
        $req->bindValue(':LName', $User->getLName());
        $req->bindValue(':Email', $User->getEmail());
        $req->bindValue(':CellNum', $User->getCellNum());
        $req->bindValue(':PostalCode', $User->getPostalCode());
        $req->bindValue(':address1', $User->getAddress1());
        $req->bindValue(':address2', $User->getAddress2());
        $req->bindValue(':Status', $User->getStatus());
        $req->execute();
        $req->closeCursor();

        /** If the password has changed, we check and if it is the case, we hash it before sending in the database */
        if(!empty($User->getPassword()))
        {
            $reqPassword = $this->db->prepare('UPDATE tbl_user SET Password = :Password WHERE ID = :ID');
            $reqPassword->bindValue(':ID', $User->getId());
            $reqPassword->bindValue(':Password', sha1($User->getPassword()));
            $reqPassword->execute();
            $reqPassword->closeCursor();
        }

        /** If a picture is upload, we get the path */
        if($User->getPicturePath() != "unknown")
        {
            $reqPicture = $this->db->prepare('UPDATE tbl_user SET UserImage = :UserImage WHERE ID = :ID');
            $reqPicture->bindValue(':ID', $User->getId());
            $reqPicture->bindValue(':UserImage', $User->getPicturePath());
            $reqPicture->execute();
            $reqPicture->closeCursor();
        }
    }

    /**
     * check the information in order to login, if it is the case, it creates a new user with its information
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
            $login->closeCursor();
            return $user;
        }
        else
        {
            return NULL;
        }
    }

    /** add a patient in the table patient */
    public function addPatient(Patient $Patient)
    {
        $req = $this->db->prepare('INSERT INTO tbl_patient(FName, LName, Address1, Address2, PostalCode, RoomNb, GradeClassification, Password, PatientImage, NextOfKin)
        VALUE (:FName, :LName, :Address1, :Address2, :PostalCode, :RoomNb, :GradeClassification, :Password, :PatientImage, :NextOfKin)');
        $req->bindValue(':FName', $Patient->getFName());
        $req->bindValue(':LName', $Patient->getLName());
        $req->bindValue(':Address1', $Patient->getAddress1());
        $req->bindValue(':Address2', $Patient->getAddress2());
        $req->bindValue(':PostalCode', $Patient->getPostalCode());
        $req->bindValue(':RoomNb', $Patient->getRoomNo());
        $req->bindValue(':GradeClassification', $Patient->getGradeClassification());
        $req->bindValue(':Password', sha1($Patient->getPassword()));
        $req->bindValue(':PatientImage', $Patient->getPicturePath());
        $req->bindValue(':NextOfKin', $Patient->getNextOfKind());
        $req->execute();
        $req->closeCursor();
    }

    /** update the patient in the table */
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
        $req->closeCursor();


        if(!empty($patient->getPassword()))
        {
            $reqPassword = $this->db->prepare('UPDATE tbl_patient SET Password = :Password WHERE ID = :ID');
            $reqPassword->bindValue(':ID', $patient->getId());
            $reqPassword->bindValue(':Password', sha1($patient->getPassword()));
            $reqPassword->execute();
            $reqPassword->closeCursor();
        }

        if(!empty($patient->getEmail()))
        {
            $req = $this->db->prepare('UPDATE tbl_patient SET NextOfKin = :NextOfKin WHERE ID = :ID');
            $req->bindValue(':ID', $patient->getId());
            $req->bindValue(':NextOfKin', $patient->getNextOfkin());
            $req->execute();
            $req->closeCursor();
        }

        if($patient->getPicturePath() != "unknown")
        {
            $reqPicture = $this->db->prepare('UPDATE tbl_patient SET patientImage = :patientImage WHERE ID = :ID');
            $reqPicture->bindValue(':ID', $patient->getId());
            $reqPicture->bindValue(':patientImage', $patient->getPicturePath());
            $reqPicture->execute();
            $reqPicture->closeCursor();
        }
    }

    public function deletePatient($id)
    {
        $req = $this->db->prepare('DELETE FROM tbl_patient WHERE ID = :id');
        $req->bindValue(':id',$id);
        $req->execute();
        $req->closeCursor();
    }


    /** GETTER */
    public function getLastInsertId()
    {
        $req = $this->db->query('SELECT MAX(ID) AS maxId FROM tbl_user');
        $lastId = $req->fetch();
        $req->closeCursor();
        return $lastId['maxId'];
    }
}

