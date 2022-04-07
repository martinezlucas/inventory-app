create database bodega character set utf8mb4;
use bodega;

/* Tabla de roles */
create table rol(
	id int not null,
    descripcion varchar(14) not null,
    
    constraint pk_rol
    primary key(id)
)
engine = InnoDB;

insert into rol(id, descripcion)
values
(1, 'administrador'),
(2, 'inventorista');

/* Tabla de usuarios */
create table persona(
	id int not null auto_increment,
    nombre varchar(128) not null,
    usuario varchar(64) not null unique,
    clave char(64) not null,
    rol int not null,
    registrado datetime not null,
    actualizado datetime,
    
    constraint pk_usuario
    primary key(id)
)
engine = InnoDB;

insert into persona(nombre, usuario, clave, rol, registrado) 
values ('Usuario administrador', 'admin', sha2('admin', 256), 1, current_timestamp());

create table producto(
	linea int not null,
    codigo varchar(64) not null,
    descripcion varchar(255) not null,
    tipo varchar(128),
    marca varchar(128),
    referencia varchar(128),
    almacen varchar(64),
    comprometido float,
    stock_actual float,
    unidad varchar(64),
    costo_unitario float,
    registrado datetime,
    
    constraint pk_producto
    primary key(codigo)
)
engine = InnoDB;

/* agregar fecha de modificación */
create table inventario(
	id int not null auto_increment,
    codigo_producto varchar(64) not null,
    cantidad float not null,
    ubicacion varchar(32),
    id_usuario int not null,
    registrado datetime not null,    
    modif_por int,
    modificado datetime,
    
    constraint pk_inventario
    primary key(id),
    
    constraint fk_inventario_producto
    foreign key(codigo_producto)
    references producto(codigo),
        
    constraint fk_inventario_persona_1
    foreign key(id_usuario)
    references persona(id),
    
    constraint fk_inventario_persona_2
    foreign key(modif_por)
    references persona(id)
)
engine = InnoDB;

create table producto_agregado(
	id int not null auto_increment,
    codigo varchar(64) not null,
    descripcion varchar(255) not null,
    cantidad float not null,
    ubicacion varchar(32),
    id_usuario int not null,
    registrado datetime not null,
    modif_por int,
    modificado datetime,
    
    constraint pk_producto_agregado
    primary key(id),
    
    constraint fk_producto_agregado_persona_1
    foreign key(id_usuario)
    references persona(id),
    
    constraint fk_producto_agregado_persona_2
    foreign key(modif_por)
    references persona(id)
)
engine = InnoDB;

/* Ubicación temporar */
create table ubicacion(
	codigo varchar(32) not null,
    id_usuario int not null
)
engine = InnoDB;

insert into ubicacion(codigo, id_usuario) values('SIN ESTABLECER', 1);