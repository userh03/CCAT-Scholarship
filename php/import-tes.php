<?php
include("connection.php");
require '../PhpSpreadsheet/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

function sanitizeInput($input) {
    return filter_var($input, FILTER_SANITIZE_STRING);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if a file was uploaded
    if (isset($_FILES['excel_file']) && $_FILES['excel_file']['error'] === UPLOAD_ERR_OK) {
        $tempFilePath = sanitizeInput($_FILES['excel_file']['tmp_name']);
        $originalFileName = sanitizeInput($_FILES['excel_file']['name']);

        // Set the default time zone to Manila
        date_default_timezone_set('Asia/Manila');

        // Check if the file already exists in the uploaded_excel_files table
        $existingFileQuery = "SELECT COUNT(*) FROM `uploaded_excel_files` WHERE `file_name` = ?";
        $existingFileStmt = $pdo->prepare($existingFileQuery);
        $existingFileStmt->execute([$originalFileName]);
        $existingFileCount = $existingFileStmt->fetchColumn();

        if ($existingFileCount > 0) {
            // File already exists in the table, handle accordingly
            // Redirect or display an error message
            header("location: ../admin-docs.php?error=file_exists");
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

        // Mapping Excel column names to database column names with underscores removed
        $columnMapping = [
            '5-digit Control Number' => '5_digit_Control_Number',
            'Student Number' => 'Student_Number',
            'Last Name' => 'Last_Name',
            'Given Name' => 'Given_Name',
            'Middle Initial' => 'Middle_Initial',
            'Sex at Birth(M/F)' => 'Sex_at_Birth',
            'Birthdate(mm/dd/yy)' => 'Birthdate',
            'Degree Program' => 'Degree_Program',
            'Year Level' => 'Year_Level',
            'Academic Units Enrolled' => 'Academic_Units_Enrolled',
            'ZIP Code' => 'ZIP_Code',
            'E-mail address' => 'E_mail_address',
            'Phone Number' => 'Phone_Number',
            'Actual Tuition and Other School Fees' => 'Actual_Tuition_and_Other_School_Fees',
            'Billed Amount' => 'Billed_Amount',
            'Stipend' => 'Stipend',
            'Person with Disability' => 'Person_with_Disability',
            'Total Amount' => 'TOTAL_AMOUNT',
        ];          

        $columnNames = [];
        $firstRow = true;
        foreach ($worksheet->getRowIterator() as $row) {
            foreach ($row->getCellIterator() as $cell) {
                if ($firstRow) {
                    $columnNames[] = $cell->getValue();
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

                header("location: ../admin-docs.php?error=true");
                exit;
            }
        }

        // Replace Excel column names with corresponding database column names
        $columnNames = array_map(function ($excelColumnName) use ($columnMapping) {
            return $columnMapping[$excelColumnName];
        }, $columnNames);

        // Retrieve existing column names from the database table
        $existingColumns = [];
        $existingColumnsQuery = "SHOW COLUMNS FROM `tes_applicants_tbl`";
        $existingColumnsResult = $pdo->query($existingColumnsQuery);
        while ($row = $existingColumnsResult->fetch(PDO::FETCH_ASSOC)) {
            $existingColumns[] = $row['Field'];
        }

        // Create columns in the database table if they don't exist
        foreach ($columnNames as $columnName) {
            if (!in_array($columnName, $existingColumns)) {
                $addColumnSql = "ALTER TABLE `tes_applicants_tbl` ADD COLUMN `$columnName` VARCHAR(255)";
                $pdo->exec($addColumnSql);
            }
        }

        // Prepare the SQL statement
        $columnList = implode(', ', array_map(function ($name) {
            return "`$name`";
        }, $columnNames));

        $placeholderList = implode(', ', array_fill(0, count($columnNames), '?'));

        $sql = "INSERT INTO `tes_applicants_tbl` ($columnList) VALUES ($placeholderList)";
        $stmt = $pdo->prepare($sql);

        foreach ($worksheet->getRowIterator() as $row) {
            if ($row->getRowIndex() === 1) {
                continue; // Skip header row
            }

            $rowData = [];
            foreach ($row->getCellIterator() as $cell) {
                $rowData[] = $cell->getValue();
            }

            // Check if the data already exists in the database
            $existingDataSql = "SELECT COUNT(*) FROM `tes_applicants_tbl` WHERE ";
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

        header("location: ../admin-docs.php?success=true");
    } else {
        echo "Error uploading the file.";
    }
} else {
    echo "Invalid request method.";
}
?>
