<?php
//importando a biblioteca PHPMailer
require 'bibliotecas/PHPMailer/Exception.php';
require 'bibliotecas/PHPMailer/OAuth.php';
require 'bibliotecas/PHPMailer/PHPMailer.php';
require 'bibliotecas/PHPMailer/POP3.php'; //Especificações do protocolo de recebimento de email
require 'bibliotecas/PHPMailer/SMTP.php'; //Especificações do protocolo de envio de email    

//importar os namespaces e usar as classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

//criando uma classe
class Mensagem
{
    //Definindo atributos privados
    private $nome = null;
    private $email = null;
    private $telefone = null;
    private $motivo = null;
    private $mensagem = null;

    //Definindo atributos publicos
    public $status = array('codigo_status' => null, 'descricao_status' => '');

    //criando os métodos públicos
    public function __get($atributo)
    {
        return $this->$atributo;
    }

    public function __set($atributo, $valor)
    {
        return $this->$atributo = $valor;
    }

    public function mensagemValida()
    {
        //
        if (empty($this->nome) || empty($this->email) || empty($this->motivo) || empty($this->mensagem) || empty($this->telefone)) {
            return false;
        }
        return true;
    }
};

//criando um objeto a partir da classe
$mensagem = new Mensagem();
$mensagem->__set('nome', $_POST['nome']);
$mensagem->__set('email', $_POST['email']);
$mensagem->__set('telefone', $_POST['telefone']);
$mensagem->__set('motivo', $_POST['motivo']);
$mensagem->__set('mensagem', $_POST['mensagem']);

if (!$mensagem->mensagemValida()) {
    header('Location: index.html');
};
print_r($_POST);

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    
    //Server settings
    $mail->SMTPDebug = 0;                                       //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                       //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = //QUEM ENVIARA O EMAIL;         //SMTP username
    $mail->Password   = //SENHA DO EMAIL ACIMA;                     //SMTP password
    $mail->SMTPSecure = 'TLS';                                  //Enable implicit TLS encryption
    $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom(//EMAIL QUEM ENVIARÁ O EMAIL, //NOME DE QUEM ENVIOU);      //Remetente (Quem envia)
    $mail->addAddress(//EMAIL DE QUEM RECEBERÁ, //NOME RECEBERÁ O EMAIL);   //Quem recebe (Destinatário)
    #$mail->addAddress('ellen@example.com');                    //Quem mais recebe
    #$mail->addReplyTo('info@example.com', 'Information');      //Terceiro que irá receber a resposta do do destinatário
    #$mail->addCC('cc@example.com');                            //Destinatários em cópia
    #$mail->addBCC('bcc@example.com');                          //Cópia oculta

    //Attachments
    #$mail->addAttachment('/var/tmp/file.tar.gz');              //Add attachments
    #$mail->addAttachment('/tmp/image.jpg', 'new.jpg');         //Optional name

    //Content
    $mail->isHTML(true);                                        //Set email format to HTML
    $mail->Subject = $mensagem->__get('motivo');                     //Assunto do email
    $mail->Body    = "Quem enviou: " .$mensagem->__get('nome', $_POST['nome']) ." (" .$mensagem->__get('email', $_POST['email']) ."), telefone: " .$mensagem->__get('telefone', $_POST['telefone']).". Mensagem: " .$mensagem->__get('mensagem', $_POST['mensagem']); //Conteudo do email com html*/
    //
    $mail->AltBody = 'É necessário utilizar um client que suporte html para ver o email completo'; //Conteudo do email sem html

    $mail->send();

    //$mensagem->status['codigo_status'] = 1;
    //$mensagem->status['descricao_status'] = 'Email enviado com sucesso';
    header('Location: ../index.html');
} catch (Exception $e) {
    $mensagem->status['codigo_status'] =2;
    $mensagem->status['descricao_status'] = 'Não foi possivel enviar esse email, por favor tente mais tarde.' . $mail->ErrorInfo;
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <title>App mail Send</title>
</head>

<body>

    <div class="container">
        <div class="py-3 text-center">            
            <h2>Send Mail</h2>
            <p class="lead">Seu app de envio de e-mails particular!</p>
        </div>

        <div class="row">
            <div class="col-md-12">
                <?php if ($mensagem->status['codigo_status'] == 1) { ?>
                    <div class="container">
                        <h1 class="display-4 text-success">Email Enviado com Sucesso</h1>
                        <p>
                            <?php $mensagem->status['descricao_status']; ?>
                        </p>
                        <a href="contato.html" class="btn btn-success btn-lg mt-5 text-white">Voltar</a>
                    </div>
                <?php }else{ ?>
                    <div class="container">
                        
                        <p>
                            <?php echo $mensagem->status['descricao_status']; ?>
                        </p>
                        <a href="contato.html" class="btn btn-danger btn-lg mt-5 text-white">Voltar</a>
                <?php }; ?>                
            </div>
        </div>
    </div>

</body>

</html>