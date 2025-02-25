# Sistema de Identificación de Tendencias de la Diabetes Grado 2
## Pagina para hacer commits

 - [commitlint.io](https://commitlint.io/)



## Prefijos en la base de datos

En las tablas del sistema tenemos diferentes prefijos usados para clasificar las diferentes tablas
| Prefijo           | Significado                       |
| ----------------- | --------------------------------- |
| sd_               | Sistema diabetes                  |
| pr_               | Sistema preguntas y respuestas    |
| sis_              | Sistema principal                 |


## Querys para limpiar

#### Limpiar personal medidco, usuarios y personas en el sistema

```sql
TRUNCATE TABLE sd_personal_medico;
TRUNCATE TABLE sd_personal_especialidad;
TRUNCATE TABLE sis_personal;
TRUNCATE TABLE sis_usuarios;

INSERT INTO `sis_usuarios` (`idusuario`, `idrol`, `idpersona`, `usu_usuario`, `usu_pass`, `usu_token`, `usu_activo`, `usu_estado`, `usu_primera`, `usu_twoauth`, `usu_code_twoauth`, `usu_fecha`) VALUES
(1, 1, 1, 'developer', '$2y$10$Fit/2psoTtAP.pctt2qiluYnf4vYcKqbGvFbZa.8/ngskf1HlwZvW', NULL, 1, 1, 0, 0, '', '2022-07-22 01:10:31');
INSERT INTO `sis_personal` (`idpersona`, `per_dni`, `per_nombre`, `per_celular`, `per_email`, `per_direcc`, `per_foto`, `per_estado`, `per_fecha`) VALUES
(1, 76144152, 'desarrollador', 987654321, 'hackingleenh@gmail.com', '', NULL, 1, '2022-07-22 01:09:20');

--72845692
```
