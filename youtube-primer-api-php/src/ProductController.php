<?php
// src/UserController.php

require_once '../config/database.php';
require_once 'Product.php';

class ProductController {
    private $db;
    private $product;

    public function __construct() {
        $database = new DB(); // Use the provided DB class
        $this->db = $database->getConnection();
        $this->product = new Product($this->db);
    }

    // Método para manejar la solicitud POST (Crear un producto)
    public function create() {
        $data = json_decode(file_get_contents("php://input"));

        if (!empty($data->codigo) && !empty($data->producto) && !empty($data->precio) && !empty($data->cantidad)) {
            $this->product->codigo = $data->codigo;
            $this->product->producto = $data->producto;
            $this->product->precio = $data->precio;
            $this->product->cantidad = $data->cantidad;
            $this->product->descripcion = $data->descripcion ?? null; // Descripcion puede ser NULL

            if ($this->product->create()) {
                http_response_code(201);
                echo json_encode(["message" => "Producto creado exitosamente."]);
            } else {
                http_response_code(503);
                echo json_encode(["message" => "No se pudo crear el producto."]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Datos incompletos. Se requieren codigo, producto, precio y cantidad."]);
        }
    }

    // Método para manejar la solicitud GET (Leer productos)
    public function read() {
        $stmt = $this->product->read();
        $num = $stmt->rowCount();

        if ($num > 0) {
            $product_arr = [];
            $product_arr["registros"] = [];

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $product_item = [
                    "id" => $id,
                    "codigo" => $codigo,
                    "producto" => $producto,
                    "precio" => $precio,
                    "cantidad" => $cantidad,
                    "descripcion" => $descripcion,
                ];
                array_push($product_arr["registros"], $product_item);
            }

            http_response_code(200);
            echo json_encode($product_arr);
        } else {
            http_response_code(404);
            echo json_encode(["message" => "No se encontraron productos."]);
        }
    }

    // Método para manejar la solicitud PUT (Actualizar producto)
    public function update() {
        $data = json_decode(file_get_contents("php://input"));

        if (!empty($data->id)) {
            $this->product->id = $data->id;

            // Intenta leer el producto existente para mantener los valores que no se actualizan
            if (!$this->product->readOne()) {
                http_response_code(404);
                echo json_encode(["message" => "Producto no encontrado."]);
                return;
            }

            // Asigna solo los valores que están presentes en la solicitud
            // Los valores no presentes o null en $data mantendrán los valores existentes del producto.
            if (isset($data->codigo)) $this->product->codigo = $data->codigo;
            if (isset($data->producto)) $this->product->producto = $data->producto;
            if (isset($data->precio)) $this->product->precio = $data->precio;
            if (isset($data->cantidad)) $this->product->cantidad = $data->cantidad;
            if (isset($data->descripcion)) $this->product->descripcion = $data->descripcion; // Puede ser null o vacio
            
            // Solo actualiza si hay al menos un campo para actualizar
            if ($this->product->update()) {
                http_response_code(200);
                echo json_encode(["message" => "Producto actualizado exitosamente."]);
            } else {
                http_response_code(503);
                echo json_encode(["message" => "No se pudo actualizar el producto (posiblemente ningún campo para actualizar o error interno)."]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["message" => "ID del producto requerido para la actualización."]);
        }
    }

    // Método para manejar la solicitud DELETE (Eliminar producto)
    public function delete() {
        $data = json_decode(file_get_contents("php://input"));

        if (!empty($data->id)) {
            $this->product->id = $data->id;

            if ($this->product->delete()) {
                http_response_code(200);
                echo json_encode(["message" => "Producto eliminado exitosamente."]);
            } else {
                http_response_code(503);
                echo json_encode(["message" => "No se pudo eliminar el producto."]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Datos incompletos."]);
        }
    }
}
