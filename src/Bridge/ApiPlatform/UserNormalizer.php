<?php


namespace Doyo\UserBundle\Bridge\ApiPlatform;

use Doyo\UserBundle\Model\UserInterface;
use Doyo\UserBundle\Util\CanonicalFieldsUpdaterInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;

class UserNormalizer implements ContextAwareDenormalizerInterface, DenormalizerAwareInterface
{
    use DenormalizerAwareTrait;

    private const ALREADY_CALLED = 'USER_NORMALIZER';

    /**
     * @var CanonicalFieldsUpdaterInterface
     */
    private $canonicalFieldsUpdater;

    /**
     * UserNormalizer constructor.
     * @param CanonicalFieldsUpdaterInterface $canonicalFieldsUpdater
     */
    public function __construct(
        CanonicalFieldsUpdaterInterface $canonicalFieldsUpdater
    )
    {
        $this->canonicalFieldsUpdater = $canonicalFieldsUpdater;
    }

    public function supportsDenormalization($data, $type, $format = null, array $context = [])
    {
        // Make sure we're not called twice
        if (isset($context[self::ALREADY_CALLED])) {
            return false;
        }

        return $type === 'App\Entity\User';
    }

    public function denormalize($data, $class, $format = null, array $context = [])
    {
        /* @var UserInterface $object */

        $context[self::ALREADY_CALLED] = true;

        $object = $this->denormalizer->denormalize($data, $class, $format, $context);

        $canonicalFieldsUpdater = $this->canonicalFieldsUpdater;
        if(isset($data['username'])){
            $usernameCanonical = $canonicalFieldsUpdater->canonicalizeUsername($data['username']);
            $object->setUsernameCanonical($usernameCanonical);
        }
        if(isset($data['email'])){
            $emailCanonical = $canonicalFieldsUpdater->canonicalizeEmail($data['email']);
            $object->setEmailCanonical($emailCanonical);
        }

        return $object;
    }
}