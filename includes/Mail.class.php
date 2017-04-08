<?php

if (!defined('INSCRIPT')) {
    exit;
}

class Mail {

    public $headers = array();
    public $eol = "\r\n";
    private $_data;
    private $_type;

    public function __construct($data) {
        $this->_data = $data;
        $this->_type = $this->_data['user-subject'];
    }

    private function to() {
        switch ($this->_type) {
            case 'user':    return 'user@mail.net';
            case 'manager': return 'manager@mail.net';
            case 'admin':
            default:        return 'admin@mail.net';
        }
    }

    private function subject() {
        return sprintf('Message for %s', $this->_type);
    }

    private function message() {
        return sprintf('You got message from %s (email: %s)',
            $this->_data['user-name'], $this->_data['user-email']);
    }

    private function headers() {
        $this->headers[] = "From: noreply@mail.net";
        $this->headers[] = "X-Mailer: PHP/" . phpversion();
        $this->headers[] = "MIME-Version: 1.0";
        $this->headers[] = "Content-type: text/plain; charset=\"UTF-8\"";
        $this->headers[] = "Content-Transfer-Encoding: 8bit" . $this->eol;

        return implode($this->eol, $this->headers);
    }

    public function send() {
        $to = $this->to();
        $subject = $this->subject();

        $message = $this->message();
        $headers = $this->headers();

        @mail($to, $subject, $message, $headers);
    }
}