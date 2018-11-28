<?php
/**
 * Created by PhpStorm.
 * User: Damien CLAEYMAN CLEMENT LAMBLING
 * Date: 06/11/2018
 * Time: 11:10
 */

/** SQL queries to select, update, insert or delete in the database */
class ItemManagerPDO
{
    protected $db;

    /**Constructor*/
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }
    /**We take information from the database*/
    public function selectAllItem()
    {
        $req = $this->db->query('SELECT * FROM tbl_item');
        return $req;
    }

    public function selectItem($id)
    {
        $req = $this->db->prepare('SELECT * FROM tbl_item WHERE ID = :id');
        $req->bindValue(':id', $id);
        $req->execute();

        return $req;
    }
    /**We add an item in the database*/
    public function add(Item $item)
    {
        $req = $this->db->prepare('INSERT INTO tbl_item(StockItem, ItemDesc, ItemPic, ItemMinimum) VALUE (:stockItem, :itemDesc, :itemPic, :itemMinimum)');
        $req->bindValue(':stockItem', $item->getStockQuantity());
        $req->bindValue(':itemDesc', $item->getItemDesc());
        $req->bindValue(':itemPic', $item->getPicturePath());
        $req->bindValue(':itemMinimum', $item->getMinItem());
        $req->execute();
        $req->closeCursor();
    }

    /**We delete an item from the database */
    public function delete($id)
    {
        $req = $this->db->prepare('DELETE FROM tbl_item WHERE ID = :id');
        $req->bindValue(':id', $id);
        $req->execute();
        $req->closeCursor();
    }

    /**We update an item in the database */
    public function update(Item $item)
    {
        $req = $this->db->prepare('UPDATE tbl_item SET StockItem = :stockItem, ItemDesc = :itemDesc, ItemMinimum = :itemMinimum WHERE ID = :id');
        $req->bindValue('stockItem', $item->getStockQuantity());
        $req->bindValue('itemDesc', $item->getItemDesc());
        $req->bindValue('itemMinimum', $item->getMinItem());
        $req->bindValue('id', $item->getID());
        $req->execute();
        $req->closeCursor();

        /** if picture is upload */
        if(!empty($item->getPictureName()))
        {
            $reqPicture = $this->db->prepare('UPDATE tbl_item SET ItemPic = :itemPic WHERE ID = :id');
            $reqPicture->bindValue('itemPic', $item->getPicturePath());
            $reqPicture->bindValue('id', $item->getID());
            $reqPicture->execute();
            $reqPicture->closeCursor();
        }
    }

    /**We update the quantity*/
    public function updateItemStockQuantity(Item $item)
    {
        $req = $this->db->prepare('UPDATE tbl_item SET StockItem = :stockItem WHERE ID = :id');
        $req->bindValue('stockItem', $item->getStockQuantity());
        $req->bindValue('id', $item->getID());
        $req->execute();
        $req->closeCursor();
    }

    /** GETTER */
    public function getLastInsertId()
    {
        $req = $this->db->query('SELECT MAX(ID) AS maxId FROM tbl_item');
        $lastId = $req->fetch();
        $req->closeCursor();
        return $lastId['maxId'];
    }
}