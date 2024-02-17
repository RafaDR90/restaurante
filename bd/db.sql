drop database restaurante;
create database restaurante;
use restaurante;
show tables ;
select * from restaurante;
select * from pedidos;
select * from datos_pedido;
select * from productos;
select * from categorias;
delete from restaurante;
describe restaurante;
UPDATE restaurante SET roles = '["ROLE_ADMIN"]' WHERE id = 9;

INSERT INTO restaurante (email, roles, password, cp, ciudad, direccion)
VALUES ('rafa18220delgado@gmail.com', '["ROLE_ADMIN"]', '$2y$13$iD9L840Kt10mfQRi8ACZve6mswmCch2/dpISjIw.RmVO2fxLomsp2', '18220', 'ciudad peligros', 'calle pepito');
# el pw es rafarafa

select * from categorias;


