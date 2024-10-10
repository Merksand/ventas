DROP DATABASE IF EXISTS db_sistema_herencia;
CREATE DATABASE db_sistema_herencia;
USE db_sistema_herencia;

CREATE TABLE tb_roles (
    id_rol INT AUTO_INCREMENT PRIMARY KEY,
    nombre_rol VARCHAR(255) NOT NULL,
    fyh_creacion DATETIME NOT NULL,
    fyh_actualizacion DATETIME NOT NULL
);

CREATE TABLE tb_persona (
    id_persona INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    apellido_paterno VARCHAR(255) NOT NULL,
    apellido_materno VARCHAR(255) DEFAULT NULL,
    celular VARCHAR(50) NOT NULL,
	email VARCHAR(50) DEFAULT NULL,
	direccion VARCHAR(255) NOT NULL,
    fyh_creacion DATETIME DEFAULT current_timestamp,
    fyh_actualizacion DATETIME DEFAULT current_timestamp on update current_timestamp
);

CREATE TABLE tb_proveedores (
    id_proveedor INT AUTO_INCREMENT PRIMARY KEY,
    id_persona INT NOT NULL,
    nombre_empresa VARCHAR(255) NOT NULL,
    nit_empresa VARCHAR(50) NOT NULL,
    FOREIGN KEY (id_persona) REFERENCES tb_persona(id_persona)
);

CREATE TABLE tb_usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    id_persona INT NOT NULL,
    password_user TEXT NOT NULL,
    token VARCHAR(100) NOT NULL,
    id_rol INT NOT NULL,
    FOREIGN KEY (id_persona) REFERENCES tb_persona(id_persona),
    FOREIGN KEY (id_rol) REFERENCES tb_roles(id_rol)
);

CREATE TABLE tb_clientes (
    id_clientes INT AUTO_INCREMENT PRIMARY KEY,
    id_persona INT NOT NULL,
    nit_ci VARCHAR(50) NOT NULL,
    FOREIGN KEY (id_persona) REFERENCES tb_persona(id_persona)
);

CREATE TABLE tb_categorias (
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    nombre_categoria VARCHAR(255) NOT NULL,
    fyh_creacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    fyh_actualizacion DATETIME DEFAULT NULL
);

CREATE TABLE tb_productos (
    id_producto INT AUTO_INCREMENT PRIMARY KEY,
    codigo_producto INT NOT NULL,
    nombre_producto VARCHAR(255) NOT NULL,
    descripcion VARCHAR(255) NOT NULL,
    precio_venta DECIMAL(10, 2) NOT NULL,
    imagen TEXT DEFAULT NULL
);

CREATE TABLE tb_almacen (
    id_almacen INT AUTO_INCREMENT PRIMARY KEY,
    id_producto INT NOT NULL,
    stock INT NOT NULL,
    stock_minimo INT DEFAULT NULL,
    stock_maximo INT DEFAULT NULL,
    fecha_ingreso DATE NOT NULL,
    id_categoria INT NOT NULL,
    FOREIGN KEY (id_producto) REFERENCES tb_productos(id_producto),
    FOREIGN KEY (id_categoria) REFERENCES tb_categorias(id_categoria)
);

CREATE TABLE tb_compras (
    id_compra INT AUTO_INCREMENT PRIMARY KEY,
    id_proveedor INT NOT NULL,
    id_producto INT NOT NULL,
    cantidad INT NOT NULL,
    precio_compra DECIMAL(10, 2) NOT NULL,
    fecha_compra DATE NOT NULL,
    id_usuario INT NOT NULL,
    FOREIGN KEY (id_proveedor) REFERENCES tb_proveedores(id_proveedor),
    FOREIGN KEY (id_producto) REFERENCES tb_productos(id_producto),
    FOREIGN KEY (id_usuario) REFERENCES tb_usuarios(id_usuario)
);

CREATE TABLE tb_ventas (
    id_venta INT AUTO_INCREMENT PRIMARY KEY,
    id_persona INT NOT NULL,
    id_usuario INT NOT NULL,
    id_tipo_operacion INT NOT NULL,
    id_caja INT NOT NULL,
    cantidad INT NOT NULL,
    efectivo DECIMAL(10, 2) NOT NULL,
    fyh_creacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_persona) REFERENCES tb_persona(id_persona),
    FOREIGN KEY (id_usuario) REFERENCES tb_usuarios(id_usuario)
);

