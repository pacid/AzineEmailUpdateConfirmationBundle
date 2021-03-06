<?php

namespace Azine\EmailUpdateConfirmationBundle\Tests\DependencyInjection;

use Azine\EmailUpdateConfirmationBundle\DependencyInjection\AzineEmailUpdateConfirmationExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class AzineEmailUpdateConfirmationExtensionTest extends \PHPUnit\Framework\TestCase
{
    public function testDisableEmailUpdateConfirmation()
    {
        $configuration = new ContainerBuilder();
        $loader = new AzineEmailUpdateConfirmationExtension();
        $config = array();
        $config['enabled'] = false;
        $config['from_email'] = 'test@example.com';
        $loader->load(array($config), $configuration);

        $this->assertFalse($configuration->hasDefinition('email_update_confirmation'));
        $this->assertFalse($configuration->hasDefinition('azine.email_update.mailer'));
        $this->assertFalse($configuration->hasDefinition('email_update_listener'));
        $this->assertFalse($configuration->hasDefinition('email_update_flash_subscriber'));
        $this->assertFalse($configuration->hasParameter('azine_email_update_confirmation.template'));
        $this->assertFalse($configuration->hasParameter('azine_email_update_confirmation.cypher_method'));
        $this->assertFalse($configuration->hasParameter('azine_email_update_confirmation.redirect_route'));
    }

    public function testEnableEmailUpdateConfirmation()
    {
        $configuration = new ContainerBuilder();
        $loader = new AzineEmailUpdateConfirmationExtension();
        $config = array();
        $config['enabled'] = true;
        $config['from_email'] = 'test@example.com';
        $loader->load(array($config), $configuration);

        $this->assertTrue($configuration->hasDefinition('email_update_confirmation'));
        $this->assertTrue($configuration->hasDefinition('azine.email_update.mailer'));
        $this->assertTrue($configuration->hasDefinition('email_update_listener'));
        $this->assertTrue($configuration->hasDefinition('email_update_flash_subscriber'));
        $this->assertTrue($configuration->hasParameter('azine_email_update_confirmation.template'));
        $this->assertTrue($configuration->hasParameter('azine_email_update_confirmation.cypher_method'));
        $this->assertTrue($configuration->hasParameter('azine_email_update_confirmation.redirect_route'));
    }

    public function testEnableEmailUpdateConfirmationByDefault()
    {
        $configuration = new ContainerBuilder();
        $loader = new AzineEmailUpdateConfirmationExtension();
        $config = array();
        $config['from_email'] = 'test@example.com';
        $loader->load(array($config), $configuration);
        $this->assertTrue($configuration->hasDefinition('email_update_confirmation'));
        $this->assertTrue($configuration->hasParameter('azine_email_update_confirmation.template'));
        $this->assertSame($config['from_email'], $configuration->getParameter('azine_email_update_confirmation.from_email'));
    }

    /**
     * @expectedException \Exception
     */
    public function testNotSetUpFromEmailParameter()
    {
        $configuration = new ContainerBuilder();
        $loader = new AzineEmailUpdateConfirmationExtension();
        $loader->load(array(), $configuration);
    }

    public function testFOSUserBundleFromEmailParameter()
    {
        $configuration = new ContainerBuilder();
        $fosFromEmail = 'fosuserbundle.from.email@exmple.com';
        $fosFromName = 'From Email Name';
        $configuration->setParameter('fos_user.resetting.email.from_email', array($fosFromEmail => $fosFromName));
        $loader = new AzineEmailUpdateConfirmationExtension();
        $loader->load(array(), $configuration);
        $this->assertSame($fosFromEmail, $configuration->getParameter('azine_email_update_confirmation.from_email'));
    }
}
