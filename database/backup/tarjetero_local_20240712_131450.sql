-- Valentina Studio --
-- MySQL dump --
-- ---------------------------------------------------------


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
-- ---------------------------------------------------------


-- CREATE TABLE "cat_conceptos_gastos" -------------------------
CREATE TABLE `cat_conceptos_gastos`( 
	`concepto_gasto_id` VarChar( 12 ) CHARACTER SET armscii8 COLLATE armscii8_general_ci NOT NULL,
	`nombre` VarChar( 160 ) CHARACTER SET armscii8 COLLATE armscii8_general_ci NOT NULL,
	`clave` VarChar( 30 ) CHARACTER SET armscii8 COLLATE armscii8_general_ci NOT NULL,
	`status` Int( 0 ) NOT NULL,
	`registro_fecha` Timestamp NOT NULL,
	`actualizacion_fecha` Timestamp NOT NULL,
	`cliente_id` VarChar( 12 ) CHARACTER SET armscii8 COLLATE armscii8_general_ci NOT NULL,
	`descripcion` VarChar( 160 ) CHARACTER SET armscii8 COLLATE armscii8_general_ci NOT NULL,
	PRIMARY KEY ( `concepto_gasto_id` ) )
CHARACTER SET = armscii8
COLLATE = armscii8_general_ci
ENGINE = InnoDB;
-- -------------------------------------------------------------


-- CREATE TABLE "cat_marcas_tarjetas" --------------------------
CREATE TABLE `cat_marcas_tarjetas`( 
	`marca_tarjeta_id` VarChar( 12 ) CHARACTER SET armscii8 COLLATE armscii8_general_ci NOT NULL,
	`nombre` VarChar( 150 ) CHARACTER SET armscii8 COLLATE armscii8_general_ci NOT NULL,
	`descripcion` VarChar( 180 ) CHARACTER SET armscii8 COLLATE armscii8_general_ci NOT NULL,
	`folio` Int( 0 ) NOT NULL,
	`icono` Text CHARACTER SET armscii8 COLLATE armscii8_general_ci NOT NULL,
	`status` Int( 0 ) NOT NULL,
	`clave` VarChar( 20 ) CHARACTER SET armscii8 COLLATE armscii8_general_ci NOT NULL,
	PRIMARY KEY ( `marca_tarjeta_id` ) )
CHARACTER SET = armscii8
COLLATE = armscii8_general_ci
ENGINE = InnoDB;
-- -------------------------------------------------------------


-- CREATE TABLE "clientes" -------------------------------------
CREATE TABLE `clientes`( 
	`cliente_id` VarChar( 12 ) CHARACTER SET armscii8 COLLATE armscii8_general_ci NOT NULL,
	`nombre` VarChar( 80 ) CHARACTER SET armscii8 COLLATE armscii8_general_ci NOT NULL,
	`apellidos` VarChar( 100 ) CHARACTER SET armscii8 COLLATE armscii8_general_ci NOT NULL,
	`email` VarChar( 120 ) CHARACTER SET armscii8 COLLATE armscii8_general_ci NOT NULL,
	`sexo` VarChar( 20 ) CHARACTER SET armscii8 COLLATE armscii8_general_ci NULL DEFAULT NULL COMMENT '1 Hombre
2 Mujer',
	`fecha_nacimiento` Date NULL DEFAULT NULL,
	`status` Int( 0 ) NOT NULL,
	`actualizacion_fecha` Timestamp NULL DEFAULT NULL,
	`registro_fecha` Timestamp NOT NULL,
	PRIMARY KEY ( `cliente_id` ) )
CHARACTER SET = armscii8
COLLATE = armscii8_general_ci
ENGINE = InnoDB;
-- -------------------------------------------------------------


-- CREATE TABLE "clientes_perfil" ------------------------------
CREATE TABLE `clientes_perfil`( 
	`cliente_perfil_id` VarChar( 12 ) CHARACTER SET armscii8 COLLATE armscii8_general_ci NOT NULL,
	`cliente_id` VarChar( 12 ) CHARACTER SET armscii8 COLLATE armscii8_general_ci NOT NULL,
	`usuario` VarChar( 50 ) CHARACTER SET armscii8 COLLATE armscii8_general_ci NOT NULL,
	`password` VarChar( 50 ) CHARACTER SET armscii8 COLLATE armscii8_general_ci NOT NULL,
	`ultimo_acceso` Timestamp NULL DEFAULT NULL,
	`token_sesion` Text CHARACTER SET armscii8 COLLATE armscii8_general_ci NULL DEFAULT NULL,
	`registro_fecha` Timestamp NOT NULL,
	`actualizacion_fecha` Timestamp NULL DEFAULT NULL,
	`pin` VarChar( 20 ) CHARACTER SET armscii8 COLLATE armscii8_general_ci NOT NULL,
	`token_fecha` Timestamp NULL DEFAULT NULL,
	`foto` Text CHARACTER SET armscii8 COLLATE armscii8_general_ci NULL DEFAULT NULL,
	PRIMARY KEY ( `cliente_perfil_id` ) )
