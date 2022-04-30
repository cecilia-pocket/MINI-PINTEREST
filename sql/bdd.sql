/* -----------------------------------------
              CREATION DES TABLES
   ----------------------------------------- */
CREATE TABLE Categorie(
  catId INTEGER PRIMARY KEY,
  nomCat VARCHAR(250) NOT NULL
);

CREATE TABLE Photo(
  photoId INTEGER PRIMARY KEY,
  nomFich VARCHAR(250) NOT NULL,
  description VARCHAR(250) NOT NULL,
  catId INTEGER,
  FOREIGN KEY (catId) REFERENCES Categorie(catId)
);

CREATE TABLE Utilisateur(
  pseudo VARCHAR(250) NOT NULL PRIMARY KEY,
  mdp VARCHAR(250) NOT NULL,
  etat VARCHAR(250) NOT NULL,
  role VARCHAR(250) NOT NULL
);