CREATE TABLE DETALLE_COMPRA (
    id_detalle_compra INT AUTO_INCREMENT PRIMARY KEY,
    id_producto INT NOT NULL,
    id_compra INT NOT NULL,
    cantidad INT NOT NULL,
    precio_compra DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (id_producto) REFERENCES tb_productos(id_producto),
    FOREIGN KEY (id_compra) REFERENCES tb_compras(id_compra)
);

-- Inserciones
INSERT INTO tb_roles (nombre_rol, fyh_creacion, fyh_actualizacion) VALUES
('Administrador', '2024-10-04 21:16:09', '2024-10-04 21:16:09');

INSERT INTO tb_persona (nombre, apellido_paterno, apellido_materno, celular, email, direccion) VALUES
('DANILO', 'CUELLAR', NULL, '75657007', 'ELECTROMUNDO@gmail.com', 'Av. del Maestro S/N'),
('MARIA', 'QUISPE', 'MONTES', '74664754', 'maria@gmail.com', 'Av. Panamericana Nro 540'),
('JUAN', 'PÉREZ', 'GARCÍA', '12345678', 'juan.perez@gmail.com', 'Calle Falsa 123'),
('LAURA', 'GÓMEZ', NULL, '87654321', 'laura.gomez@gmail.com', 'Calle Real 456'),
('JOSÉ', 'LÓPEZ', 'HERRERA', '23456789', 'jose.lopez@gmail.com', 'Av. Libertador 789'),
('ANA', 'MARTÍNEZ', 'CASTAÑEDA', '34567890', 'ana.martinez@gmail.com', 'Calle 7 de Junio 321');

INSERT INTO tb_proveedores (id_persona, nombre_empresa, nit_empresa) VALUES
(3, 'STAR', '75657007'),
(4, 'BBC', '74664754');

INSERT INTO tb_usuarios (id_persona, password_user, token, id_rol) VALUES
(1, 'admin', '', 1),
(2, 'admin', '', 1);

INSERT INTO tb_clientes (id_persona, nit_ci) VALUES
(5, '75657007'),
(6, '74664754');

INSERT INTO tb_productos (codigo_producto, nombre_producto, descripcion, precio_venta, imagen) VALUES
(001, 'LICUADORA OSTER', 'LICUADORA OSTER 350 WATTS', '400.00', 'licuadora1.jpg'),
(002, 'LICUADORA MAGEFESA', 'LICUADORA MAGEFESA 300 WATTS', '250.00', 'licuadora2.jpg');

INSERT INTO tb_categorias (nombre_categoria, fyh_creacion, fyh_actualizacion) VALUES
('LICUADORAS', '2024-10-04 21:16:09', '2024-10-04 17:17:59'),
('BATIDORAS', '2024-10-04 21:16:09', '2024-10-04 17:18:10'),
('PROCESADORAS', '2024-10-04 17:18:21', NULL);

INSERT INTO tb_almacen (id_producto, stock, stock_minimo, stock_maximo, fecha_ingreso, id_categoria) VALUES
(1, 9, 3, 15, '2024-10-04', 1),
(2, 10, 3, 15, '2024-10-04', 1);

INSERT INTO tb_compras (id_proveedor, id_producto, cantidad, precio_compra, fecha_compra, id_usuario) VALUES
(1, 1, 12, '350.00', '2024-10-05', 1);

INSERT INTO tb_ventas (id_persona, id_usuario, id_tipo_operacion, id_caja, cantidad, efectivo, fyh_creacion) VALUES
(1, 2, 1, 1, 1, 70.00, '2024-10-04 21:16:10'),
(1, 1, 1, 1, 1, 400.00, '2024-10-05 20:07:10');

INSERT INTO DETALLE_COMPRA (id_producto, id_compra, cantidad, precio_compra) VALUES
(1, 1, 2, 350.00),
(2, 1, 3, 250.00);

SELECT distinct * FROM tb_persona p 
JOIN tb_clientes pr ON p.id_persona = pr.id_persona;

