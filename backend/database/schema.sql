CREATE DATABASE IF NOT EXISTS plaza
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE plaza;

-- 1) Tables that others point to come first
CREATE TABLE brands (
    id    INT AUTO_INCREMENT PRIMARY KEY,
    naziv VARCHAR(80) NOT NULL UNIQUE
);

CREATE TABLE users (
    id                INT AUTO_INCREMENT PRIMARY KEY,
    ime               VARCHAR(60)  NOT NULL,
    prezime           VARCHAR(60)  NOT NULL,
    email             VARCHAR(120) NOT NULL UNIQUE,
    lozinka           VARCHAR(255) NOT NULL,
    uloga             ENUM('user', 'admin') NOT NULL DEFAULT 'user',
    datum_registracije TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- 2) Tables that depend on the ones above
CREATE TABLE vehicles (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    brand_id    INT           NOT NULL,
    model       VARCHAR(80)   NOT NULL,
    godina      YEAR          NOT NULL,
    cijena      DECIMAL(10,2) NOT NULL,
    kilometraza INT           NOT NULL DEFAULT 0,
    mjenjac     ENUM('manuelni', 'automatik') NOT NULL,
    gorivo      ENUM('benzin', 'dizel', 'hibrid', 'elektricni') NOT NULL,
    snaga_kw    INT           NOT NULL,
    boja        VARCHAR(40)   NOT NULL,
    opis        TEXT          NULL,
    slika_url   VARCHAR(255)  NULL,
    status      ENUM('dostupno', 'rezervisano', 'prodano') NOT NULL DEFAULT 'dostupno',
    CONSTRAINT fk_vehicles_brand FOREIGN KEY (brand_id) REFERENCES brands(id),
    CONSTRAINT chk_vehicles_cijena CHECK (cijena >= 0),
    CONSTRAINT chk_vehicles_km CHECK (kilometraza >= 0)
);

CREATE TABLE inquiries (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    user_id    INT  NOT NULL,
    vehicle_id INT  NOT NULL,
    poruka     TEXT NOT NULL,
    datum      TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_inquiries_user    FOREIGN KEY (user_id)    REFERENCES users(id),
    CONSTRAINT fk_inquiries_vehicle FOREIGN KEY (vehicle_id) REFERENCES vehicles(id)
);

CREATE TABLE wishlist (
    id             INT AUTO_INCREMENT PRIMARY KEY,
    user_id        INT NOT NULL,
    vehicle_id     INT NOT NULL,
    datum_dodavanja TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_wishlist_user    FOREIGN KEY (user_id)    REFERENCES users(id),
    CONSTRAINT fk_wishlist_vehicle FOREIGN KEY (vehicle_id) REFERENCES vehicles(id),
    CONSTRAINT uq_wishlist UNIQUE (user_id, vehicle_id)
);

CREATE TABLE financing_requests (
    id             INT AUTO_INCREMENT PRIMARY KEY,
    user_id        INT           NOT NULL,
    vehicle_id     INT           NOT NULL,
    ucesce         DECIMAL(10,2) NOT NULL,
    broj_rata      INT           NOT NULL,
    mjesecna_rata  DECIMAL(10,2) NOT NULL,
    status         ENUM('na_cekanju', 'odobreno', 'odbijeno') NOT NULL DEFAULT 'na_cekanju',
    datum          TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_fin_user    FOREIGN KEY (user_id)    REFERENCES users(id),
    CONSTRAINT fk_fin_vehicle FOREIGN KEY (vehicle_id) REFERENCES vehicles(id),
    CONSTRAINT chk_fin_ucesce CHECK (ucesce >= 0),
    CONSTRAINT chk_fin_rate   CHECK (broj_rata > 0)
);

-- 3) Standalone table, no relations
CREATE TABLE contact_messages (
    id     INT AUTO_INCREMENT PRIMARY KEY,
    ime    VARCHAR(100) NOT NULL,
    email  VARCHAR(120) NOT NULL,
    poruka TEXT NOT NULL,
    datum  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);