<?php

class File
{
    public $errors = [];
    public function validateAndUploadImage(PDO $conn, $data)
    {
        try {
            switch ($_FILES['image-file']['error']) {
                case UPLOAD_ERR_OK:
                    break;
                case UPLOAD_ERR_NO_FILE:
                    throw new Exception('No file was uploaded, please try again');
                    break;
                case UPLOAD_ERR_INI_SIZE:
                    throw new Exception('File is too large, please upload a smaller file');
                    break;
                default:
                    throw new Exception('An error occurred');
            }

            $mime_types = ['image/gif', 'image/png', 'image/jpeg'];

            $f_info = finfo_open(FILEINFO_MIME_TYPE);

            $mimetype = finfo_file($f_info, $_FILES['image-file']['tmp_name']);

            if (!in_array($mimetype, $mime_types)) {
                throw new Exception('Wrong file type!');
            }

            $path = pathinfo($_FILES['image-file']['name']);

            $base = $path['filename'];

            $base = preg_replace("/[^a-zA-Z0-9_-]/", "_", $base);

            $base = mb_substr($base, 0, 200);

            $filename = $base . "." . $path['extension'];

            $destination = "../uploads/$filename";

            $i = 1;

            while(file_exists($destination)){
                $filename = $base . "-$i." . $path['extension'];
                $destination = "../uploads/$filename";
                $i++;
            }

            if(move_uploaded_file($_FILES['image-file']['tmp_name'], $destination)){

                $data->saveInfo($conn, $filename);

                echo "File uploaded successfully!";

                $previous_image = $data->bikeImage;

                $data->bikeImageID = $data->getImageId($conn, $data->bikeLat, $data->bikeUserId)['image_id'];

                if($data->setImageFile($conn, $filename)){
                    if(file_exists($previous_image)){
                        unlink("../uploads/$previous_image");
                    }
                }
            } else{
                die('An error has occurred');
            }
        } catch (Exception $e) {
            $this->errors[] = $e->getMessage();
            return $this->errors;
        }
    }
}
