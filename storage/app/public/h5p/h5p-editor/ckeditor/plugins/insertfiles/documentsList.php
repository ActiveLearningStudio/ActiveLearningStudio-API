<?php
session_start();

$protocol = !empty($_SERVER['HTTPS']) ? 'https://' : 'http://';
$_SESSION['doc_upload_dir'] = "/files/";
$_SESSION['doc_upload_path'] = __DIR__ . $_SESSION['doc_upload_dir'];
$_SESSION['doc_files_url'] = $protocol . $_SERVER['SERVER_NAME'] . pathinfo("$_SERVER[REQUEST_URI]", PATHINFO_DIRNAME) . $_SESSION['doc_upload_dir'];
$documents = array();
//$fileNames = glob( $_SERVER['DOCUMENT_ROOT'] . $_SESSION['doc_upload_dir'] . '*.*' );
$fileNames = glob( $_SESSION['doc_upload_path'] . '*.*' );
if(isset($_GET['del']) && $_GET['del']){
  foreach($fileNames as $file){ // iterate files
    if(is_file($file))
      unlink($file); // delete file
  }
  echo json_encode( [] );
  exit();
}
foreach( $fileNames as $fileName )
{
  $document = basename( $fileName );
  $documents[] = array( 'name' => $document, 'url' => $_SESSION['doc_files_url'] . $document );
}
echo json_encode( $documents );
