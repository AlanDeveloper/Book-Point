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

insert into "user" ("name", "email", "password") VALUES ('Alan', 'alanssantos32@gmail.com', '123456');