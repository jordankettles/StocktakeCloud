CREATE TABLE Products (
	name VARCHAR(50) NOT NULL,
	type VARCHAR(10) NOT NULL,
	unit VARCHAR(10) NOT NULL,
	vol DOUBLE(10, 2),
	full_weight DOUBLE(10, 2),
	empty_weight DOUBLE(10, 2), 
	desired_quantity INTEGER NOT NULL
 );

 CREATE TABLE Spirits (
	id MEDIUMINT NOT NULL AUTO_INCREMENT,
	name VARCHAR(50) NOT NULL,
	volume DOUBLE(10, 2),
	full_weight DOUBLE(10, 2),
	empty_weight DOUBLE(10, 2),
	desired_quantity INTEGER NOT NULL,
	PRIMARY KEY (id)
);

CREATE TABLE Beer (
	id MEDIUMINT NOT NULL AUTO_INCREMENT,
	name VARCHAR(50) NOT NULL,
	desired_quantity INTEGER NOT NULL,
	PRIMARY KEY (id)
);

CREATE TABLE Wine (
	id MEDIUMINT NOT NULL AUTO_INCREMENT,
	name VARCHAR(50) NOT NULL,
	volume DOUBLE(10, 2),
	full_weight DOUBLE(10, 2),
	empty_weight DOUBLE(10, 2),
	desired_quantity INTEGER NOT NULL,
	PRIMARY KEY (id)
);

CREATE TABLE NonAlc (
	id MEDIUMINT NOT NULL AUTO_INCREMENT,
	name VARCHAR(50) NOT NULL,
	desired_quantity INTEGER NOT NULL,
	PRIMARY KEY (id)
);

CREATE TABLE StocktakeProds (
	id MEDIUMINT NOT NULL AUTO_INCREMENT,
	name VARCHAR(50) NOT NULL,
	desired_quantity INTEGER NOT NULL,
	current_quantityInt INTEGER,
	current_quantityDec DOUBLE(10, 2),
	stocktake_num INTEGER NOT NULL,
	PRIMARY KEY (id)
 );

 CREATE TABLE StocktakeRefs (
	 dt DATETIME NOT NULL,
	 stock_num INTEGER NOT NULL
 );

CREATE TABLE ClientUsers (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE AdminUsers (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE ClientRequests (
	clientUsername VARCHAR(50) NOT NULL UNIQUE,
	clientID INT NOT NULL,
	adminUsername VARCHAR(50) NOT NULL,
	adminID INT NOT NULL,

	FOREIGN KEY (clientID)
		REFERENCES ClientUsers(id)
		ON UPDATE CASCADE ON DELETE CASCADE,

	FOREIGN KEY (adminID)
		REFERENCES AdminUsers(id)
		ON UPDATE CASCADE ON DELETE CASCADE,

    PRIMARY KEY (clientID, adminID)
);

CREATE TABLE ClientTableAccess (
	clientID INT NOT NULL,
	adminID INT NOT NULL,

	FOREIGN KEY (clientID)
		REFERENCES ClientUsers(id)
		ON UPDATE CASCADE ON DELETE CASCADE,

	FOREIGN KEY (adminID)
		REFERENCES AdminUsers(id)
		ON UPDATE CASCADE ON DELETE CASCADE,
    
    PRIMARY KEY (clientID, adminID)
);