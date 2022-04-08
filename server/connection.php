<?php

    require 'config.php';

    class Connection {

        private $mysqli = null;

        public function __construct() {

            $this->mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);

            if($this->mysqli->connect_errno) {

                header('location:/logs/error_connection.html');

            } else {
                $this->mysqli->set_charset(CHARSET);
            }
        }

        public function get_connection() {
            return $this->mysqli;
        }

        public function close() {
            $this->mysqli->close();
        }

        /* Búsqueda de usuarios */
        public function check_user($user, $password) {
            $sql = "call buscar_usuario(?, ?, @id_usuario)";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->bind_param("ss", $user, $password);
            $stmt->execute();
            $sql = "select @id_usuario";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->execute();
            $stmt->bind_result($user_id);
            $stmt->fetch();
            $stmt->close();

            return $user_id;
        }
        
        public function get_table($table_name) {
            $sql = "select * from {$table_name}";
            $table = $this->mysqli->query($sql);

            return $table;
        }
        
        public function get_row_by_id($table_name, $id) {
            $sql = "select * from {$table_name} where id = ?";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
            $row = $result->fetch_assoc();
            
            return $row;
        }        

        public function get_counts_by_user($table_name, $user_id) {
            $sql = "select count(*) from {$table_name} where id_usuario = ?";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt->bind_result($counts);
            $stmt->fetch();
            $stmt->close();

            return $counts;
        }

        public function get_count_by_table($table_name) {
            $sql = "select count(*) from {$table_name}";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->execute();
            $stmt->bind_result($counts);
            $stmt->fetch();
            $stmt->close();

            return $counts;
        }

        public function get_location_by_user($user_id) {
            $sql = "select codigo from ubicacion where id_usuario = ?";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt->bind_result($location);
            $stmt->fetch();
            $stmt->close();

            return $location;
        }

        ////////////////////////////////////////////////////////////////////
        public function get_paginated_table_by_user($table_name, $user_id, $index, $rows_per_page) {
            $sql = "select * from {$table_name} where id_usuario = ? order by id asc limit ?, ?";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->bind_param("iii", $user_id, $index, $rows_per_page);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
            
            return $result;
        }
        ////////////////////////////////////////////////////////////////////        
        public function get_added_count($code) {
            $sql = "select * from producto_agregado where codigo = ? order by id asc";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->bind_param("s", $code);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
            
            return $result;
        }

        /* crear usuarios */
        public function create_user($name, $user_name, $password, $role) {
            $sql = "call crear_usuario(?, ?, ?, ?, @usuario_creado)";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->bind_param("sssi", $name, $user_name, $password, $role);
            $stmt->execute();
            $sql = "select @usuario_creado";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->execute();
            $stmt->bind_result($user_created);
            $stmt->fetch();
            $stmt->close();

            return $user_created;
        }

        /* actualizar usuario */
        public function update_user($user_id, $name, $user_name, $role) {
            $sql = "call actualizar_usuario(?, ?, ?, ?, @usuario_actualizado)";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->bind_param("issi", $user_id, $name, $user_name, $role);
            $stmt->execute();
            $sql = "select @usuario_actualizado";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->execute();
            $stmt->bind_result($user_updated);
            $stmt->fetch();
            $stmt->close();

            return $user_updated;
        }

        /* actualizar clave */
        public function update_password($user_id, $password) {
            $sql = "call actualizar_clave(?, ?, @clave_actualizada)";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->bind_param("is", $user_id, $password);
            $stmt->execute();
            $sql = "select @clave_actualizada";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->execute();
            $stmt->bind_result($password_updated);
            $stmt->fetch();
            $stmt->close();

            return $password_updated;
        }

        /* actualizar clave */
        public function delete_user($user_id) {
            $sql = "call borrar_usuario(?, @usuario_borrado)";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $sql = "select @usuario_borrado";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->execute();
            $stmt->bind_result($user_deleted);
            $stmt->fetch();
            $stmt->close();

            return $user_deleted;
        }       
                

        public function get_rol_description($rol_id) {
            $sql = "select descripcion from rol where id = ?";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->bind_param("i", $rol_id);
            $stmt->execute();
            $stmt->bind_result($rol_description);
            $stmt->fetch();
            $stmt->close();

            return $rol_description;
        }

        /* Registros de la base de datos */ //OPTIMIZAR ESTA FUNCIÓN
        public function get_number_of_codes() {
            $sql = "select count(*) from producto";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->execute();
            $stmt->bind_result($codes_count);
            $stmt->fetch();
            $stmt->close();

            return $codes_count;
        }

        /* Registros del inventario */ 
        public function get_inventory_count() {
            $sql = "select count(*) from inventario";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->execute();
            $stmt->bind_result($codes_count);
            $stmt->fetch();
            $stmt->close();

            return $codes_count;
        }

        /* Paginación de registros */
        public function get_rows_per_page($index, $number_of_rows, $table, $column) {
            $sql = "select * from {$table} order by {$column} asc limit ?, ?";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->bind_param("ii", $index, $number_of_rows);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
            
            return $result;
        }

        // verificar uso de esta función
        public function get_codes_counted() {
            $sql = "select codigo_producto, sum(cantidad) as total from inventario group by codigo_producto";
            $result = $this->mysqli->query($sql);
            return $result;
        }

        /* suma los códigos contados para calcular porcentaje */
        public function get_sum_of_all_codes() {
            $sql = "select count(distinct codigo_producto) from inventario";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->execute();
            $stmt->bind_result($all_codes);
            $stmt->fetch();
            $stmt->close();
            
            return $all_codes;
        }

        public function check_code($code) {
            $sql = "select count(*) from producto where codigo = ?";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->bind_param("s", $code);
            $stmt->execute();
            $stmt->bind_result($code_count);
            $stmt->fetch();
            $stmt->close();

            return $code_count;
        }

        public function get_code_data($code) {
            $sql = "select * from producto where codigo = ?";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->bind_param("s", $code);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
            $row = $result->fetch_assoc();
            
            return $row;
        }

        /* Guardar inventario */
        public function save_count($user, $code, $quantity, $location) {
            $sql = "call guardar_cantidad(?, ?, ?, ?, @cantidad_guardada)";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->bind_param("isds", $user, $code, $quantity, $location);
            $stmt->execute();
            $sql = "select @cantidad_guardada";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->execute();
            $stmt->bind_result($count_saved);
            $stmt->fetch();
            $stmt->close();

            return $count_saved;
        }

        /* Establecer ubicación */
        public function set_location($location, $user_id) {
            $sql = "call establecer_ubicacion(?, ?, @ubicacion_establecida)";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->bind_param("si", strtoupper($location), $user_id);
            $stmt->execute();
            $sql = "select @ubicacion_establecida";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->execute();
            $stmt->bind_result($location_added);
            $stmt->fetch();
            $stmt->close();

            return $location_added;
        }

        public function update_location($location, $user_id) {
            $sql = "call actualizar_ubicacion(?, ?, @ubicacion_actualizada)";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->bind_param("si", strtoupper($location), $user_id);
            $stmt->execute();
            $sql = "select @ubicacion_actualizada";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->execute();
            $stmt->bind_result($location_added);
            $stmt->fetch();
            $stmt->close();

            return $location_added;
        }

        /* Agregar producto al inventario */
        public function add_product($user, $code, $quantity, $description, $location) {
            $sql = "call agregar_producto(?, ?, ?, ?, ?, @producto_agregado)";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->bind_param("isdss", $user, $code, $quantity, $description, $location);
            $stmt->execute();
            $sql = "select @producto_agregado";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->execute();
            $stmt->bind_result($count_saved);
            $stmt->fetch();
            $stmt->close();

            return $count_saved;
        }

        /* contar cantidades guardadas */
        public function get_sum_of_code($code) {
            $sql = "select sum(cantidad) from inventario where codigo_producto = ?";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->bind_param("s", $code);
            $stmt->execute();
            $stmt->bind_result($code_sum);
            $stmt->fetch();
            $stmt->close();

            return $code_sum;
        }        

        /* conteo por producto en inventario */
        public function get_count_by_code($code) {
            $sql = "select * from inventario where codigo_producto = ?";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->bind_param("s", $code);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
            
            return $result;
        }

        /* conteo por producto y usuario en inventario */
        public function get_count_by_code_and_user($code, $user_id) {
            $sql = "select * from inventario where codigo_producto = ? and id_usuario = ?";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->bind_param("si", $code, $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
            
            return $result;
        }
        
        public function get_add_by_code_and_user($code, $user_id) {
            $sql = "select * from producto_agregado where codigo = ? and id_usuario = ?";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->bind_param("si", $code, $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
            
            return $result;
        }

        /* Búsqueda de códigos similares */
        public function search_similar_codes($subcode) {
            $sql = "select codigo, marca, descripcion from producto where codigo like ?";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->bind_param("s", $subcode);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
            
            return $result;
        }

        /* Codigos de artículos */
        public function get_codes() {
            $sql = "select * from producto order by linea asc";
            $result = $this->mysqli->query($sql);
            return $result;
        }
        
        /* Obtener nombre de usuario */
        public function get_user_name($user_id) {
            $sql = "select nombre from persona where id = ?";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt->bind_result($user_name);
            $stmt->fetch();
            $stmt->close();

            return $user_name;
        }

        public function get_user_id($user_name, $password) {
            $sql = "select id from persona where usuario = ? and clave = ?";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->bind_param("ss", $user_name, hash('sha256', $password));
            $stmt->execute();
            $stmt->bind_result($user_id);
            $stmt->fetch();
            $stmt->close();

            return $user_id;
        }

        /* limpiar tablas de la base de datos */
        public function clean_tables() {
            $sql = "call limpiar_tablas(@tablas_limpias)";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->execute();
            $sql = "select @tablas_limpias";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->execute();
            $stmt->bind_result($clean_tables);
            $stmt->fetch();
            $stmt->close();

            return $clean_tables;
        }

        /* Cargar datos de productos a la base de datos */
        public function load_product($id,
                                     $code,
                                     $description,
                                     $type,
                                     $brand,
                                     $reference,
                                     $warehouse,
                                     $to_deliver,
                                     $actual_stock,
                                     $unit,
                                     $price) {

            $sql = "call guardar_producto(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->bind_param("issssssddsd",
                              $id,              
                              $code,
                              $description,
                              $type,
                              $brand,
                              $reference,
                              $warehouse,
                              $to_deliver,
                              $actual_stock,
                              $unit,
                              $price);
            $stmt->execute();
        }

        /* obtener último conteo por usuario */
        public function get_last_code_counted_by_user($user_id) {
            $sql = "select codigo_producto, cantidad from inventario where id_usuario = ? order by id desc limit 1";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
            $row = $result->fetch_assoc();
            
            return $row;
        }

        /* obtener último conteo por usuario */
        public function get_last_add_by_user($user_id) {
            $sql = "select * from producto_agregado where id_usuario = ? order by id desc limit 1";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
            $row = $result->fetch_assoc();
            
            return $row;
        }

        /* verificar si un conteo corresponde a un usuario */
        public function check_count_by_user($id_count, $user_id) {
            $sql = "select count(*) from inventario where id = ? and id_usuario = ?";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->bind_param("ii", $id_count, $user_id);
            $stmt->execute();
            $stmt->bind_result($count_verified);
            $stmt->fetch();
            $stmt->close();

            return $count_verified;
        }

        /* verificar si un codigo agregado corresponde a un usuario */
        public function check_add_by_user($id_add, $user_id) {
            $sql = "select count(*) from producto_agregado where id = ? and id_usuario = ?";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->bind_param("ii", $id_add, $user_id);
            $stmt->execute();
            $stmt->bind_result($add_verified);
            $stmt->fetch();
            $stmt->close();

            return $add_verified;
        }

        /* eliminar conteo del inventario */
        public function delete_count($id_count) {
            $sql = "call eliminar_conteo(?, @conteo_eliminado)";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->bind_param("i", $id_count);
            $stmt->execute();
            $sql = "select @conteo_eliminado";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->execute();
            $stmt->bind_result($count_deleted);
            $stmt->fetch();
            $stmt->close();

            return $count_deleted;
        }

        /* eliminar conteo del inventario */
        public function delete_add($id_add) {
            $sql = "call eliminar_agregado(?, @agregado_eliminado)";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->bind_param("i", $id_add);
            $stmt->execute();
            $sql = "select @agregado_eliminado";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->execute();
            $stmt->bind_result($add_deleted);
            $stmt->fetch();
            $stmt->close();

            return $add_deleted;
        }        

        /* actualizar un conteo */
        public function update_count($user_id, $id_count, $quantity, $location) {
            $sql = "call actualizar_conteo(?, ?, ?, ?, @conteo_actualizado)";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->bind_param("iids", $user_id, $id_count, $quantity, $location);
            $stmt->execute();
            $sql = "select @conteo_actualizado";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->execute();
            $stmt->bind_result($count_updated);
            $stmt->fetch();
            $stmt->close();

            return $count_updated;
        }

        /* actualizar un conteo */
        public function update_add($user_id, $id_add, $code, $description, $quantity, $location) {
            $sql = "call actualizar_agregado(?, ?, ?, ?, ?, ?, @agregado_actualizado)";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->bind_param("iissds", $user_id, $id_add, $code, $description, $quantity, $location);
            $stmt->execute();
            $sql = "select @agregado_actualizado";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->execute();
            $stmt->bind_result($add_updated);
            $stmt->fetch();
            $stmt->close();

            return $add_updated;
        }
    }    
?>