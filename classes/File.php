<?php

class File
{
    public $errors = [];
    public function validateAndUploadImage(PDO $conn, $data)
    {
        try {
            switch ($_FILES['file']['error']) {
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

            $base = preg_replace("/[a-zA-Z0-9_-]/", "_", $base);

            $base = mb_substr($base, 0, 200);

            $filename = $base . "." . $path['extension'];

            $destination = "/bike-app/admin/uploads/$filename";

            $i = 1;

            while(file_exists($destination)){
                $filename = $base . "-$i." . $path['extension'];
                $destination = "/bike-app/admin/uploads/$filename";
                $i++;
            }

            
            if(move_uploaded_file($_FILES['image-file']['tmp_name'], $destination)){
                echo "File was uploaded successfully";

                $previous_image = $data->bikeImage;

                if($data->setImageFile($conn, $filename)){
                    if($previous_image){
                        unlink("/bike-app/admin/uploads/$previous_image");
                    }
                }
            } else{
                exit('An error has occurred');
            }
        } catch (Exception $e) {
            $this->errors[] = $e->getMessage();
            return $this->errors;
        }
    }
}
