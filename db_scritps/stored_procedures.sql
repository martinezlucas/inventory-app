use bodega;

drop procedure if exists buscar_usuario;

delimiter //
create procedure buscar_usuario(in var_usuario varchar(64), 
                                in var_clave char(64), 
								out id_usuario int)

begin

	declare usuario_encontrado int default 0;
    
    select count(*)
    into usuario_encontrado
    from persona
    where usuario = var_usuario
    and clave = sha2(var_clave, 256);
    
    if usuario_encontrado != 0 then
		select id 
        into id_usuario
        from persona
		where usuario = var_usuario
		and clave = sha2(var_clave, 256);
	else
		set id_usuario = 0;
	
    end if;

end //
delimiter ;

/* Guardar cantidad */
drop procedure if exists guardar_cantidad;

delimiter //
create procedure guardar_cantidad(in var_usuario varchar(64), 
                                  in var_codigo_producto varchar(64),
                                  in var_cantidad float,
                                  in var_ubicacion varchar(32),
								  out cantidad_guardada tinyint)

begin

	declare sql_error tinyint default false;
    declare continue handler for sqlexception
		set sql_error = true;
        
	start transaction;
    
		insert into inventario(codigo_producto, cantidad, ubicacion, id_usuario, registrado)
        values(var_codigo_producto, var_cantidad, var_ubicacion, var_usuario, current_timestamp());
    
    if sql_error = false then
		commit;
        set cantidad_guardada = 1;
	else
		rollback;
        set cantidad_guardada = 0;
	end if;  
    
end //
delimiter ;

/* Limpieza de tablas */
drop procedure if exists limpiar_tablas;

delimiter //
create procedure limpiar_tablas(out tablas_limpias tinyint)

begin

	declare sql_error tinyint default false;
    declare continue handler for sqlexception
		set sql_error = true;
        
	start transaction;
		
        set @@foreign_key_checks = 0;
        
		truncate table producto;
        truncate table inventario;
        truncate table producto_agregado;
        
        set @@foreign_key_checks = 1;
    
    if sql_error = false then
		commit;
        set tablas_limpias = 1;
	else
		rollback;
        set tablas_limpias = 0;
	end if;  
    
end //
delimiter ;

/* Guardar producto */
drop procedure if exists guardar_producto;

delimiter //
create procedure guardar_producto(in var_linea int,              
								  in var_codigo varchar(64),
								  in var_descripcion varchar(255),
								  in var_type varchar(128),
								  in var_marcar varchar(128),
								  in var_referencia varchar(128),
								  in var_almacen varchar(64),
								  in var_comprometido float,
								  in var_stock_actual float,
								  in var_unidad varchar(64),
								  in var_costo_unitario float)

begin
	
    insert into producto(linea, 
						 codigo, 
                         descripcion, 
                         tipo, 
                         marca, 
                         referencia, 
                         almacen, 
                         comprometido, 
                         stock_actual, 
                         unidad, 
                         costo_unitario, 
                         registrado) 
	values(var_linea,              
		   var_codigo,
		   var_descripcion,
		   var_type,
		   var_marcar,
		   var_referencia,
		   var_almacen,
		   var_comprometido,
		   var_stock_actual,
		   var_unidad,
		   var_costo_unitario, 
           current_timestamp());
    		
end //
delimiter ;

/* Crear usuario */
drop procedure if exists crear_usuario;
delimiter //
create procedure crear_usuario(in var_nombre varchar(128),
							   in var_usuario varchar(64),
                               in var_clave varchar(255),
                               in var_rol int,
							   out usuario_creado tinyint)

begin

	declare sql_error tinyint default false;
    declare continue handler for sqlexception
		set sql_error = true;
        
	start transaction;
		
        insert into persona(nombre, usuario, clave, rol, registrado)
		values
		(var_nombre, var_usuario, sha2(var_clave, 256), var_rol, current_timestamp());
    
    if sql_error = false then
		commit;
        set usuario_creado = 1;
	else
		rollback;
        set usuario_creado = 0;
	end if;  
    
end //
delimiter ;

/* Actualizar usuario */
drop procedure if exists actualizar_usuario;
delimiter //
create procedure actualizar_usuario(in var_id int,
                                    in var_nombre varchar(128),
							        in var_usuario varchar(64),
                                    in var_rol int,
							        out usuario_actualizado tinyint)

begin

	declare sql_error tinyint default false;
    declare continue handler for sqlexception
		set sql_error = true;
        
	start transaction;
		
        update persona
		set
		nombre = var_nombre,
        usuario = var_usuario,
        rol = var_rol,
        actualizado = current_timestamp()
        
        where id = var_id;
    
    if sql_error = false then
		commit;
        set usuario_actualizado = 1;
	else
		rollback;
        set usuario_actualizado = 0;
	end if;  
    
end //
delimiter ;

/* Actualizar clave */
drop procedure if exists actualizar_clave;
delimiter //
create procedure actualizar_clave(in var_id int,
                                  in var_clave varchar(255),
							      out clave_actualizada tinyint)

begin

	declare sql_error tinyint default false;
    declare continue handler for sqlexception
		set sql_error = true;
        
	start transaction;
		
        update persona
		set
		clave = sha2(var_clave, 256),
        actualizado = current_timestamp()
        
        where id = var_id;
    
    if sql_error = false then
		commit;
        set clave_actualizada = 1;
	else
		rollback;
        set clave_actualizada = 0;
	end if;  
    
