
<?php
session_start();
if(isset($_POST['send'])){
    //extraction des variables
    extract($_POST);
    //verifions si les variables existents et ne sont pas vides
    if(isset($username) && $username != "" &&
       isset($email) && $email != "" &&
       isset($phone) && $phone != "" &&
       isset($message) && $message != ""){

        //envoyé l'email
        //le destinataire (votre adresse mail)
        $to = "parazonichris@gmail.com";
        //objet du mail
        $subject = "Vous avez reçu un message de : " . $email;
        $message = "
              <p> Vous avez reçu un message de <strong> ".$email."</strong></p>
              <p><strong>Nom :</strong> ".$username."</p>
              <p><strong>Téléphone :</strong> ".$phone."</p>
              <p><strong>Message :</strong> ".$message."</p>
              ";

    //always set content-type when sending HTML email
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers = "Content-type:text/html;charset=UTF-8" . "\r\n";

    //More headers
    $headers = 'From: <'.$email.'>' . "\r\n";

    //Envoi du mail
    $send = mail($to,$subject,$message,$headers);
    //verification de l'envoi
    if(!$send){
        $_SESSION['succes_message'] = "message envoyé";
        
    }else{
        $info = "message non envoyé";
       
    }


    }else{
        //si elle sont vides
        $info = "Veuillez remplir tous les champs !";
        
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send an mail</title>
    <link rel="stylesheet" href="path/to/your/css/style.css"> <!-- Ajouter ton fichier CSS ici -->
        <!-- Favicons -->
        <link href="../assets1/img/logo/file-N5hSrFMbCgXESwrU8guWU6.webp" rel="icon">
  <link href="../assets1/img/logo/file-N5hSrFMbCgXESwrU8guWU6.webp" rel="SINFED_Image">

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,600;1,700&family=Amatic+SC:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Inter:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

</head>
<style> 
*{
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Roboto', sans-serif;
}
body{
    background-color: #e4f2fe;
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100vh;
    flex-direction: column;
}
.request_message{
    width: 400px;
    text-transform: capitalize;
    background-color: #fff;
    text-align: center;
    color: green;
    font-size: 13px;
    padding: 14px;
    margin-bottom: 20px;
    border-radius: 20px;
    box-shadow: 0 0 8px rgba(0,0,0,0.1);
    animation: anime 0.5s ease-out;
}
/*animation*/
@keyframes anime{
    from{
        transform: translateY(-70px);
    }
}
form{
    background-color: #fff;
    border-radius: 20px;
    display: flex;
    flex-direction: column;
    box-shadow: 0 0 8px rgba(0,0,0,0.1);
    width: 400px;
    padding: 20px;
}
form h2{
    color: #333;
    font-size: 35px;
    margin-bottom: 5px;
    text-align: center;
}
label{
    font-size: 16px;
    font-weight: 400;
    margin: 5px 0;
    text-transform: capitalize;
    letter-spacing: 1px;
}
input , textarea{
    padding: 8px;
    resize: none;
    outline: 0;
    border: 1px solid #999;
    border-radius: 4px;
}
button{
    background-color: #5970e3;
    color: #fff;
    border: 0;
    border-radius: 4px;
    cursor: pointer;
    margin-top: 18px;
    letter-spacing: 1px;
    text-transform: uppercase;
    padding: 10px;
    transition: 0.5s;
}
button:hover{
    transform: scale(1.02);
}
</style>
<body>
    <?php
    //afficher le message d'erreur
    if(isset($info)){?>
         <p class="request_message" style="color:red">
            <?=$info?>
         </p>
         <?php
    }
    ?>

<?php
    //afficher le message d'erreur
    if(isset($_SESSION['succes_message'])){?>
         <p class="request_message" style="color:green">
            <?=$_SESSION['succes_message']?>
         </p>
         <?php
    }
    ?>

<form action="" method="POST">
    <label>Username</label>
    <input type="text" name="username">
    <label>Email</label>
    <input type="email" name="email">
    <label>phone</label>
    <input type="number" name="phone">
    <label>Message</label>
    <textarea name="message"cols="30" rows="10"></textarea>
    <button name="send">send</button>
</form>
</body>
</html>