<?php
// Include the database connection
include 'db.php';

// Initialize variables for filtering
$doctor_id = isset($_POST['doctor_id']) ? $_POST['doctor_id'] : '';
$patient_id = isset($_POST['patient_id']) ? $_POST['patient_id'] : '';

// Build the base query
$query = "SELECT Prescriptions.id, Prescriptions.dosage, Prescriptions.date, 
                 Patients.name AS patient_name, 
                 Doctors.name AS doctor_name, 
                 Medications.name AS medication_name 
          FROM Prescriptions 
          INNER JOIN Patients ON Prescriptions.patient_id = Patients.id 
          INNER JOIN Doctors ON Prescriptions.doctor_id = Doctors.id 
          INNER JOIN Medications ON Prescriptions.medication_id = Medications.id";

// Add filters if applicable
$conditions = [];
$params = [];
$types = "";

if (!empty($doctor_id)) {
    $conditions[] = "Prescriptions.doctor_id = ?";
    $params[] = $doctor_id;
    $types .= "i";
}

if (!empty($patient_id)) {
    $conditions[] = "Prescriptions.patient_id = ?";
    $params[] = $patient_id;
    $types .= "i";
}

if (count($conditions) > 0) {
    $query .= " WHERE " . implode(" AND ", $conditions);
}

$query .= " ORDER BY Prescriptions.date DESC";

$stmt = $conn->prepare($query);

if ($types) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

// Fetch doctors and patients for filter dropdowns
$doctors = $conn->query("SELECT id, name FROM Doctors");
$patients = $conn->query("SELECT id, name FROM Patients");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prescription History</title>
</head>
<body>
    <h1>Prescription History</h1>

    <!-- Filter Form -->
    <form method="POST" action="">
        <!-- Doctor Filter -->
        <label for="doctor_id">Filter by Doctor:</label>
        <select name="doctor_id" id="doctor_id">
            <option value="">All Doctors</option>
            <?php while ($doctor = $doctors->fetch_assoc()): ?>
                <option value="<?= $doctor['id'] ?>" <?= $doctor_id == $doctor['id'] ? 'selected' : '' ?>>
                    <?= $doctor['name'] ?>
                </option>
            <?php endwhile; ?>
        </select>
        <br><br>

        <!-- Patient Filter -->
        <label for="patient_id">Filter by Patient:</label>
        <select name="patient_id" id="patient_id">
            <option value="">All Patients</option>
            <?php while ($patient = $patients->fetch_assoc()): ?>
                <option value="<?= $patient['id'] ?>" <?= $patient_id == $patient['id'] ? 'selected' : '' ?>>
                    <?= $patient['name'] ?>
                </option>
            <?php endwhile; ?>
        </select>
        <br><br>

        <button type="submit">Filter</button>
        <button type="button" onclick="window.location.href='prescription_history.php'">Reset</button>
    </form>
    <br><br>

    <!-- Prescription Table -->
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Patient Name</th>
                <th>Doctor Name</th>
                <th>Medication</th>
                <th>Dosage</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= $row['patient_name'] ?></td>
                        <td><?= $row['doctor_name'] ?></td>
                        <td><?= $row['medication_name'] ?></td>
                        <td><?= $row['dosage'] ?></td>
                        <td><?= $row['date'] ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">No prescriptions found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
