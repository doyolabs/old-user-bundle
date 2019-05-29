<?php

namespace Doyo\UserBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class GenerateJWTKeyCommand extends Command
{
    private $pubKeyPath;

    private $secKeyPath;

    private $passphrase;
    private $validDigest;

    private $validKeyType;

    public function __construct(
        $secKeyPath, $pubKeyPath, $passphrase
    ) {
        $this->secKeyPath       = $secKeyPath;
        $this->pubKeyPath       = $pubKeyPath;
        $this->passphrase       = $passphrase;
        $this->validDigest      = array();
        $this->validKeyType     = array();

        parent::__construct('doyo:generate:jwt-keys');
    }

    protected function configure()
    {
        $this->setDescription('Generate JWT public and secret keys');

        $this->addOption(
            'digest',
            '-d',
            InputOption::VALUE_OPTIONAL,
            'Digest method or signature hash',
            'sha512'
        );

        $this->addOption(
            'key-bits',
            '-b',
            InputOption::VALUE_OPTIONAL,
            'Specifies how many bits should be used to generate a private key',
            4096)
        ;

        $this->addOption(
            'key-type',
            '-t',
            InputOption::VALUE_OPTIONAL,
            'Specifies the type of private key to create.',
            'rsa'
        );

        $secretKey = $this->secKeyPath;
        $publicKey = $this->pubKeyPath;

        $usage = <<<EOC
<info>
You will need to enable openssl extension to use this command.

This command will generate jwt <comment>secret</comment> and <comment>public</comment> key in:
<fg=green;options=bold>secret key: </><comment>$secretKey</comment>
<fg=green;options=bold>public key: </><comment>$publicKey</comment>
</info>
EOC;

        if(extension_loaded('openssl')){
            $this->validKeyType = [
                'rsa' => OPENSSL_KEYTYPE_RSA,
                'dsa' => OPENSSL_KEYTYPE_DSA,
                'dh' => OPENSSL_KEYTYPE_DH,
                'ec' => OPENSSL_KEYTYPE_EC,
            ];
            $this->validDigest = openssl_get_md_methods(true);
            $digest  = implode(', ',$this->validDigest);
            $keyType = implode(', ',array_keys($this->validKeyType));

            $usage .= <<<EOC
<info>

<fg=green;options=underscore>A valid value for option is:</> 
    <fg=green;options=bold>[-d|--digest]</>: <comment>$digest</comment>

    <fg=green;options=bold>[-t|--key-type]</>: <comment>$keyType</comment>
</info>
EOC;
        }
        $this->setHelp($usage);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $digest     = $input->getOption('digest');
        $keyBits    = $input->getOption('key-bits');
        $keyType    = $input->getOption('key-type');

        if(!in_array($digest,$this->validDigest,true)){
            throw new InvalidArgumentException(sprintf(
                'Invalid [-d|--digest] value %s',
                $digest
            ));
        }

        if(!isset($this->validKeyType[$keyType])){
            throw new InvalidArgumentException(sprintf(
                'Invalid [-t|--key-type] value %s',
                $keyType
            ));
        }

        $keyType    = $this->validKeyType[$keyType];
        $pubKeyPath = $this->pubKeyPath;
        $secKeyPath = $this->secKeyPath;

        if (!is_dir($dir = \dirname($pubKeyPath))) {
            $output->writeln('Creating directories in '.$pubKeyPath);
            mkdir($dir, 0777, true);
        }

        $config = [
            'digest_alg' => $digest,
            'private_key_bits' => $keyBits,
            'private_key_type' => $keyType
        ];

        // generate private key
        $output->writeln(sprintf('Generating private key in <info>%s</info>', $secKeyPath));
        $this->generateSecretKey($config);

        // generate public key
        $output->writeln(sprintf('Generating public key in <info>%s</info>', $pubKeyPath));
        $this->generatePublicKey();

        // chmod 0775 secretKey and publicKey
        $output->writeln(
            sprintf('chmod 0775 <info>%s</info>',$secKeyPath)
        );
        chmod($secKeyPath,0755);
        $output->writeln(
            sprintf('chmod 0775 <info>%s</info>',$pubKeyPath)
        );
        chmod($pubKeyPath,0755);
    }

    final private function generateSecretKey($config)
    {
        $res = openssl_pkey_new($config);
        openssl_pkey_export_to_file($res,$this->secKeyPath,$this->passphrase);
    }

    final private function generatePublicKey()
    {
        $secretKey = file_get_contents($this->secKeyPath);
        $key = openssl_pkey_get_private($secretKey, $this->passphrase);
        $details = openssl_pkey_get_details($key);
        file_put_contents($this->pubKeyPath, $details['key']);
    }
}
