<?php
include("connection.php");
require '../PhpSpreadsheet/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if a file was uploaded
    if (isset($_FILES['excel_file']) && $_FILES['excel_file']['error'] === UPLOAD_ERR_OK) {
        $tempFilePath = $_FILES['excel_file']['tmp_name'];
        $originalFileName = $_FILES['excel_file']['name'];

        // Set the default time zone to Manila
        date_default_timezone_set('Asia/Manila');

        // Sanitize and convert uploaded filename to string
        $originalFileName = filter_var($originalFileName, FILTER_SANITIZE_STRING);

        // Check if the file already exists in the uploaded_excel_files table
        $existingFileQuery = "SELECT COUNT(*) FROM `uploaded_excel_files` WHERE `file_name` = ?";
        $existingFileStmt = $pdo->prepare($existingFileQuery);
        $existingFileStmt->execute([$originalFileName]);
        $existingFileCount = $existingFileStmt->fetchColumn();

        if ($existingFileCount > 0) {
            // File already exists in the table, handle accordingly
            // Redirect or display an error message
            header("location: ../developer-docs.php?error=file_exists");
            exit;
        }

        // Save the original filename and upload date/time into the uploaded_excel_files table
        $insertFileInfoSql = "INSERT INTO `uploaded_excel_files` (`file_name`, `upload_date`) VALUES (?, ?)";
        $stmt = $pdo->prepare($insertFileInfoSql);
        $uploadDateTime = date('Y-m-d H:i:s');
        $stmt->execute([$originalFileName, $uploadDateTime]);

        // Get the file extension
        $extension = pathinfo($originalFileName, PATHINFO_EXTENSION);

        // Generate a unique file name to avoid conflicts
        $fileName = uniqid() . '.' . $extension;

        // Move the uploaded file to a desired directory
        $destination = '../documents/' . $fileName;
        move_uploaded_file($tempFilePath, $destination);

        // Load the uploaded Excel file
        $spreadsheet = IOFactory::load($destination);
        $worksheet = $spreadsheet->getActiveSheet();

        // Mapping Excel column names to database column names
        $columnMapping = [
            'Student ID Number' => 'student_id_number',
            'Firstname' => 'fname',
            'Lastname' => 'lname',
            'Email' => 'email',
            'Department' => 'department',
            'Section' => 'section',
            'Mobile Number' => 'mobile',
        ];

        $columnNames = [];
        $firstRow = true;
        foreach ($worksheet->getRowIterator() as $row) {
            foreach ($row->getCellIterator() as $cell) {
                if ($firstRow) {
                    $columnNames[] = filter_var($cell->getValue(), FILTER_SANITIZE_STRING);
                }
            }
            $firstRow = false;
            break;  // Only need to read the first row for column names
        }

        // Check if the Excel column names match the expected mapping
        foreach ($columnNames as $excelColumnName) {
            if (!isset($columnMapping[$excelColumnName])) {
                // Column name not found in the mapping, stop the uploading process
                // Remove the inserted record in uploaded_excel_files
                $deleteFileInfoSql = "DELETE FROM `uploaded_excel_files` WHERE `file_name` = ?";
                $stmt = $pdo->prepare($deleteFileInfoSql);
                $stmt->execute([$originalFileName]);

                header("location: ../developer-docs.php?error=true");
                exit;
            }
        }

        // Replace Excel column names with corresponding database column names
        $columnNames = array_map(function ($excelColumnName) use ($columnMapping) {
            return $columnMapping[filter_var($excelColumnName, FILTER_SANITIZE_STRING)];
        }, $columnNames);

        // Add the two columns with default values to the $columnNames array
        $columnNames[] = 'profile_picture';
        $columnNames[] = 'isValid';

        // Retrieve existing column names from the database table
        $existingColumns = [];
        $existingColumnsQuery = "SHOW COLUMNS FROM `students_tbl`";
        $existingColumnsResult = $pdo->query($existingColumnsQuery);
        while ($row = $existingColumnsResult->fetch(PDO::FETCH_ASSOC)) {
            $existingColumns[] = $row['Field'];
        }

        // Create columns in the database table if they don't exist
        foreach ($columnNames as $columnName) {
            if (!in_array($columnName, $existingColumns)) {
                $addColumnSql = "ALTER TABLE `students_tbl` ADD COLUMN `$columnName` VARCHAR(255)";
                $pdo->exec($addColumnSql);
            }
        }

        // Prepare the SQL statement
        $columnList = implode(', ', array_map(function ($name) {
            return "`$name`";
        }, $columnNames));

        $placeholderList = implode(', ', array_fill(0, count($columnNames), '?'));

        $sql = "INSERT INTO `students_tbl` ($columnList) VALUES ($placeholderList)";
        $stmt = $pdo->prepare($sql);

        foreach ($worksheet->getRowIterator() as $row) {
            if ($row->getRowIndex() === 1) {
                continue; // Skip header row
            }

            $rowData = [];
            foreach ($row->getCellIterator() as $cell) {
                $rowData[] = filter_var($cell->getValue(), FILTER_SANITIZE_STRING);
            }

            // Add default values for the new columns
            $rowData[] = filter_var('images/user.png', FILTER_SANITIZE_STRING); // profile_picture
            $rowData[] = filter_var(1, FILTER_SANITIZE_STRING); // isValid

            // Check if the data already exists in the database
            $existingDataSql = "SELECT COUNT(*) FROM `students_tbl` WHERE ";
            $whereClauses = [];
            foreach ($columnNames as $columnName) {
                $whereClauses[] = "`$columnName` = ?";
            }
            $existingDataSql .= implode(' AND ', $whereClauses);
            $existingDataStmt = $pdo->prepare($existingDataSql);
            $existingDataStmt->execute($rowData);
            $existingDataCount = $existingDataStmt->fetchColumn();

            // Ensure student_id_number is not null
            if ($existingDataCount == 0) {
                // Insert the row if it doesn't exist in the database
                $stmt->execute($rowData);
            }
        }

        header("location: ../developer-docs.php?success=true");
    } else {
        echo "Error uploading the file.";
    }
} else {
    echo "Invalid request method.";
}
?>
