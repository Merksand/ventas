
DROP DATABASE IF EXISTS electrotech_es;
CREATE DATABASE electrotech_es;
USE electrotech_es;
SET sql_mode='';

CREATE TABLE usuario (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50),
    apellido VARCHAR(50),
    nombre_usuario VARCHAR(50),
    correo VARCHAR(255),
    password VARCHAR(60),
    activo BOOLEAN NOT NULL DEFAULT 1,
    es_admin BOOLEAN NOT NULL DEFAULT 0,
    creado_en DATETIME
);

INSERT INTO usuario (nombre, apellido, correo, contraseña, activo, es_admin, creado_en)
VALUES ("Administrador", "", "admin", "90b9aa7e25f80cf4f64e990b78a9fc5ebd6cecad", 1, 1, NOW());

CREATE TABLE categoria (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50),
    creado_en DATETIME
);



CREATE TABLE producto (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    imagen VARCHAR(255),
    codigo_barras VARCHAR(50),
    nombre VARCHAR(50),
    descripcion TEXT,
    inventario_minimo INT DEFAULT 10,
    precio_compra FLOAT,
    precio_venta FLOAT,
    cantidad VARCHAR(255),
    id_usuario INT,
    id_categoria INT,
    creado_en DATETIME,
    activo BOOLEAN DEFAULT 1,
    FOREIGN KEY (id_categoria) REFERENCES categoria(id),
    FOREIGN KEY (id_usuario) REFERENCES usuario(id)
);

/*
tipo_persona:
1.- Cliente
2.- Proveedor
*/
CREATE TABLE persona (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    imagen VARCHAR(255),
    nombre VARCHAR(255),
    apellido VARCHAR(50),
    empresa VARCHAR(50),
    direccion1 VARCHAR(50),
    telefono1 VARCHAR(50),
    correo1 VARCHAR(50),
    tipo INT,
    creado_en DATETIME
);

CREATE TABLE tipo_operacion (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50)
);

INSERT INTO tipo_operacion (nombre) VALUES ("entrada");
INSERT INTO tipo_operacion (nombre) VALUES ("salida");

CREATE TABLE caja (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    creado_en DATETIME
);

CREATE TABLE venta (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_persona INT,
    id_usuario INT,
    id_tipo_operacion INT DEFAULT 2,
    id_caja INT,
    total DOUBLE,
    FOREIGN KEY (id_caja) REFERENCES caja(id),
    FOREIGN KEY (id_tipo_operacion) REFERENCES tipo_operacion(id),
    FOREIGN KEY (id_usuario) REFERENCES usuario(id),
    FOREIGN KEY (id_persona) REFERENCES persona(id),
    creado_en DATETIME
);

CREATE TABLE operacion (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_producto INT,
    cantidad FLOAT,
    id_tipo_operacion INT,
    id_venta INT,
    creado_en DATETIME,
    FOREIGN KEY (id_producto) REFERENCES producto(id),
    FOREIGN KEY (id_tipo_operacion) REFERENCES tipo_operacion(id),
    FOREIGN KEY (id_venta) REFERENCES venta(id)
);


use inventiolite;
select * from user;
select * from product;

select * from category;
select * from operation;
select * from operation_type;
select * from box;
select * from sell;