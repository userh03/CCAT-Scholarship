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
            'Firstname' => 'first_name',
            'Lastname' => 'last_name',
            'Subject Code' => 'subject_code',
            'Subject Name' => 'subject_name',
            'Semester' => 'semester',
            'Grade' => 'grade',
            'Year' => 's_year',
            'Units' => 'units',
        ];

        $columnNames = [];
        $firstRow = true;
        $data = [];
        foreach ($worksheet->getRowIterator() as $row) {
            $rowData = [];
            foreach ($row->getCellIterator() as $cell) {
                if ($firstRow) {
                    // Store column names
                    $columnNames[] = filter_var($cell->getValue(), FILTER_SANITIZE_STRING);
                } else {
                    // Sanitize and convert values to strings, except for 'Units' (cast to float)
                    $rowData[] = ($columnMapping[$cell->getColumn()] === 'units')
                        ? filter_var($cell->getValue(), FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION)
                        : filter_var($cell->getValue(), FILTER_SANITIZE_STRING);
                }
            }
            if (!$firstRow) {
                $data[] = $rowData;
            }
            $firstRow = false;
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
            return $columnMapping[$excelColumnName];
        }, $columnNames);

        // Retrieve existing column names from the database table
        $existingColumns = [];
        $existingColumnsQuery = "SHOW COLUMNS FROM `docs_tbl`";
        $existingColumnsResult = $pdo->query($existingColumnsQuery);
        while ($row = $existingColumnsResult->fetch(PDO::FETCH_ASSOC)) {
            $existingColumns[] = $row['Field'];
        }

        // Create columns in the database table if they don't exist
        foreach ($columnNames as $columnName) {
            if (!in_array($columnName, $existingColumns)) {
                $addColumnSql = "ALTER TABLE `docs_tbl` ADD COLUMN `$columnName` VARCHAR(255)";
                $pdo->exec($addColumnSql);
            }
        }

        // Prepare the SQL statement
        $columnList = implode(', ', array_map(function ($name) {
            return "`$name`";
        }, $columnNames));

        $placeholderList = implode(', ', array_fill(0, count($columnNames), '?'));

        $sql = "INSERT INTO `docs_tbl` ($columnList) VALUES ($placeholderList)";
        $stmt = $pdo->prepare($sql);

        foreach ($data as $row) {
            // Check if the data already exists in the database
            $existingDataSql = "SELECT COUNT(*) FROM `docs_tbl` WHERE ";
            $whereClauses = [];
            foreach ($columnNames as $columnName) {
                $whereClauses[] = "`$columnName` = ?";
            }
            $existingDataSql .= implode(' AND ', $whereClauses);
            $existingDataStmt = $pdo->prepare($existingDataSql);
            $existingDataStmt->execute($row);
            $existingDataCount = $existingDataStmt->fetchColumn();

            // Ensure student_id_number is not null
            if ($existingDataCount == 0) {
                // Insert the row if it doesn't exist in the database
                $stmt->execute($row);
            }
        }

        header("location: ../developer-docs.php?success=true");
    } else {
        echo "Error uploading the file.";
    }
}
?>
