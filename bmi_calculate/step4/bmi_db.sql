
 CREATE TABLE IF NOT EXISTS bmi_records (
     id INT AUTO_INCREMENT PRIMARY KEY,
     name VARCHAR(100),
     weight FLOAT,
     height FLOAT,
     bmi FLOAT,
     interpretation VARCHAR(50),
     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
 );
