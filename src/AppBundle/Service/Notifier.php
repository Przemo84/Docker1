<?php
/**
 * Created by PhpStorm.
 * User: przemek
 * Date: 27.04.17
 * Time: 10:17
 */

namespace AppBundle\Service;


use Monolog\Logger;

class Notifier
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;
    /**
     * @var Logger
     */
    private $logger;


    /**
     * Notifier constructor.
     * @param \Swift_Mailer $mailer
     */
    public function __construct(\Swift_Mailer $mailer, Logger $logger)
    {
        $this->mailer = $mailer;
        $this->logger = $logger;
    }

    public function send($message)
    {
        $this->logger->addInfo("Send some dummy msg");

        $this->mailer->send($message);
    }
}