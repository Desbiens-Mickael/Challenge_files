<?php
// Je vérifie si le formulaire est soumis comme d'habitude
if ($_SERVER['REQUEST_METHOD'] === "POST") {
   
    // Securité en php
    // chemin vers un dossier sur le serveur qui va recevoir les fichiers uploadés (attention ce dossier doit être accessible en écriture)
    $uploadDir = 'public/uploads/';
   
    // Je récupère l'extension du fichier
    $extension = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);

    // Les extensions autorisées
    $authorizedExtensions = ['jpg', 'jpeg', 'png', 'webp'];

    // On donne au fichier un nom unique
    
    $uploadFile = uniqid() . "." . $extension ;
   
    // Le poids max géré par PHP par défaut est de 2M, ici on le paramètre sur 1M
    $maxFileSize = 1000000 ;
   
    // Je sécurise et effectue mes tests

    /****** Si l'extension est autorisée *************/
    if (!in_array(strtolower($extension), $authorizedExtensions) && !isset($_POST['delete'])) {
        $errors[] = 'Veuillez sélectionner une image de type Jpg ou Jpeg ou Png ou webp!';
    }

    /****** On vérifie si l'image existe et si le poids est autorisé en octets *************/
    if (file_exists($_FILES['avatar']['tmp_name']) && filesize($_FILES['avatar']['tmp_name']) > $maxFileSize
    || !filesize($_FILES['avatar']['tmp_name']) && !isset($_POST['delete'])) {
        $errors[] = "Votre fichier doit faire moins de 1M !";
       
    }
   
  
    // S'il n'y a pas d'erreurs on upload le fichier
    if (empty($errors) && !isset($_POST['delete'])) {
        move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadFile);
        // header('Location: #');
    }
}
?>

<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"
          rel="stylesheet" 
          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" 
          crossorigin="anonymous">
    <title>Document</title>
</head>

<body class="container h-100 d-flex flex-column justify-content-center">
    <?php if(!empty($_FILES['avatar']['name'])): ?>
        <div class="d-flex flex-row justify-content-center mt-5">   
            <img src=<?=$uploadFile?> alt="...">
            <div class="text-center">
                <h3><?=$_POST['firstname']?></h3> 
                <h3><?=$_POST['lastname']?></h3>    
                <h4><?=$_POST['age']?></h4>  
            </div>
        </div>
    <?php endif; ?>

    <!-- On affiche les erreurs -->
    <?php if(!empty($errors)): ?>
        <div class="h-25 d-flex flex-column justify-content-center align-items-center bg-danger rounded-pill">
            <?php foreach($errors as $error): ?>
                <h2 class="text-light text-center"><?=$error?></h2>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
   
    <form method="POST" enctype="multipart/form-data" class=" h-100 d-flex flex-column justify-content-center align-items-center">
        <label for="imageUpload" class="mb-2">Choisissez le fichier à uploader </label>
        <input type="file" name="avatar" id="imageUpload" class="mb-2" />

        <label for="firstname" class="mb-2">Entrez votre prénom </label>
        <input type="text" name="firstname" id="firstname" class="mb-2"/>

        <label for="lastname" class="mb-2">Entrez votre nom</label>
        <input type="text" name="lastname" id="lastname" class="mb-2"/>

        <label for="age" class="mb-2">Entrez votre âge</label>
        <input type="number" name="age" id="age" class="mb-2"/>

        <button name="send" class="btn btn-primary mb-5 w-25">Send</button>
    </form>
</body>

</html>