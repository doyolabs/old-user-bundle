<?php


namespace Doyo\UserBundle\Util;


use Doyo\UserBundle\Model\UserInterface;

class CanonicalFieldsUpdater implements CanonicalFieldsUpdaterInterface
{
    /**
     * @var CanonicalizerInterface
     */
    private $usernameCanonicalizer;

    /**
     * @var CanonicalizerInterface
     */
    private $emailCanonicalizer;

    /**
     * CanonicalFieldsUpdater constructor.
     * @param CanonicalizerInterface $usernameCanonicalizer
     * @param CanonicalizerInterface $emailCanonicalizer
     */
    public function __construct(CanonicalizerInterface $usernameCanonicalizer, CanonicalizerInterface $emailCanonicalizer)
    {
        $this->usernameCanonicalizer = $usernameCanonicalizer;
        $this->emailCanonicalizer = $emailCanonicalizer;
    }

    public function updateCanonicalFields(UserInterface $user)
    {
        $user->setUsernameCanonical($this->usernameCanonicalizer->canonicalize($user->getUsername()));
        $user->setEmailCanonical($this->emailCanonicalizer->canonicalize($user->getEmail()));
    }
}