end //
delimiter ;

/* Borrar usuario */
drop procedure if exists borrar_usuario;
delimiter //
create procedure borrar_usuario(in var_id int,
							    out usuario_borrado tinyint)

begin

	declare sql_error tinyint default false;
    declare continue handler for sqlexception
		set sql_error = true;
        
	start transaction;
		
        delete from persona where id = var_id;
    
    if sql_error = false then
		commit;
        set usuario_borrado = 1;
	else
		rollback;
        set usuario_borrado = 0;
	end if;  
    
end //
delimiter ;

/* Agregar producto */
drop procedure if exists agregar_producto;
delimiter //
create procedure agregar_producto(in var_id int,
								  in var_codigo varchar(64),
                                  in var_cantidad float,
                                  in var_descripcion varchar(255),
                                  in var_ubicacion varchar(255),
							      out producto_agregado tinyint)

begin

	declare sql_error tinyint default false;
    declare continue handler for sqlexception
		set sql_error = true;
        
	start transaction;
		
        insert into producto_agregado(codigo, descripcion, cantidad, ubicacion, id_usuario, registrado)
        values(var_codigo, var_descripcion, var_cantidad, var_ubicacion, var_id, current_timestamp());
    
    if sql_error = false then
		commit;
        set producto_agregado = 1;
	else
		rollback;
        set producto_agregado = 0;
	end if;  
    
end //
delimiter ;

/* eliminar conteo */
drop procedure if exists eliminar_conteo;
delimiter //
create procedure eliminar_conteo(in var_id int,
							     out conteo_eliminado tinyint)

begin

	declare sql_error tinyint default false;
    declare continue handler for sqlexception
		set sql_error = true;
        
	start transaction;
		
        delete from inventario where id = var_id;
    
    if sql_error = false then
		commit;
        set conteo_eliminado = 1;
	else
		rollback;
        set conteo_eliminado = 0;
	end if;  
    
end //
delimiter ;

/* eliminar producto agregado */
drop procedure if exists eliminar_agregado;
delimiter //
create procedure eliminar_agregado(in var_id int,
							       out agregado_eliminado tinyint)

begin

	declare sql_error tinyint default false;
    declare continue handler for sqlexception
		set sql_error = true;
        
	start transaction;
		
        delete from producto_agregado where id = var_id;
    
    if sql_error = false then
		commit;
        set agregado_eliminado = 1;
	else
		rollback;
        set agregado_eliminado = 0;
	end if;  
    
end //
delimiter ;

/* actualizar conteo */
drop procedure if exists actualizar_conteo;
delimiter //
create procedure actualizar_conteo(in var_id_usuario int,
								   in var_id int,
								   in var_cantidad float,
                                   in var_ubicacion varchar(32),
							       out conteo_actualizado tinyint)

begin

	declare sql_error tinyint default false;
    declare continue handler for sqlexception
		set sql_error = true;
        
	start transaction;
		
        update inventario set cantidad = var_cantidad, ubicacion = var_ubicacion, modif_por = var_id_usuario, modificado = current_timestamp()
        where id = var_id;
    
    if sql_error = false then
		commit;
        set conteo_actualizado = 1;
	else
		rollback;
        set conteo_actualizado = 0;
	end if;  
    
end //
delimiter ;

/* Establecer ubicación */
drop procedure if exists establecer_ubicacion;

delimiter //
create procedure establecer_ubicacion(in var_codigo varchar(32),
								      out ubicacion_establecida tinyint)

begin
	
    declare ubicacion_encontrada tinyint default 0;
	declare sql_error tinyint default false;
    declare continue handler for sqlexception
		set sql_error = true;
        
	start transaction;
    
		select count(*) into ubicacion_encontrada from tmp_ubicacion;
        
        if ubicacion_encontrada = 0 then
			insert into tmp_ubicacion(codigo) values(var_codigo);
		else
			update tmp_ubicacion set codigo = var_codigo;
		end if;
    
    if sql_error = false then
		commit;
        set ubicacion_establecida = 1;
	else
		rollback;
        set ubicacion_establecida = 0;
	end if;  
    
end //
delimiter ;

/* actualizar código agregado */
drop procedure if exists actualizar_agregado;
delimiter //
create procedure actualizar_agregado(in var_id_usuario int,
								     in var_id int,
								     in var_codigo varchar(64),
                                     in var_descripcion varchar(255),
                                     in var_cantidad float,
                                     in var_ubicacion varchar(32),
							         out agregado_actualizado tinyint)

begin

	declare sql_error tinyint default false;
    declare continue handler for sqlexception
		set sql_error = true;
        
	start transaction;
		
        update producto_agregado set codigo = var_codigo,
									 descripcion = var_descripcion,
									 cantidad = var_cantidad, 
                                     ubicacion = var_ubicacion, 
                                     modif_por = var_id_usuario,
                                     modificado = current_timestamp()
        where id = var_id;
    
    if sql_error = false then
		commit;
        set agregado_actualizado = 1;
	else
		rollback;
        set agregado_actualizado = 0;
	end if;  
    
end //
delimiter ;