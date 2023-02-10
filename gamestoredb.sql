CREATE USER 'storeusr'@'localhost' IDENTIFIED BY 'storepass';

CREATE DATABASE gamestoredb
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;
  
USE gamestoredb;

GRANT SELECT, INSERT, UPDATE, DELETE ON gamestoredb.* TO 'storeusr'@'localhost';

CREATE TABLE users (
    id INTEGER auto_increment,
    username VARCHAR(20) NOT NULL UNIQUE,
    password VARCHAR(40) NOT NULL,
    firstname VARCHAR(100) NOT NULL,
    lastname VARCHAR(100) NOT NULL,
    role VARCHAR(10) NOT NULL DEFAULT 'staff',
    UNIQUE (firstname, lastname),
    PRIMARY KEY (id)
) ENGINE InnoDb;

INSERT INTO users VALUES (0, "android", "pass01", "Andrea", "Morales Mata", "admin");
INSERT INTO users VALUES (0, "annie", "pass02", "Ani Liseth", "Valle Banegas", "admin");
INSERT INTO users VALUES (0, "steelstrain", "pass03", "Alex", "Jimenez Cuevas", "admin");
INSERT INTO users VALUES (0, "lollipop24", "pass04", "Esther", "Casado Puebla", "staff");
INSERT INTO users VALUES (0, "devilsmaycry", "pass05", "Roger", "Bueno Padilla", "staff");
INSERT INTO users VALUES (0, "mineadict", "pass06", "Ruben", "Martin Fernandez", "staff");
INSERT INTO users VALUES (0, "zeldaxlink", "pass06", "Maria", "Cabezas Bolaños", "staff");

CREATE TABLE products (
    id INTEGER auto_increment,
    code VARCHAR(20) NOT NULL UNIQUE,
    description VARCHAR(100) NOT NULL,
    price DOUBLE DEFAULT 0.0,
    category_id INTEGER NOT NULL,
    PRIMARY KEY (id)
) ENGINE InnoDb;

CREATE TABLE categories (
    id INTEGER auto_increment,
    code VARCHAR(20) NOT NULL UNIQUE,
    description VARCHAR(100) NOT NULL,
    PRIMARY KEY (id)
) ENGINE InnoDb;

CREATE TABLE warehouses (
  id INTEGER auto_increment,
  code VARCHAR(20) NOT NULL UNIQUE,
  address VARCHAR(100) NOT NULL,
  PRIMARY KEY (id)
) ENGINE InnoDb;

CREATE TABLE warehousesproducts (
  warehouse_id INTEGER,
  product_id INTEGER,
  stock INTEGER,
  PRIMARY KEY(warehouse_id, product_id)
) ENGINE InnoDb;

ALTER TABLE products ADD FOREIGN KEY (category_id) REFERENCES categories(id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE warehousesproducts ADD FOREIGN KEY (product_id) REFERENCES products(id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE warehousesproducts ADD FOREIGN KEY (warehouse_id) REFERENCES warehouses(id) ON UPDATE CASCADE ON DELETE RESTRICT;

INSERT INTO categories VALUES 
  (1, "Playstation", "Accessories and games o Sony playstation consoles"),
  (2, "Nintendo Switch", "Accessories and games of this console"),
  (3, "Xbox", "Accessories and games o Microsoft xbox  consoles"),
  (4, "PC", "Accessories and games of PC"),
  (5, "Merchandising", "All gaming merchandising products of all kind");

INSERT INTO products VALUES 
  (1, "God of War Ragnarok", "PS5 Game", 79.99, 1),
  (2, "Red wallet", "Mario money wallet", 9.99, 5),
  (3, "Animal Crossing:NH", "Nintendo Switch game", 42.99, 2),
  (4, "Grand Theft Auto V", "PS4 game", 19.99, 1),
  (5, "The Sims 4", "PC game", 95.99, 4),
  (6, "Space invaders", "Retro shirt", 19.95, 5),
  (7, "Dragon Ball Super", "Poster", 4.99, 5),
  (8, "Dualsense Controller", "PS5 Accesories", 69.99, 1),
  (9, "Angry Pika", "Pokémon cap", 19.99, 5),
  (10, "The Legend of Zelda", "Nintendo Switch game", 35.99, 2),
  (11, "Need For Speed Heat", "PC game", 14.99, 4),
  (12, "Mario Kart 8", "Nintendo Switch game", 27.95, 2),
  (13, "Howarts Legacy", "PS5 Game", 74.99, 1),
  (14, "Battlefield 2042", "Xbox game", 29.99, 3),
  (15, "Crash Bandicoot", "Poster", 5.99, 5),
  (16, "Minecraft", "PS3 game", 19.99, 1),
  (17, "Steelseries arctis 3", "PC headphones accessories", 69.99, 4),
  (18, "Farming Simulator", "PC game", 19.99, 4),
  (19, "Far Cry 6", "Xbox game", 24.99, 3),
  (20, "Pack 2 steering", "Nintendo Switch accesories", 11.99, 2); 

INSERT INTO warehouses VALUES 
  (1, "LaCantina", "C/ Pi Maragarll 20-25"),
  (2, "Mas Forner", "C/ Angel Guimerà 34"),
  (3, "La Roca", "C/ Avinguda 89"),
  (4, "GAME Outlet", "C/Pablo Picasso 19-22"),
  (5, "Finestrelles", "C/Narcís 70 ");

INSERT INTO warehousesproducts VALUES 
  (4, 1, 5),
  (5, 20, 10),
  (1, 5, 15),
  (4, 18, 3),
  (3, 9, 23),
  (5, 15, 18),
  (2, 6, 10),
  (4, 10, 2),
  (1, 4, 13),
  (5, 9, 3),
  (4, 13, 23),
  (3, 3, 6),
  (2, 13, 12),
  (5, 17, 1),
  (5, 6, 26);
  