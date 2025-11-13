<?php

class Product {
    private $conn;
    private $table_name = "productos";

    public $id;
    public $codigo;
    public $producto;
    public $precio;
    public $cantidad;
    public $descripcion;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Crear un nuevo producto
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET codigo=:codigo, producto=:producto, precio=:precio, cantidad=:cantidad, descripcion=:descripcion";
        $stmt = $this->conn->prepare($query);

        $this->codigo = htmlspecialchars(strip_tags($this->codigo));
        $this->producto = htmlspecialchars(strip_tags($this->producto));
        $this->precio = htmlspecialchars(strip_tags($this->precio));
        $this->cantidad = htmlspecialchars(strip_tags($this->cantidad));
        $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));

        $stmt->bindParam(":codigo", $this->codigo);
        $stmt->bindParam(":producto", $this->producto);
        $stmt->bindParam(":precio", $this->precio);
        $stmt->bindParam(":cantidad", $this->cantidad);
        $stmt->bindParam(":descripcion", $this->descripcion);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Obtener productos
    public function read() {
        $query = "SELECT id, codigo, producto, precio, cantidad, descripcion FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    // Leer un solo producto por ID
    public function readOne() {
        $query = "SELECT codigo, producto, precio, cantidad, descripcion FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->codigo = $row['codigo'];
            $this->producto = $row['producto'];
            $this->precio = $row['precio'];
            $this->cantidad = $row['cantidad'];
            $this->descripcion = $row['descripcion'];
            return true;
        }
        return false;
    }

    // Actualizar un producto existente
    public function update() {
        $query_parts = [];
        $params = [':id' => $this->id];

        if ($this->codigo !== null) {
            $query_parts[] = 'codigo=:codigo';
            $params[':codigo'] = htmlspecialchars(strip_tags($this->codigo));
        }
        if ($this->producto !== null) {
            $query_parts[] = 'producto=:producto';
            $params[':producto'] = htmlspecialchars(strip_tags($this->producto));
        }
        if ($this->precio !== null) {
            $query_parts[] = 'precio=:precio';
            $params[':precio'] = htmlspecialchars(strip_tags($this->precio));
        }
        if ($this->cantidad !== null) {
            $query_parts[] = 'cantidad=:cantidad';
            $params[':cantidad'] = htmlspecialchars(strip_tags($this->cantidad));
        }
        if ($this->descripcion !== null) {
            $query_parts[] = 'descripcion=:descripcion';
            $params[':descripcion'] = htmlspecialchars(strip_tags($this->descripcion));
        }

        if (empty($query_parts)) {
            return false; // No fields to update
        }

        $query = "UPDATE " . $this->table_name . " SET " . implode(', ', $query_parts) . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        if ($stmt->execute($params)) {
            return true;
        }
        return false;
    }

    // Eliminar un producto
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(":id", $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
