<?php

namespace App\EventSubscriber;

use App\Entity\SendGridSchedule;
use App\Event\EmailConfirm;
use App\Event\RecurringPaymentFailure;
use App\Event\RecurringPaymentRemove;
use App\Event\PayoutRequestEvent;
use App\Event\RegistrationEvent;
use App\Event\ResetPasswordEvent;
use App\Event\RequestSuccessEvent;
use App\Repository\ChildRepository;
use App\Service\SendGridService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Psr\Log\LoggerInterface;

class SendGridSubscriber implements EventSubscriberInterface
{
    /**
     * @var SendGridService
     */
    private $sendGrid;

    /**
     * @var UrlGeneratorInterface
     */
    private $generator;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(SendGridService $sg, UrlGeneratorInterface $generator, EntityManagerInterface $em, LoggerInterface $logger)
    {
        $this->sendGrid = $sg;
        $this->generator = $generator;
        $this->em = $em;
        $this->logger = $logger;
    }

    /**
     * @param RegistrationEvent $event
     *
     * @throws \Symfony\Component\Routing\Exception\InvalidParameterException
     * @throws \Symfony\Component\Routing\Exception\MissingMandatoryParametersException
     * @throws \Symfony\Component\Routing\Exception\RouteNotFoundException
     */
    public function onRegistration(RegistrationEvent $event)
    {        
        $user = $event->getUser();
        $mail = $this->sendGrid->getMail(
            $user->getEmail(),
            $user->getFirstName(),
            [
                'first_name' => $user->getFirstName(),
                'confirm_url' => $this->generator->generate('code_confirm', [
                    'code' => $user->getRefCode(),
                    'email' => $user->getEmail()
                ], 0)
            ],
            'Регистрация на сайте'
        );
        
        $mail->setTemplateId('d-536b9f1c13cf4596a92513f67a076543');
        try {
            $this->sendGrid->send($mail);
        }
        catch (Exception $e) {
            $this->logger->error('Caught exception: '.  $e->getMessage(). "\n");
        }        

        // Письмо О фонде
        $this->em->persist(
            (new SendGridSchedule())
            ->setEmail($user->getEmail())
            ->setName($user->getFirstName())
            ->setBody([
                'first_name' => $user->getFirstName()
            ])
            ->setTemplateId('d-f79a687fd8fa49089ab62a18445ce6fc')
            ->setSendAt(
                \DateTimeImmutable::createFromMutable(
                    (new \DateTime())
                    ->add(new \DateInterval('P35D'))
                    ->setTime(12, 0, 0)
                )
            )
        );

        // Писмьмо О Фонде - на что живет фонд, команда
        $this->em->persist(
            (new SendGridSchedule())
            ->setEmail($user->getEmail())
            ->setName($user->getFirstName())
            ->setBody([
                'first_name' => $user->getFirstName()
            ])
            ->setTemplateId('d-30f3d027463c430f8f743356307a77bb')
            ->setSendAt(
                \DateTimeImmutable::createFromMutable(
                    (new \DateTime())
                    ->add(new \DateInterval('P65D'))
                    ->setTime(12, 0, 0)
                )
            )
        );

        $this->em->flush();
    }

    /**
     * @param RequestSuccessEvent $event
     *
     * @return \SendGrid\Response|void
     */
    public function onRequestSuccess(RequestSuccessEvent $event)
    {
        $req = $event->getRequest();
        $user = $req->getUser();
        $mail = $this->sendGrid->getMail(
            $user->getEmail(),
            $user->getFirstName(),
            [
                'first_name' => $user->getFirstName()
            ]
        );
        $mail->setTemplateId('d-07888ea4b98c44278e218c6d1f365549');

        return $this->sendGrid->send($mail);
    }

    public function onEmailConfirm(EmailConfirm $event)
    {        
        $user = $event->getUser();
        $mail = $this->sendGrid->getMail(
            $user->getEmail(),
            $user->getFirstName(),
            [
                'first_name' => $user->getFirstName()
            ],
            'Приветствие'
        );        
        $mail->setTemplateId('d-c104643da6d04f6884baf477a2f819a1');
        try {
            $this->sendGrid->send($mail);
        }
        catch (Exception $e) {
            $this->logger->error('Caught exception: '.  $e->getMessage(). "\n");
        }            
    }
    
    public function onPayoutRequest(PayoutRequestEvent $event)
    {       
        $mail_to = 'fond.detyam@mail.ru';

        $user = $event->getUser();
        $mail = $this->sendGrid->getMail(
            $mail_to,
            $user->getFirstName(),
            [
                'first_name' => $user->getFirstName()
            ],
            'Запрос вывода средств'
        );        

        $mail->addContent("text/plain", 
            "Запрос: " .
            "\n Имя: " . $user->getFirstName() . 
            "\n Почта: " . $user->getEmail() . 
            "\n Баллы: " . $user->getRewardSum());
        // $mail->setTemplateId('d-c104643da6d04f6884baf477a2f819a1');
        try {
            $this->sendGrid->send($mail);
        }
        catch (Exception $e) {
            $this->logger->error('Caught exception: '.  $e->getMessage(). "\n");
        }            
    }

    public function onResetPassword(ResetPasswordEvent $event)
    {        
        $user = $event->getUser();
        $mail = $this->sendGrid->getMail(
            $user->getEmail(),
            $user->getFirstName(),
            [        
                'first_name' => $user->getFirstName(),        
                'reset_url' => $this->generator->generate('reset_password', [
                    'email' => $user->getEmail(),
                    'token' => $user->getPass()    
                ], 0)                
            ],
            'Восстановление доступа'
        );        
        $mail->setTemplateId('d-d9cc7d4306914b0dbf7090e4bacae96a');
        try {
            $this->sendGrid->send($mail);
        }
        catch (Exception $e) {
            $this->logger->error('Caught exception: '.  $e->getMessage(). "\n");
        }            
    }

    public function onRecurringPaymentFailure(RecurringPaymentFailure $event)
    {
        $user = $event->getRequest()->getUser();
        $mail = $this->sendGrid->getMail(
            $user->getEmail(),
            $user->getFirstName(),
            [
                'first_name' => $user->getFirstName()
            ]
        );
        $mail->setTemplateId('d-a5e99ed02f744cb1b2b8eb12ab4764b5');

        return $this->sendGrid->send($mail);
    }

    public function onRecurringPaymentRemove(RecurringPaymentRemove $event)
    {
        $user = $event->getRecurringPayment()->getUser();
        $this->em->persist((new SendGridSchedule())
            ->setEmail($user->getEmail())
            ->setName($user->getFirstName())
            ->setBody([
                'first_name' => $user->getFirstName()
            ])
            ->setTemplateId('d-eaae4848c985425f90e2b968d9364630')
            ->setSendAt(
                \DateTimeImmutable::createFromMutable(
                    (new \DateTime())
                    ->add(new \DateInterval('P1D'))
                )
            ));
        $this->em->flush();
    }

    public static function getSubscribedEvents()
    {
        return [
            'account.payoutRequestEvent' => 'onPayoutRequest',
            'registration' => 'onRegistration',
            'request.success' => 'onRequestSuccess',
            'user.emailConfirm' => 'onEmailConfirm',
            'user.resetPassword' => 'onResetPassword',
            'recurring_payment.failure' => 'onRecurringPaymentFailure',
            'recurring_payment.remove' => 'onRecurringPaymentRemove'
        ];
    }
}