CHARACTER SET = armscii8
COLLATE = armscii8_general_ci
ENGINE = InnoDB;
-- -------------------------------------------------------------


-- CREATE TABLE "clientes_suscripciones" -----------------------
CREATE TABLE `clientes_suscripciones`( 
	`cliente_suscripcion_id` VarChar( 12 ) CHARACTER SET armscii8 COLLATE armscii8_general_ci NOT NULL,
	`cliente_id` VarChar( 255 ) CHARACTER SET armscii8 COLLATE armscii8_general_ci NOT NULL,
	`monto` Decimal( 10, 0 ) NOT NULL,
	`fecha_pago` Timestamp NOT NULL,
	`dias_activa` Int( 0 ) NOT NULL,
	`fecha_fin` Date NOT NULL,
	`pagado` Int( 0 ) NOT NULL COMMENT '1.- no pagado
2.- pagado',
	`status` Int( 0 ) NOT NULL,
	`registro_fecha` Timestamp NOT NULL,
	`token_referencia` VarChar( 150 ) CHARACTER SET armscii8 COLLATE armscii8_general_ci NULL DEFAULT NULL,
	PRIMARY KEY ( `cliente_suscripcion_id` ) )
CHARACTER SET = armscii8
COLLATE = armscii8_general_ci
ENGINE = InnoDB;
-- -------------------------------------------------------------


-- CREATE TABLE "clientes_tarjetas" ----------------------------
CREATE TABLE `clientes_tarjetas`( 
	`cliente_tarjeta_id` VarChar( 12 ) CHARACTER SET armscii8 COLLATE armscii8_general_ci NOT NULL,
	`cliente_id` VarChar( 12 ) CHARACTER SET armscii8 COLLATE armscii8_general_ci NOT NULL,
	`numero` Int( 0 ) NOT NULL,
	`titulo` VarChar( 100 ) CHARACTER SET armscii8 COLLATE armscii8_general_ci NOT NULL,
	`titular` VarChar( 100 ) CHARACTER SET armscii8 COLLATE armscii8_general_ci NOT NULL,
	`anio_expiracion` Int( 0 ) NOT NULL,
	`mes_expiracion` VarChar( 6 ) CHARACTER SET armscii8 COLLATE armscii8_general_ci NOT NULL,
	`codigo_cvv` Int( 0 ) NOT NULL,
	`status` VarChar( 255 ) CHARACTER SET armscii8 COLLATE armscii8_general_ci NOT NULL,
	`registro_fecha` Timestamp NOT NULL,
	`actualizacion_fecha` Timestamp NOT NULL,
	PRIMARY KEY ( `cliente_tarjeta_id` ) )
CHARACTER SET = armscii8
COLLATE = armscii8_general_ci
ENGINE = InnoDB;
-- -------------------------------------------------------------


-- CREATE TABLE "tarjetas" -------------------------------------
CREATE TABLE `tarjetas`( 
	`tarjeta_id` VarChar( 12 ) CHARACTER SET armscii8 COLLATE armscii8_general_ci NOT NULL,
	`titulo` VarChar( 100 ) CHARACTER SET armscii8 COLLATE armscii8_general_ci NOT NULL,
	`numero` VarChar( 20 ) CHARACTER SET armscii8 COLLATE armscii8_general_ci NOT NULL,
	`ultimos_digitos` VarChar( 4 ) CHARACTER SET armscii8 COLLATE armscii8_general_ci NOT NULL,
	`titular` VarChar( 120 ) CHARACTER SET armscii8 COLLATE armscii8_general_ci NOT NULL,
	`anio_expiracion` Year NULL DEFAULT NULL,
	`mes_expiracion` VarChar( 8 ) CHARACTER SET armscii8 COLLATE armscii8_general_ci NULL DEFAULT NULL,
	`codigo_cvv` Int( 0 ) NULL DEFAULT NULL,
	`color` VarChar( 16 ) CHARACTER SET armscii8 COLLATE armscii8_general_ci NOT NULL,
	`marca_tarjeta_id` VarChar( 12 ) CHARACTER SET armscii8 COLLATE armscii8_general_ci NOT NULL,
	`comentario` Text CHARACTER SET armscii8 COLLATE armscii8_general_ci NULL DEFAULT NULL,
	`status` Int( 0 ) NOT NULL,
	`registro_fecha` Timestamp NOT NULL,
	`actualizacion_fecha` Timestamp NULL DEFAULT NULL,
	`cliente_id` VarChar( 12 ) CHARACTER SET armscii8 COLLATE armscii8_general_ci NOT NULL,
	PRIMARY KEY ( `tarjeta_id` ) )
