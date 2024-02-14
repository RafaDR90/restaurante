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

INSERT INTO categorias (nombre, descripcion)
VALUES ('Frutas', 'Productos frescos y naturales, ideales para una dieta saludable.');

INSERT INTO categorias (nombre, descripcion)
VALUES ('Vegetales', 'Productos cultivados en la tierra, ricos en vitaminas y minerales.');

INSERT INTO categorias (nombre, descripcion)
VALUES ('Lácteos', 'Productos derivados de la leche, como la leche, el queso y el yogur.');

select * from productos;

-- Para la categoría "Frutas"
INSERT INTO productos (nombre, descripcion, peso, stock,precio, categoria_id)
VALUES ('Manzana', 'Una fruta deliciosa y saludable.', 0.2, 50,2, 2);

INSERT INTO productos (nombre, descripcion, peso, stock,precio, categoria_id)
VALUES ('Plátano', 'Una fruta tropical llena de energía.', 0.15, 40,2.5, 2);

-- Para la categoría "Vegetales"
INSERT INTO productos (nombre, descripcion, peso, stock,precio, categoria_id)
VALUES ('Zanahoria', 'Una hortaliza rica en vitamina A.', 0.1, 30,2.3, 3);

INSERT INTO productos (nombre, descripcion, peso, stock,precio, categoria_id)
VALUES ('Espinaca', 'Una verdura llena de hierro y nutrientes.', 0.2, 25,1.2, 3);

-- Para la categoría "Lácteos"
INSERT INTO productos (nombre, descripcion, peso, stock,precio, categoria_id)
VALUES ('Leche', 'Una bebida nutritiva y esencial en la dieta.', 1, 100,1, 4);

INSERT INTO productos (nombre, descripcion, peso, stock,precio, categoria_id)
VALUES ('Queso', 'Un producto lácteo delicioso y versátil.', 0.3, 50,2, 4);
