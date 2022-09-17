DROP TABLE IF EXISTS wcf1_distinguished;
CREATE TABLE wcf1_distinguished (
	distinguishedID		INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	optionName			VARCHAR(100) NOT NULL DEFAULT '',
	application			VARCHAR(50) NOT NULL DEFAULT '',
	packageID			INT(10) NOT NULL
);

ALTER TABLE wcf1_distinguished ADD FOREIGN KEY (packageID) REFERENCES wcf1_package (packageID) ON DELETE CASCADE;
