<?php
namespace Core\Mail;

interface MailerInterface
{

    /**
     * [subject asunto o tema por el cual se envia el correo]
     * @param  string $asunto [asunto o tema]
     * @return [self]
     */
    public function subject(string $asunto);
    /**
     * [from direccion de la cual se esta enviando el correo, pueden ser varios correos]
     * @param  array  $remitente [Formato ['correo' => 'nombre']]
     * @return [self]
     */
    public function from(array $remitente);

    /**
     * [to establece las direcciones de los destinatarios]
     * @param  array  $destination [Formato ['correo' => 'nombre']]
     * @return [self]
     */
    public function to(array $destination);

    /**
     * [messague mensaje que se le enviara al destinatario text || html]
     * @param  string $message [mensaje]
     * @return [self]
     */
    public function messague(string $message);

    /**
     * [cc copiar el mensaje actual a otros destinatarios]
     * @param  array  $copyA [destinatarios formato["correo" => "nombre"]]
     * @return [self]
     */
    public function cc(array $copyA);

    /**
     * [bcc copia ciega el destinatario pricipal o principales no se enteran de la copia]
     * @param  array  $copyBcc [destinatarios a quien se les copiara formato ["correo" => "nombre"]]
     * @return [self]
     */
    public function bcc(array $copyBcc);

    /**
     * [attached Archivos adjuntos que se deseen enviar al destinatarios]
     * @param  string $pathFile [ruta del archivo]
     * @return [self]
     */
    public function attached(string $pathFile);

    /**
     * [send envia el mensaje el cual se crea con los metodos de la clase]
     * @return [bool] [true/false]
     */
    public function send(): bool;
}
