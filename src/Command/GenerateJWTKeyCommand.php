<?php

namespace Doyo\UserBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class GenerateJWTKeyCommand extends Command
{
    private $pubKeyPath;

    private $secKeyPath;

    private $passphrase;

    public function __construct(
        $secKeyPath, $pubKeyPath, $passphrase
    ) {
        $this->secKeyPath = $secKeyPath;
        $this->pubKeyPath = $pubKeyPath;
        $this->passphrase = $passphrase;
        parent::__construct('doyo:generate:jwt-keys');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $pubKeyPath = $this->pubKeyPath;
        $secKeyPath = $this->secKeyPath;
        $passphrase = $this->passphrase;

        //@codeCoverageIgnoreStart
        if (!is_dir($dir = \dirname($pubKeyPath))) {
            $output->writeln('Creating directories in '.$pubKeyPath);
            mkdir($dir, 0777, true);
        }
        //@codeCoverageIgnoreEnd

        $output->writeln(sprintf('Generating private key in <info>%s</info>', $secKeyPath));
        $config = [
            'digest_alg' => 'sha512',
            'private_key_bits' => 4096,
            'private_key_type' => OPENSSL_KEYTYPE_RSA
        ];
        $res = openssl_pkey_new($config);
        openssl_pkey_export($res, $secretKey);

        $publicKey = openssl_pkey_get_details($res);
        $publicKey = $publicKey['key'];

        file_put_contents($secKeyPath, $secretKey, LOCK_EX);

        $output->writeln(sprintf('Generating public key in <info>%s</info>', $pubKeyPath));
        file_put_contents($pubKeyPath, $publicKey, LOCK_EX);

        /*
        $process = new Process([
            'openssl',
            'genrsa',
            '-out',
            $secKeyPath,
            '-aes256',
            '-passout',
            'pass:'.$passphrase,
            '4096'
        ]);
        $process->setTimeout(null);
        $process->run(function ($type, $buffer) use ($output) {
            $output->write(sprintf('<info>%s</info>', $buffer));
        });


        $process = new Process([
            'openssl',
            'rsa',
            '-pubout',
            '-in',
            $secKeyPath,
            '-passin',
            'pass:'.$passphrase,
            '-out', $pubKeyPath
        ]);
        $process->setTimeout(null);
        $process->run(function ($type, $buffer) use ($output) {
            $output->write(sprintf('<info>%s</info>', $buffer));
        });
        */

        $output->writeln(
            sprintf('chmod 0775 <info>%s</info>',$secKeyPath)
        );

        chmod($secKeyPath,0755);

        $output->writeln(
            sprintf('chmod 0775 <info>%s</info>',$pubKeyPath)
        );
        chmod($pubKeyPath,0755);
    }
}
