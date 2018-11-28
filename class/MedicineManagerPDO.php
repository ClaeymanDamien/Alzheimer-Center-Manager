<?php
/**
 * Created by PhpStorm.
 * User: Damien CLAEYMAN CLEMENT LAMBLING
 * Date: 06/11/2018
 * Time: 11:10
 */

/** same thing as ItemManagerPDO, it is just SQL queries */
class MedicineManagerPDO
{
    protected $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function selectAllMedicine()
    {
        $req = $this->db->query('SELECT * FROM tbl_medicine');
        return $req;
    }

    public function selectMedicine($id)
    {
        $req = $this->db->prepare('SELECT * FROM tbl_medicine WHERE ID = :id');
        $req->bindValue(':id', $id);
        $req->execute();

        return $req;
    }

    public function add(Medicine $medicine)
    {
        $req = $this->db->prepare('INSERT INTO tbl_medicine(MedDesc, Schedule) VALUE (:medDesc, :schedule)');
        $req->bindValue(':medDesc', $medicine->getMedDesc());
        $req->bindValue(':schedule', $medicine->getSchedule());
        $req->execute();
        $req->closeCursor();
    }

    public function delete($id)
    {
        $req = $this->db->prepare('DELETE FROM tbl_medicine WHERE ID = :id');
        $req->bindValue(':id', $id);
        $req->execute();
        $req->closeCursor();
    }

    public function update(Medicine $medicine)
    {
        $req = $this->db->prepare('UPDATE tbl_medicine SET MedDesc = :medDesc, Schedule = :schedule WHERE ID = :id');
        $req->bindValue(':medDesc', $medicine->getMedDesc());
        $req->bindValue(':schedule', $medicine->getSchedule());
        $req->bindValue(':id', $medicine->getId());
        $req->execute();
        $req->closeCursor();
    }
}