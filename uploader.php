<?php
$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

$data = [
    "name" => null,
    "url" => null,
    "status_code" => null,
    "error" => null
];

function stripFile($in){
    $pieces = explode("/", $in); 
    if(count($pieces) < 4) return $in . "/";
    if(strpos(end($pieces), ".") !== false){
        array_pop($pieces); 
    }elseif(end($pieces) !== ""){
        $pieces[] = "";
    }
    return implode("/", $pieces);
}

// Check if file already exists
if (file_exists($target_file)) {
    $data = [
        "status_code" => 302,
        "error" => "Alredy exist"
    ];
    $uploadOk = 0;
}
// Check file size
if ($_FILES["fileToUpload"]["size"] > 500000) {
    $data = [
        "status_code" => 413,
        "error" => "File too large."
    ];
    $uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif"&& $imageFileType != "mp4"&& $imageFileType != "3gp"&& $imageFileType != "wmv" ) {
    $data = [
        "status_code" => 415,
        "error" => "Please upload valid image or video."
    ];
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk != 0) {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/$_SERVER[REQUEST_URI]";
        $actual_link = stripFile($actual_link);
        $actual_link = $actual_link ."/". $target_dir."/".$_FILES["fileToUpload"]["name"];

        $data = [
            "name" => $_FILES["fileToUpload"]["name"],
            "url" => $actual_link,
            "status_code" => 200,
        ];

    } else {
        $data = [
            "status_code" => 400,
            "error" => "Unknown error"
        ];
    }
}
echo json_encode($data);

?>