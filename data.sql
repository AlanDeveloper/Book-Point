CREATE TABLE "user"(
	"id" bigserial PRIMARY KEY,
	"name" varchar(50) NOT NULL,
	"email" varchar(255) UNIQUE NOT NULL,
	"password" varchar(50) NOT NULL,
	"admin" boolean DEFAULT false
);

CREATE TABLE "book"(
	"id" bigserial PRIMARY KEY,
	"name" varchar(50) UNIQUE NOT NULL,
	"author" varchar(50) NOT NULL,
	"image" varchar(100) NOT NULL,
	"price" double precision NOT NULL,
	"amount" int NOT NULL,
	"language" varchar(50) NOT NULL,
	"synopsis" varchar(2500) NOT NULL,
	"genre" varchar(100) NOT NULL
);

CREATE TABLE "cart"(
    "id_user" bigint,
    "id_book" bigint,
    "total" double precision,
	PRIMARY KEY("id_user", "id_book")
);

INSERT INTO "user" ("name", "email", "password") VALUES ('Alan', 'alanssantos32@gmail.com', '123456');
INSERT INTO "book" (name, author, image, price, amount, language, synopsis, genre) VALUES ('Trono de Vidro', 'Harry Potter', 'image', 24.50, 20, 'Português;', 'sdasdasjdksjda', 'Drama;Romance;');
INSERT INTO "book" (name, author, image, price, amount, language, synopsis, genre) VALUES ('Just Case', 'Harry Potter', 'image', 30.49, 20, 'Português;', 'sdasdasjdksjda', 'Drama;Romance;');
INSERT INTO "book" (name, author, image, price, amount, language, synopsis, genre) VALUES ('Crave a Marca', 'Harry Potter', 'image', 39.99, 20, 'Português;', 'sdasdasjdksjda', 'Drama;Romance;');