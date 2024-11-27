<?php
// Include the database connection file
include 'db.php';

// Handle form submission
if (isset($_POST['submit_prescription'])) {
    $patient_name = $_POST['patient_name'];
    $doctor_id = $_POST['doctor_id'];
    $medication_id = $_POST['medication_id'];
    $dosage = $_POST['dosage'];
    $date = $_POST['date'];

    // Check if the patient already exists
    $patient_query = "SELECT id FROM Patients WHERE name = ?";
    $stmt = $conn->prepare($patient_query);
    $stmt->bind_param("s", $patient_name);
    $stmt->execute();
    $stmt->bind_result($patient_id);
    $stmt->fetch();
    $stmt->close();

    // If the patient does not exist, insert them
    if (!$patient_id) {
        $insert_patient_query = "INSERT INTO Patients (name) VALUES (?)";
        $stmt = $conn->prepare($insert_patient_query);
        $stmt->bind_param("s", $patient_name);
        $stmt->execute();
        $patient_id = $stmt->insert_id; // Get the new patient's ID
        $stmt->close();
    }

    // Insert the prescription into the database
    $sql = "INSERT INTO Prescriptions (patient_id, doctor_id, medication_id, dosage, date) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiiss", $patient_id, $doctor_id, $medication_id, $dosage, $date);

    if ($stmt->execute()) {
        echo "Prescription added successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Fetch doctors and medications for the dropdowns
$doctors = $conn->query("SELECT id, name FROM Doctors");
$medications = $conn->query("SELECT id, name FROM Medications");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Prescription</title>
</head>
<body>
    <h1>Create a New Prescription</h1>

    <form method="POST" action="">
        <!-- Patient Name Input -->
        <label for="patient_name">Patient Name:</label>
        <input type="text" name="patient_name" id="patient_name" required>
        <br><br>

        <!-- Doctor Selection -->
        <label for="doctor_id">Doctor:</label>
        <select name="doctor_id" id="doctor_id" required>
            <option value="">Select Doctor</option>
            <?php while ($doctor = $doctors->fetch_assoc()): ?>
                <option value="<?= $doctor['id'] ?>"><?= $doctor['name'] ?></option>
            <?php endwhile; ?>
        </select>
        <br><br>

        <!-- Medication Selection -->
        <label for="medication_id">Medication:</label>
        <select name="medication_id" id="medication_id" required>
            <option value="">Select Medication</option>
            <?php while ($medication = $medications->fetch_assoc()): ?>
                <option value="<?= $medication['id'] ?>"><?= $medication['name'] ?></option>
            <?php endwhile; ?>
        </select>
        <br><br>

        <!-- Dosage Input -->
        <label for="dosage">Dosage:</label>
        <input type="text" name="dosage" id="dosage" required>
        <br><br>

        <!-- Date Input -->
        <label for="date">Date:</label>
        <input type="date" name="date" id="date" required>
        <br><br>

        <!-- Submit Button -->
        <button type="submit" name="submit_prescription">Add Prescription</button>
    </form>
</body>
</html>
