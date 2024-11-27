CREATE DATABASE hospital_db;
USE hospital_db;


CREATE TABLE Patients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    age INT NOT NULL,
    gender VARCHAR(10) NOT NULL,
    contact VARCHAR(50) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE Medications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    quantity INT NOT NULL DEFAULT 0,
    stock INT NOT NULL
) ENGINE=InnoDB;

CREATE TABLE Doctors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    specialization VARCHAR(255),
    contact VARCHAR(50) NOT NULL
) ENGINE=InnoDB;


CREATE TABLE Prescriptions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    doctor_id INT NOT NULL,
    medication_id INT NOT NULL,
    dosage VARCHAR(255) NOT NULL,
    date DATE NOT NULL,
    FOREIGN KEY (patient_id) REFERENCES Patients(id) ON DELETE CASCADE,
    FOREIGN KEY (doctor_id) REFERENCES Doctors(id) ON DELETE CASCADE,
    FOREIGN KEY (medication_id) REFERENCES Medications(id) ON DELETE CASCADE
);


INSERT INTO Patients (name, age, gender, contact)
VALUES 
('John Doe', 30, 'Male', '123-456-7890'),
('Jane Smith', 25, 'Female', '987-654-3210'),
('Alice Brown', 35, 'Female', '456-789-0123');


INSERT INTO Doctors (name, specialization, contact)
VALUES 
('Dr. Andrew White', 'Cardiologist', '321-654-9870'),
('Dr. Sarah Green', 'Pediatrician', '654-123-0987'),
('Dr. Emily Black', 'Neurologist', '789-012-3456');

--inserting medication values
INSERT INTO Medications (name, description, price, quantity) VALUES
('Paracetamol', 'Used for pain relief and fever reduction', 5.00, 100),
('Amoxicillin', 'Antibiotic used to treat bacterial infections', 10.50, 200),
('Ibuprofen', 'Anti-inflammatory drug for pain and swelling', 8.00, 150),
('Metformin', 'Medication for managing type 2 diabetes', 15.00, 120),
('Atorvastatin', 'Used to lower cholesterol levels', 12.00, 100),
('Omeprazole', 'Reduces stomach acid for indigestion or ulcers', 7.50, 180),
('Losartan', 'Treats high blood pressure and heart problems', 9.00, 130),
('Citalopram', 'Antidepressant for anxiety and depression', 13.00, 90),
('Furosemide', 'Diuretic for managing fluid retention and swelling', 6.00, 140),
('Aspirin', 'Relieves pain, inflammation, and fever', 4.50, 200);



--retrieve prescription history
SELECT p.id AS prescription_id, d.name AS doctor_name, m.name AS medication_name, p.dosage, p.date
FROM Prescriptions p
JOIN Doctors d ON p.doctor_id = d.id
JOIN Medications m ON p.medication_id = m.id
WHERE p.patient_id = 1;


--update medication details
UPDATE Medications 
SET name = 'Updated Medication', description = 'Updated Description', price = 12.00, stock = 150
WHERE id = 1;

--updating medication values
UPDATE Medications SET quantity = 100 WHERE name = 'Paracetamol';
UPDATE Medications SET quantity = 200 WHERE name = 'Amoxicillin';
UPDATE Medications SET quantity = 150 WHERE name = 'Ibuprofen';
UPDATE Medications SET quantity = 120 WHERE name = 'Metformin';
UPDATE Medications SET quantity = 100 WHERE name = 'Atorvastatin';
UPDATE Medications SET quantity = 180 WHERE name = 'Omeprazole';
UPDATE Medications SET quantity = 130 WHERE name = 'Losartan';
UPDATE Medications SET quantity = 90 WHERE name = 'Citalopram';
UPDATE Medications SET quantity = 140 WHERE name = 'Furosemide';
UPDATE Medications SET quantity = 200 WHERE name = 'Aspirin';



--filter prescriptions by patient
SELECT p.id AS prescription_id, pt.name AS patient_name, m.name AS medication_name, p.dosage, p.date
FROM Prescriptions p
JOIN Patients pt ON p.patient_id = pt.id
JOIN Medications m ON p.medication_id = m.id
WHERE p.doctor_id = 1;

----filter prescriptions by doctor
SELECT p.id AS prescription_id, pt.name AS patient_name, m.name AS medication_name, p.dosage, p.date
FROM Prescriptions p
JOIN Patients pt ON p.patient_id = pt.id
JOIN Medications m ON p.medication_id = m.id
WHERE p.doctor_id = 1;


