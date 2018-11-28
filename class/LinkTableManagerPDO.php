<?php
/**
 * Created by PhpStorm.
 * User: Damien CLAEYMAN CLEMENT LAMBLING
 * Date: 07/11/2018
 * Time: 14:25
 */

/** same thing as ItemManagerPDO, it is just SQL queries */
class LinkTableManagerPDO
{
    protected $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /** We take information from the table patient_item from the database */
    /**
     * @param $idItem
     * @param $idPatient
     * @return bool|PDOStatement
     */
    public function selectItemPatient($idItem, $idPatient)
    {
        $req = $this->db->prepare('SELECT * FROM tbl_patient_item WHERE tbl_Item_ID = :tbl_Item_ID AND tbl_Patient_ID = :tbl_Patient_ID');
        $req->bindValue('tbl_Item_ID', $idItem);
        $req->bindValue('tbl_Patient_ID', $idPatient);
        $req->execute();

        return $req;
    }
    /**  */
    public function selectAllItemPatient($idPatient)
    {
        $req = $this->db->prepare('SELECT * FROM tbl_patient_item WHERE tbl_Patient_ID = :id');
        $req->bindValue(':id', $idPatient);
        $req->execute();

        return $req;
    }

    /** Function to add an item in the database */
    /**
     * @param Patient $patient
     * @param Item $item
     */
    public function addItemPatient(Patient $patient, Item $item)
    {
        $req = $this->db->prepare('INSERT INTO tbl_patient_item(tbl_Item_ID, tbl_Patient_ID, Quantity)
        VALUE (:itemID, :patientID, :quantity)');
        $req->execute(array(
            ':itemID' => $item->getId(),
            ':patientID' => $patient->getId(),
            'quantity' => $item->getQuantity()
        ));
        $req->closeCursor();
    }

    /** Function to update the database */
    public function updateItemPatient(Patient $patient, Item $item)
    {
        $req = $this->db->prepare('UPDATE tbl_patient_item SET Quantity = :quantity WHERE tbl_Item_ID = :tbl_Item_ID AND tbl_Patient_ID = :tbl_Patient_ID ');
        $req->bindValue(':quantity', $item->getQuantity());
        $req->bindValue(':tbl_Item_ID', $item->getId());
        $req->bindValue(':tbl_Patient_ID', $patient->getId());
        $req->execute();
        $req->closeCursor();
    }

    /** Function to delete an item from the table in the database */
    public function deleteItemPatient($idPatient, $idItem)
    {
        $req = $this->db->prepare('DELETE FROM tbl_patient_item WHERE tbl_Item_ID = :tbl_Item_ID AND tbl_Patient_ID = :tbl_Patient_ID');
        $req->bindValue(':tbl_Item_ID', $idItem);
        $req->bindValue(':tbl_Patient_ID', $idPatient);
        $req->execute();
        $req->closeCursor();
    }

    public function deleteItem($idItem)
    {
        $req = $this->db->prepare('DELETE FROM tbl_patient_item WHERE tbl_Item_ID = :tbl_Item_ID');
        $req->bindValue(':tbl_Item_ID', $idItem);
        $req->execute();
        $req->closeCursor();
    }

    public function deleteMedicine($idMedicine)
    {
        $req = $this->db->prepare('DELETE FROM tbl_patient_medicine WHERE tbl_Medicine_ID = :tbl_Medicine_ID');
        $req->bindValue(':tbl_Medicine_ID', $idMedicine);
        $req->execute();
        $req->closeCursor();
    }

    public function deletePatient($idPatient)
    {
        $req = $this->db->prepare('DELETE FROM tbl_patient_item WHERE tbl_Patient_ID = :tbl_Patient_ID');
        $req->bindValue(':tbl_Patient_ID', $idPatient);
        $req->execute();
        $req->closeCursor();
        $req = $this->db->prepare('DELETE FROM tbl_patient_medicine WHERE tbl_Patient_ID = :tbl_Patient_ID');
        $req->bindValue(':tbl_Patient_ID', $idPatient);
        $req->execute();
        $req->closeCursor();
    }

    public function selectMedicinePatient($idMedicine, $idPatient)
    {
        $req = $this->db->prepare('SELECT * FROM tbl_patient_medicine WHERE tbl_Medicine_ID = :tbl_Medicine_ID AND tbl_Patient_ID = :tbl_Patient_ID');
        $req->bindValue('tbl_Medicine_ID', $idMedicine);
        $req->bindValue('tbl_Patient_ID', $idPatient);
        $req->execute();

        return $req;
    }

    public function selectAllItemMedicine($idMedicine)
    {
        $req = $this->db->prepare('SELECT * FROM tbl_patient_medicine WHERE tbl_Patient_ID = :id');
        $req->bindValue(':id', $idMedicine);
        $req->execute();

        return $req;
    }

    /**
     * @param Patient $patient
     * @param Item $item
     */
    public function addMedicinePatient(Patient $patient, Medicine $medicine)
    {
        $req = $this->db->prepare('INSERT INTO tbl_patient_medicine(tbl_Medicine_ID, tbl_Patient_ID, Dosage)
        VALUE (:medicineID, :patientID, :dosage)');
        $req->execute(array(
            ':medicineID' => $medicine->getId(),
            ':patientID' => $patient->getId(),
            'dosage' => $medicine->getDosage()
        ));
        $req->closeCursor();
    }

    public function updateMedicinePatient(Patient $patient, Medicine $medicine)
    {
        $req = $this->db->prepare('UPDATE tbl_patient_medicine SET Dosage = :dosage WHERE tbl_Medicine_ID = :tbl_Medicine_ID AND tbl_Patient_ID = :tbl_Patient_ID ');
        $req->bindValue(':dosage', $medicine->getDosage());
        $req->bindValue(':tbl_Medicine_ID', $medicine->getId());
        $req->bindValue(':tbl_Patient_ID', $patient->getId());
        $req->execute();
        $req->closeCursor();
    }

    public function deleteMedicinePatient($idPatient, $idMedicine)
    {
        $req = $this->db->prepare('DELETE FROM tbl_patient_medicine WHERE tbl_Medicine_ID = :tbl_Medicine_ID AND tbl_Patient_ID = :tbl_Patient_ID');
        $req->bindValue(':tbl_Medicine_ID', $idMedicine);
        $req->bindValue(':tbl_Patient_ID', $idPatient);
        $req->execute();
        $req->closeCursor();
    }
}