<?php

/*
 * This file is part of the DoyoUserBundle project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

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
     */
    public function __construct(
        CanonicalFieldsUpdaterInterface $canonicalFieldsUpdater
    ) {
        $this->canonicalFieldsUpdater = $canonicalFieldsUpdater;
    }

    public function supportsDenormalization($data, $type, $format = null, array $context = [])
    {
        // Make sure we're not called twice
        if (isset($context[self::ALREADY_CALLED])) {
            return false;
        }

        return 'App\Entity\User' === $type;
    }

    public function denormalize($data, $class, $format = null, array $context = [])
    {
        /* @var UserInterface $object */

        $context[self::ALREADY_CALLED] = true;

        $object = $this->denormalizer->denormalize($data, $class, $format, $context);

        $canonicalFieldsUpdater = $this->canonicalFieldsUpdater;
        if (isset($data['username'])) {
            $usernameCanonical = $canonicalFieldsUpdater->canonicalizeUsername($data['username']);
            $object->setUsernameCanonical($usernameCanonical);
        }
        if (isset($data['email'])) {
            $emailCanonical = $canonicalFieldsUpdater->canonicalizeEmail($data['email']);
            $object->setEmailCanonical($emailCanonical);
        }

        return $object;
    }
}
