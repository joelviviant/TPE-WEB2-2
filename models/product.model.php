<?php

class ProductModel {

    private $db;

    public function __construct() {
        $this->db = new PDO('mysql:host=localhost;'.'dbname=db_cart;charset=utf8', 'root', '');
    }

    public function getAll($page,$per_page,$categoria,$sort,$order) {
        $query =  "select p.id,p.nombre,p.marca,p.cantidad,p.vendido, c.nombre as categoria,c.id_categoria from producto p join categoria c on p.categoria=c.id_categoria  ";
        if(!is_null($categoria)){
            $query.= "WHERE lower(c.nombre) = lower('$categoria')";
        }
        if(!is_null($sort)){
            $query.="ORDER BY $sort $order";
        }
        if( !is_null($page) && !is_null($per_page)){
            $limit= ($page-1)*$per_page;
            $query.= "LIMIT $limit, $per_page";
        }
        $query = $this->db->prepare( $query);
        $query -> execute();
        $products = $query->fetchALL(PDO::FETCH_OBJ); 
        return $products; 
    }

    public function get($id) {
        $query = $this->db->prepare( "select p.id,p.nombre,p.marca,p.cantidad,p.vendido, c.nombre as categoria,c.id_categoria from producto p join categoria c on p.categoria=c.id_categoria  WHERE id=?");
        $query -> execute(array($id));
        $product = $query->fetch(PDO::FETCH_OBJ);
        return $product; 
    }

    public function insert($nombre, $categoria, $cantidad, $marca, $vendido) {
        $query = $this->db->prepare("INSERT INTO producto (nombre, categoria, cantidad, marca, vendido) VALUES (?, ?, ?, ?, ?)");
        $query->execute([$nombre, $categoria, $cantidad, $marca, $vendido]);
        return $this->db->lastInsertId();
    }
   
    public function delete($id) {
        $query = $this->db->prepare("DELETE FROM producto WHERE id=?");
        $query->execute(array($id,));
    }

    public function edit($id,$valor) {
        $query = $this->db->prepare('UPDATE producto SET vendido=? WHERE id = ?');
        $query->execute([$valor, $id]);
    }

    public function editProduct($id, $nombre, $categoria, $cantidad,$marca,$vendido){
        $sentencia = $this->db->prepare("UPDATE producto SET nombre=?, categoria=?,  cantidad=?, marca=?, vendido=? WHERE id=?");
        $sentencia->execute(array($nombre,$categoria, $cantidad,$marca,$vendido,$id));
    }

}
