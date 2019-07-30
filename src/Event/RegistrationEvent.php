<?php

namespace App\Event;

use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class RegistrationEvent
 * @package App\Event
 */
class RegistrationEvent extends Event
{
    const NAME = 'registration';

    /**
     * @var User
     */
    protected $user;

    /**
     * RequestSuccessEvent constructor.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }
}
