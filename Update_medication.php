<?php
// Include the database connection file
include 'db.php';

// Handle medication update
if (isset($_POST['update_medication'])) {
    $medication_id = $_POST['medication_id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    $sql = "UPDATE Medications SET name=?, description=?, price=?, stock=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdii", $name, $description, $price, $stock, $medication_id);
    $stmt->execute();
    echo "Medication updated successfully!";
}

// Fetch doctors and patients for filtering
$doctors = $conn->query("SELECT id, name FROM Doctors");
$patients = $conn->query("SELECT id, name FROM Patients");

// Handle filter request
$where_clause = "";
if (isset($_GET['filter'])) {
    if (!empty($_GET['doctor_id'])) {
        $where_clause = "WHERE p.doctor_id = " . intval($_GET['doctor_id']);
    } elseif (!empty($_GET['patient_id'])) {
        $where_clause = "WHERE p.patient_id = " . intval($_GET['patient_id']);
    }
}

// Fetch prescriptions for filtering
$prescriptions = $conn->query("
    SELECT p.id, pt.name AS patient_name, d.name AS doctor_name, m.name AS medication_name, p.dosage, p.date 
    FROM Prescriptions p
    JOIN Patients pt ON p.patient_id = pt.id
    JOIN Doctors d ON p.doctor_id = d.id
    JOIN Medications m ON p.medication_id = m.id
    $where_clause
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Medication</title>
</head>
<body>
    <h1>Update Medication</h1>

    <!-- Filter Section -->
    <form method="GET" action="">
        <label for="doctor_id">Filter by Doctor:</label>
        <select name="doctor_id" id="doctor_id">
            <option value="">Select Doctor</option>
            <?php while ($doctor = $doctors->fetch_assoc()): ?>
                <option value="<?= $doctor['id'] ?>"><?= $doctor['name'] ?></option>
            <?php endwhile; ?>
        </select>

        <label for="patient_id">Filter by Patient:</label>
        <select name="patient_id" id="patient_id">
            <option value="">Select Patient</option>
            <?php while ($patient = $patients->fetch_assoc()): ?>
                <option value="<?= $patient['id'] ?>"><?= $patient['name'] ?></option>
            <?php endwhile; ?>
        </select>

        <button type="submit" name="filter">Filter</button>
    </form>

    <!-- Prescription List -->
    <h2>Prescription List</h2>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Patient</th>
                <th>Doctor</th>
                <th>Medication</th>
                <th>Dosage</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $prescriptions->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['patient_name'] ?></td>
                    <td><?= $row['doctor_name'] ?></td>
                    <td><?= $row['medication_name'] ?></td>
                    <td><?= $row['dosage'] ?></td>
                    <td><?= $row['date'] ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Medication Update Form -->
    <h2>Update Medication</h2>
    <form method="POST" action="">
        <label for="medication_id">Select Medication:</label>
        <select name="medication_id" id="medication_id" required>
            <option value="">Select Medication</option>
            <?php
            $medications = $conn->query("SELECT id, name FROM Medications");
            while ($medication = $medications->fetch_assoc()):
            ?>
                <option value="<?= $medication['id'] ?>"><?= $medication['name'] ?></option>
            <?php endwhile; ?>
        </select>

        <label for="name">Name:</label>
        <input type="text" name="name" id="name" required>

        <label for="description">Description:</label>
        <textarea name="description" id="description" required></textarea>

        <label for="price">Price:</label>
        <input type="number" step="0.01" name="price" id="price" required>

        <label for="stock">Stock:</label>
        <input type="number" name="stock" id="stock" required>

        <button type="submit" name="update_medication">Update Medication</button>
    </form>
</body>
</html>
