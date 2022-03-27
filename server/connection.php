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

        /* obtener tabla de usuario */
        public function get_users() {
            $sql = "select * from persona";
            $result = $this->mysqli->query($sql);
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

        /* Obtener datos de usuario */
        public function get_user_data($user_id) {
            $sql = "select * from persona where id = ?";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
            $row = $result->fetch_assoc();
            
            return $row;
        }

        public function get_roles() {
            $sql = "select * from rol";
            $result = $this->mysqli->query($sql);
            return $result;
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
        public function set_location($location) {
            $sql = "call establecer_ubicacion(?, @ubicacion_establecida)";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->bind_param("s", $location);
            $stmt->execute();
            $sql = "select @ubicacion_establecida";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->execute();
            $stmt->bind_result($location_added);
            $stmt->fetch();
            $stmt->close();

            return $location_added;
        }

        /* Obtener ubicación */
        public function get_location() {
            $sql = "select * from tmp_ubicacion";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
            $row = $result->fetch_assoc();
            
            return $row;
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

        /* conteo por usuario en inventario 
        public function get_count_by_user($user_id) {
            $sql = "select * from inventario where id_usuario = ?";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
            
            return $result;
        } */

        /* conteo por usuario en inventario con paginación */
        public function get_rows_per_user($user_id, $index, $rows_per_page) {
            $sql = "select * from inventario where id_usuario = ? order by id asc limit ?, ?";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->bind_param("iii", $user_id, $index, $rows_per_page);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
            
            return $result;
        }

        public function get_user_codes_count($user_id) {
            $sql = "select count(*) from inventario where id_usuario = ?";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt->bind_result($codes_count);
            $stmt->fetch();
            $stmt->close();

            return $codes_count;
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

        /* códigos agregados por usuario en inventario */
        public function get_adds_by_user($user_id) {
            $sql = "select * from producto_agregado where id_usuario = ?";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->bind_param("i", $user_id);
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

        /* códigos agregados general */
        public function get_codes_added() {
            $sql = "select * from producto_agregado";
            $result = $this->mysqli->query($sql);

            return $result;
        }
        
        /* Obtener nombre de usuario */
        public function get_user_name($user_id) {
            $sql = "select nombre from persona where id = ?";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->bind_param("s", $user_id);
            $stmt->execute();
            $stmt->bind_result($user_name);
            $stmt->fetch();
            $stmt->close();

            return $user_name;
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

        /* obtener conteo individual */
        public function get_count_data($id) {
            $sql = "select * from inventario where id = ?";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
            $row = $result->fetch_assoc();
            
            return $row;
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
    }    
?>