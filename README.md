# Hotel-reservation-system
Hotel reservation using MySQL and PHP
-- Create a new database named 'hospital_db'
CREATE DATABASE hospital_db;

-- Use the 'hospital_db' database
USE hospital_db;

-- Create the 'Patients' table to store patient details
CREATE TABLE Patients (
    id INT AUTO_INCREMENT PRIMARY KEY,  -- Unique identifier for each patient
    name VARCHAR(255) NOT NULL,         -- Patient's name (required)
    age INT NOT NULL,                   -- Patient's age (required)
    gender VARCHAR(10) NOT NULL,        -- Patient's gender (required)
    contact VARCHAR(50) NOT NULL        -- Patient's contact number (required)
) ENGINE=InnoDB;                       -- Use InnoDB engine for foreign key support

-- Create the 'Medications' table to store medication details
CREATE TABLE Medications (
    id INT AUTO_INCREMENT PRIMARY KEY,  -- Unique identifier for each medication
    name VARCHAR(255) NOT NULL,         -- Medication's name (required)
    description TEXT,                   -- Medication's description (optional)
    price DECIMAL(10, 2) NOT NULL,      -- Price of the medication (required)
    quantity INT NOT NULL DEFAULT 0,    -- Quantity available in stock (default 0)
    stock INT NOT NULL                  -- Amount of stock available (required)
) ENGINE=InnoDB;                       -- Use InnoDB engine for foreign key support

-- Create the 'Doctors' table to store doctor details
CREATE TABLE Doctors (
    id INT AUTO_INCREMENT PRIMARY KEY,  -- Unique identifier for each doctor
    name VARCHAR(255) NOT NULL,         -- Doctor's name (required)
    specialization VARCHAR(255),        -- Doctor's specialization (optional)
    contact VARCHAR(50) NOT NULL        -- Doctor's contact number (required)
) ENGINE=InnoDB;                       -- Use InnoDB engine for foreign key support

-- Create the 'Prescriptions' table to store prescription details
CREATE TABLE Prescriptions (
    id INT AUTO_INCREMENT PRIMARY KEY,  -- Unique identifier for each prescription
    patient_id INT NOT NULL,            -- Foreign key referencing the 'Patients' table
    doctor_id INT NOT NULL,             -- Foreign key referencing the 'Doctors' table
    medication_id INT NOT NULL,         -- Foreign key referencing the 'Medications' table
    dosage VARCHAR(255) NOT NULL,       -- Dosage instructions for the medication (required)
    date DATE NOT NULL,                 -- Date of the prescription (required)
    FOREIGN KEY (patient_id) REFERENCES Patients(id) ON DELETE CASCADE,  -- Ensure referential integrity with 'Patients'
    FOREIGN KEY (doctor_id) REFERENCES Doctors(id) ON DELETE CASCADE,    -- Ensure referential integrity with 'Doctors'
    FOREIGN KEY (medication_id) REFERENCES Medications(id) ON DELETE CASCADE  -- Ensure referential integrity with 'Medications'
);

-- Insert sample patient data into the 'Patients' table
INSERT INTO Patients (name, age, gender, contact)
VALUES 
('John Doe', 30, 'Male', '123-456-7890'),
('Jane Smith', 25, 'Female', '987-654-3210'),
('Alice Brown', 35, 'Female', '456-789-0123');

-- Insert sample doctor data into the 'Doctors' table
INSERT INTO Doctors (name, specialization, contact)
VALUES 
('Dr. Andrew White', 'Cardiologist', '321-654-9870'),
('Dr. Sarah Green', 'Pediatrician', '654-123-0987'),
('Dr. Emily Black', 'Neurologist', '789-012-3456');

-- Insert sample medication data into the 'Medications' table
INSERT INTO Medications (name, description, price, quantity) 
VALUES
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

-- Query to retrieve a patient's prescription history (for patient with ID 1)
SELECT p.id AS prescription_id, d.name AS doctor_name, m.name AS medication_name, p.dosage, p.date
FROM Prescriptions p
JOIN Doctors d ON p.doctor_id = d.id
JOIN Medications m ON p.medication_id = m.id
WHERE p.patient_id = 1;

-- Update medication details for the medication with ID 1
UPDATE Medications 
SET name = 'Updated Medication', description = 'Updated Description', price = 12.00, stock = 150
WHERE id = 1;

-- Update the quantities of various medications
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

-- Query to filter prescriptions by a specific doctor (doctor with ID 1)
SELECT p.id AS prescription_id, pt.name AS patient_name, m.name AS medication_name, p.dosage, p.date
FROM Prescriptions p
JOIN Patients pt ON p.patient_id = pt.id
JOIN Medications m ON p.medication_id = m.id
WHERE p.doctor_id = 1;
