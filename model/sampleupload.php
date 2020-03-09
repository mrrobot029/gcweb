
        $file_name = $_FILES['file']['name'];
        $target_dir = "../filesFP/".$file_name;

        move_uploaded_file($_FILES['file']['tmp_name'], $target_dir)
