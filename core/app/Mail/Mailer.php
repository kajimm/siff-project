<?php
namespace Core\Mail;

use Core\Mail\MailerInterface;

/**
 * Clase de envio de email
 * @author  freyder rey <freyder@siff.com.co>
 * @method swiftmailer
 */
class Mailer implements MailerInterface
{
    /**
     * [$asunto mensaje]
     * @var [string]
     */
    private $asunto;

    /**
     * [$remite quien envia o quienes envian]
     * @var [array]
     */
    private $remite;

    /**
     * [$destino a quienes se envia el mensaje]
     * @var [array]
     */
    private $destino;

    /**
     * [$mensaje mensaje html || text]
     * @var [string]
     */
    private $mensaje;

    /**
     * [$adjuntos ruta de archivo a enviar adjun al mensaje]
     * @var [?string  Swift_Attachment]
     */
    private $adjuntos;

    /**
     * [$copiarA destinatarios a los cuales se le copiara el correo actual]
     * @var [array]
     */
    private $copiarA;

    /**
     * [$copiaBcc destinatarios a los cuales se le copiara ciegamente el remitente principal no se entera]
     * @var [array]
     */
    private $copiaBcc;
    /**
     * [$transport description]
     * Object Swift_SmtpTransport
     * @var [Object]
     */
    private $transport;

    /**
     * [$mailer description]
     * Object Swift_Mailer
     * @var [Object]
     */
    private $mailer;

    /**
     * [$message description]
     * Object Swift_Message
     * @var [Swift_Message]
     */
    private $email;

    /**
     * [$log log al enviar mensajes]
     * @var [LogInterface]
     */
    private $log;

    /**
     * [__construct description]
     * @param string $hostEmail [Correo electronico que se usara para enviar emails]
     * @param int    $port      [Puerto de conexion generalmente es 587 tls]
     * @param string $protocol  [Protocolo de conexion al servidor de correo POP]
     * @param string $username  [crendecial usuario]
     * @param string $pasword   [contraseña usuario]
     */
    public function __construct(string $hostEmail, int $port, string $protocol, string $username, string $pasword, $log)
    {
        $this->log = $log;
        $this->transport = (new \Swift_SmtpTransport($hostEmail, $port, $protocol))
            ->setUsername($username)
            ->setPassword($pasword);
        $this->mailer = new \Swift_Mailer($this->transport);
    }

    /**
     * [subject asunto o tema por el cual se envia el correo]
     * @param  string $asunto [asunto o tema]
     * @return [self]
     */
    public function subject(string $asunto): self
    {
        $this->asunto = $asunto;
        return $this;
    }
    /**
     * [from direccion de la cual se esta enviando el correo, pueden ser varios correos]
     * @param  array  $remitente [Formato ['correo' => 'nombre']]
     * @return [self]
     */
    public function from(array $remitente): self
    {
        $this->remite = $remitente;
        return $this;
    }

    /**
     * [to establece las direcciones de los destinatarios]
     * @param  array  $destination [Formato ['correo' => 'nombre']]
     * @return [self]
     */
    public function to(array $destination): self
    {
        $this->destino = $destination;
        return $this;
    }

    /**
     * [messague mensaje que se le enviara al destinatario text || html]
     * @param  string $message [mensaje]
     * @return [self]
     */
    public function messague(string $message): self
    {
        $this->mensaje = $message;
        return $this;
    }

    /**
     * [cc copiar el mensaje actual a otros destinatarios]
     * @param  array  $copyA [destinatarios formato["correo" => "nombre"]]
     * @return [self]
     */
    public function cc(array $copyA): self
    {
        $this->copiarA = $copyA;
        return $this;
    }

    /**
     * [bcc copia ciega el destinatario pricipal o principales no se enteran de la copia]
     * @param  array  $copyBcc [destinatarios a quien se les copiara formato ["correo" => "nombre"]]
     * @return [self]
     */
    public function bcc(array $copyBcc): self
    {
        $this->copiaBcc = $copyBcc;
        return $this;
    }

    /**
     * [attached Archivos adjuntos que se deseen enviar al destinatarios]
     * @param  string $pathFile [ruta del archivo]
     * @return [self]
     */
    public function attached(string $pathFile): self
    {

        $this->adjuntos = \Swift_Attachment::fromPath($pathFile);
        return $this;
    }

    /**
     * [send envia el mensaje el cual se crea con los metodos de la clase]
     * @return [bool] [true/false]
     */
    public function send(): bool
    {
        $this->email = (new \Swift_Message($this->asunto))
            ->setFrom($this->remite)
            ->setTo($this->destino)
            ->setBody($this->mensaje, 'text/html')
            ->addPart($this->mensaje, 'text/plain')
        ;

        /**
         * Si esta establecida la copia a otros remitentes se adjunta la copia y se envia
         */
        if ($this->copiarA) {
            $this->email->setCc($this->copiarA);
        }

        /**
         * Si se establece la copia siega se adjunta el remitente y se envia
         */
        if ($this->copiaBcc) {
            $this->email->setBcc($this->copiaBcc);
        }

        /**
         * si se establece ruta de un archivo se adjunta y se envia
         */
        if ($this->adjuntos) {
            $this->email->attach($this->adjuntos);
        }
        
        /**
         * [Registro de log para los envios de emails del sistema]
         */
       $fecha = json_encode($this->email->getDate());
       $para = json_encode($this->email->getFrom());
       $detino =json_encode($this->email->getTo());
       $tema = json_encode($this->email->getSubject());

       $result = $this->mailer->send($this->email);

        
        if ($result) {
            $this->log->newLog('INFO');
            $this->log->rewriteChanel('Email');
            $this->log->registrar(
                "E-mail enviado ID:: ".$this->email->getId().
                " Fecha:: ".$fecha.
                " From:: ".$para.
                " To:: ".$detino.
                " subject:: ".$tema,
                array('Usuarios' => $para));
            return true;
        } else {
            return false;
        }

    }
}