CHARACTER SET = armscii8
COLLATE = armscii8_general_ci
ENGINE = InnoDB;
-- -------------------------------------------------------------


-- CREATE TABLE "tarjetas_gastos" ------------------------------
CREATE TABLE `tarjetas_gastos`( 
	`tarjeta_gasto_id` VarChar( 12 ) CHARACTER SET armscii8 COLLATE armscii8_general_ci NOT NULL,
	`tarjeta_id` VarChar( 12 ) CHARACTER SET armscii8 COLLATE armscii8_general_ci NOT NULL,
	`cliente_id` VarChar( 12 ) CHARACTER SET armscii8 COLLATE armscii8_general_ci NOT NULL,
	`concepto_gasto_id` VarChar( 12 ) CHARACTER SET armscii8 COLLATE armscii8_general_ci NOT NULL,
	`comentario` Text CHARACTER SET armscii8 COLLATE armscii8_general_ci NOT NULL,
	`mes_gasto` VarChar( 6 ) CHARACTER SET armscii8 COLLATE armscii8_general_ci NOT NULL,
	`anio_gasto` Year NOT NULL,
	`monto` Decimal( 14, 0 ) NOT NULL DEFAULT 0,
	`metodo_gasto` Int( 0 ) NOT NULL COMMENT 'Para determinar que tipo va a tomar

1. Pago fijo
2. Meses sin intereses
3. Pago unico a futuro',
	`tipo_gasto` Int( 0 ) NOT NULL COMMENT 'Para determinar como se realizo el gasto
si el metodo es: 
1. Pago fijo a meses
100. 3 meses
101. 6 meses
102. 9 meses
103. 12 meses
104. 15 meses
105. 18 meses
106. 24 meses
107. 30 meses
108. 48 meses
109. 60 meses
110. 72 meses

2. Meses sin intereses
200. 3 msi
201. 6 msi
202. 9 msi
203. 12 msi
204. 15 msi
205. 18 msi
206. 24 msi

3.
300. Pago unico al siguiente mes
301. Pagar dentro de 2 meses
302. Pagar dentro de 3 meses
303. Pagar dentro de 4 meses
304. Pagar dentro de 5 meses
305. Pagar dentro de 6 meses

',
	`status` Int( 0 ) NOT NULL,
	`registro_fecha` Int( 0 ) NOT NULL )
CHARACTER SET = armscii8
COLLATE = armscii8_general_ci
ENGINE = InnoDB;
-- -------------------------------------------------------------


-- CREATE TABLE "tarjetas_historico" ---------------------------
CREATE TABLE `tarjetas_historico`( 
	`tarjeta_historico_id` VarChar( 12 ) CHARACTER SET armscii8 COLLATE armscii8_general_ci NOT NULL,
	`registro_fecha` Timestamp NOT NULL,
	`tarjeta_id` VarChar( 12 ) CHARACTER SET armscii8 COLLATE armscii8_general_ci NOT NULL,
	`titular` VarChar( 120 ) CHARACTER SET armscii8 COLLATE armscii8_general_ci NOT NULL,
	`anio_expiracion` Year NULL DEFAULT NULL,
	`mes_expiracion` VarChar( 6 ) CHARACTER SET armscii8 COLLATE armscii8_general_ci NULL DEFAULT NULL,
	`codigo_cvv` Int( 0 ) NULL DEFAULT NULL,
	`comentario` Text CHARACTER SET armscii8 COLLATE armscii8_general_ci NULL DEFAULT NULL,
	`color` VarChar( 100 ) CHARACTER SET armscii8 COLLATE armscii8_general_ci NOT NULL,
	`marca_tarjeta_id` VarChar( 12 ) CHARACTER SET armscii8 COLLATE armscii8_general_ci NOT NULL,
	`numero` VarChar( 20 ) CHARACTER SET armscii8 COLLATE armscii8_general_ci NOT NULL,
	`ultimos_digitos` VarChar( 4 ) CHARACTER SET armscii8 COLLATE armscii8_general_ci NOT NULL,
	`titulo` VarChar( 100 ) CHARACTER SET armscii8 COLLATE armscii8_general_ci NOT NULL,
	`cliente_id` VarChar( 12 ) CHARACTER SET armscii8 COLLATE armscii8_general_ci NOT NULL,
	PRIMARY KEY ( `tarjeta_historico_id` ) )
CHARACTER SET = armscii8
COLLATE = armscii8_general_ci
ENGINE = InnoDB;
-- -------------------------------------------------------------


/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
-- ---------------------------------------------------------


