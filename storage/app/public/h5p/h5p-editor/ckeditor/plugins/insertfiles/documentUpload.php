<?php
session_start();
if(isset($_FILES['upload'])){
  $errors= array();
  $file_ext = strtolower(end(explode('.',$_FILES['upload']['name'])));
  $file_name = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', pathinfo($_FILES['upload']['name'], PATHINFO_FILENAME)))).".".$file_ext;
  $file_size = $_FILES['upload']['size'];
  $file_tmp = $_FILES['upload']['tmp_name'];
  $file_type = $_FILES['upload']['type'];

  $extensions = array("pdf","docx","ppt", "doc", "rtf", "xls", "odt", "ods");
  $mime_types = array(
    // adobe
    'pdf' => 'application/pdf',
    // ms office
    'doc' => 'application/msword',
    'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'rtf' => 'application/rtf',
    'xls' => 'application/vnd.ms-excel',
    'ppt' => 'application/vnd.ms-powerpoint',
    // open office
    'odt' => 'application/vnd.oasis.opendocument.text',
    'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
  );

  if(in_array($file_ext,$extensions) === false || in_array($file_type, $mime_types) === false){
    $errors[] = "This type of file is not allowed.";
  }

  if($file_size > 30097152) {
    $errors[] = 'File size must not be greater than 30MB';
  }

  if(empty($errors) == true) {
    if(move_uploaded_file($_FILES["upload"]["tmp_name"], $_SESSION['doc_upload_path'].$file_name)){
      echo json_encode(['uploaded' => 1, "fileName" => $file_name, "url" => $_SESSION['doc_files_url'] . $file_name]);
      exit();
    }
    $errors[] = "File could not be uploaded!";
  }
  echo json_encode(['uploaded' => 0, "error" => [
    "message" => implode("\n", $errors)
  ]]);
  exit();
}
echo "<script>alert('No file Found for Upload!');</script>";
