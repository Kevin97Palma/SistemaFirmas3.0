CREATE DATABASE control_inversion;

USE control_inversion;

-- DROP DATABASE control_inversion;

CREATE TABLE ciudad(
idCiudad INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
nombre VARCHAR(64) NOT NULL
);

CREATE TABLE sector(
idSector INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
nombre VARCHAR(256) NOT NULL,
direccion VARCHAR(256) NOT NULL,
referencia VARCHAR(256) NOT NULL,
gpsLink VARCHAR(1024) NOT NULL
);

CREATE TABLE proyecto(
idProyecto INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
nombre VARCHAR(128) NOT NULL,
jefeObra VARCHAR(256) NOT NULL,
idCiudadFK INT NOT NULL,
idSectorFK INT NOT NULL,
FOREIGN KEY (idCiudadFK) REFERENCES ciudad (idCiudad) ON DELETE CASCADE,
FOREIGN KEY (idSectorFK) REFERENCES sector (idSector) ON DELETE CASCADE
);

/*
CREATE TABLE vivienda(
idVivienda INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
nombre VARCHAR(128) NOT NULL,
numero VARCHAR(64) NOT NULL,
estado VARCHAR(64) NOT NULL,
precio DECIMAL(20,2) NOT NULL,
factura VARCHAR(17),
idSectorFK INT NOT NULL,
idProyectoFK INT NOT NULL,
FOREIGN KEY (idSectorFK) REFERENCES sector (idSector) ON DELETE CASCADE,
FOREIGN KEY (idProyectoFK) REFERENCES proyecto (idProyecto) ON DELETE CASCADE
);
*/

-- ALTER TABLE vivienda ADD COLUMN idProyectoFK INT NOT NULL;
-- ALTER TABLE vivienda ADD CONSTRAINT vivienda_ibfk_2 FOREIGN KEY (idProyectoFK) REFERENCES proyecto (idProyecto);

CREATE TABLE `vivienda` (
  `idVivienda` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(128) NOT NULL,
  `numero` varchar(64) NOT NULL,
  `estado` varchar(64) NOT NULL,
  `precio` decimal(20,2) NOT NULL,
  `factura` varchar(17) DEFAULT NULL,
  `linkGPS` varchar(1024) DEFAULT NULL,
  `idSectorFK` int(11) NOT NULL,
  `idProyectoFK` int(11) NOT NULL,
  PRIMARY KEY (`idVivienda`),
  KEY `idSectorFK` (`idSectorFK`),
  KEY `vivienda_ibfk_2` (`idProyectoFK`),
  CONSTRAINT `vivienda_ibfk_1` FOREIGN KEY (`idSectorFK`) REFERENCES `sector` (`idSector`) ON DELETE CASCADE,
  CONSTRAINT `vivienda_ibfk_2` FOREIGN KEY (`idProyectoFK`) REFERENCES `proyecto` (`idProyecto`)
);


CREATE TABLE proveedor(
idProveedor INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
ruc VARCHAR(13) NOT NULL,
razonSocial VARCHAR(128) NOT NULL,
direccion VARCHAR(256) NOT NULL,
telefono VARCHAR(12) NOT NULL,
correo VARCHAR(64) NOT NULL
);

CREATE TABLE material(
idMaterial INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
nombre  VARCHAR(128) NOT NULL,
costo DECIMAL(20,2) NOT NULL,
trasladoPrecio DECIMAL(20,2) NOT NULL,
total DECIMAL(20,2) NOT NULL,
idProveedorFK INT NOT NULL,
FOREIGN KEY (idProveedorFK) REFERENCES proveedor (idProveedor) ON DELETE CASCADE
);

CREATE TABLE servicio(
idServicio INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
nombre  VARCHAR(128) NOT NULL,
costoHora DECIMAL(20,2) NOT NULL,
costoViaticos DECIMAL(20,2) NOT NULL,
total DECIMAL(20,2) NOT NULL,
idProveedorFK INT NOT NULL,
FOREIGN KEY (idProveedorFK) REFERENCES proveedor (idProveedor) ON DELETE CASCADE
);

CREATE TABLE costo_vivienda(
idCosto INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
idViviendaFK INT NOT NULL,
idMaterialFK INT NULL,
cantidadMaterial DECIMAL(20,2) NULL,
costoMaterial DECIMAL(20,2) NULL,
idServicioFK INT NULL,
cantidadServicio INT NULL,
costoServicio DECIMAL(20,2) NULL,
FOREIGN KEY (idViviendaFK) REFERENCES vivienda (idVivienda) ON DELETE CASCADE,
FOREIGN KEY (idMaterialFK) REFERENCES material (idMaterial) ON DELETE CASCADE,
FOREIGN KEY (idServicioFK) REFERENCES servicio (idServicio) ON DELETE CASCADE
);

CREATE TABLE persona(
idPersona INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
nombre VARCHAR(128) NOT NULL,
apellido VARCHAR(128) NOT NULL,
identificacion VARCHAR(13) NOT NULL,
telefono VARCHAR(12) NOT NULL,
correo VARCHAR(64) NOT NULL
);

CREATE TABLE usuario(
idUsuario INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
nombre VARCHAR(128) NOT NULL,
contrasena VARCHAR(256) NOT NULL,
rol VARCHAR(16) NOT NULL,
idPersonaFK INT NOT NULL,
FOREIGN KEY (idPersonaFK) REFERENCES persona (idPersona) ON DELETE CASCADE
);


DELIMITER $$
CREATE PROCEDURE registrarUsuario(IN v_user VARCHAR(128), IN v_nom VARCHAR(128), IN v_ape VARCHAR(128), 
IN v_corr VARCHAR(64), IN v_tel VARCHAR(12), IN v_contra VARCHAR(256), IN v_rol VARCHAR(16))
BEGIN
	DECLARE codigoUser INT;
    INSERT INTO persona (nombre,apellido,identificacion,telefono,correo) VALUES (v_nom,v_ape,v_user,v_tel,v_corr);
    SET codigoUser = (SELECT idPersona FROM persona WHERE nombre = v_nom AND identificacion = v_user);
    INSERT INTO usuario (nombre,contrasena,rol,idPersonaFK) VALUES (v_user,v_contra,v_rol,codigoUser);
END;

DELIMITER $$
CREATE PROCEDURE actualizarUsuario(IN v_user VARCHAR(128), IN v_nom VARCHAR(128), IN v_ape VARCHAR(128), 
IN v_corr VARCHAR(64), IN v_tel VARCHAR(12), IN v_contra VARCHAR(256), IN v_rol VARCHAR(16), IN codigo INT)
BEGIN
	UPDATE persona SET nombre = v_nom, apellido = v_ape, identificacion = v_user, correo = v_corr, telefono = v_tel WHERE idPersona = codigo;
    UPDATE usuario SET nombre = v_user, contrasena = v_contra, rol = v_rol WHERE idPersonaFK = codigo;
END;

DELIMITER $$
CREATE PROCEDURE eliminarUsuario(IN codigo INT)
BEGIN
	DELETE FROM usuario WHERE idPersona = codigo LIMIT 1;
    DELETE FROM persona WHERE idPersona = codigo LIMIT 1;
END;

