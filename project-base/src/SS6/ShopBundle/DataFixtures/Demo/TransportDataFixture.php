<?php

namespace SS6\ShopBundle\DataFixtures\Demo;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use SS6\ShopBundle\Component\DataFixture\AbstractReferenceFixture;
use SS6\ShopBundle\DataFixtures\Base\VatDataFixture;
use SS6\ShopBundle\Model\Transport\TransportData;

class TransportDataFixture extends AbstractReferenceFixture implements DependentFixtureInterface {

	/**
	 * @param \Doctrine\Common\Persistence\ObjectManager $manager
	 */
	public function load(ObjectManager $manager) {
		$transportData = new TransportData();
		$transportData->name = array(
			'cs' => 'Česká pošta - balík do ruky',
			'en' => 'Czech post',
		);
		$transportData->price = 99.95;
		$transportData->description = array(
			'cs' => 'Pouze na vlastní nebezpečí',
			'en' => 'Only if you are crazy',
		);
		$transportData->vat = $this->getReference(VatDataFixture::VAT_HIGH);
		$transportData->domains = array(1, 2);
		$transportData->hidden = false;
		$this->createTransport('transport_cp', $transportData);

		$transportData->name = array(
			'cs' => 'PPL',
			'en' => 'PPL',
		);
		$transportData->price = 199.95;
		$transportData->description = array(
			'cs' => null,
			'en' => null,
		);
		$this->createTransport('transport_ppl', $transportData);

		$transportData->name = array(
			'cs' => 'Osobní převzetí',
			'en' => 'Personal takeover',
		);
		$transportData->price = 0;
		$transportData->description = array(
			'cs' => 'Uvítá Vás milý personál!',
			'en' => 'You will be welcomed friendly staff!',
		);
		$transportData->vat = $this->getReference(VatDataFixture::VAT_ZERO);
		$this->createTransport('transport_personal', $transportData);
	}

	/**
	 * @param \Doctrine\Common\Persistence\ObjectManager $manager
	 * @param string $referenceName
	 * @param \SS6\ShopBundle\Model\Transport\TransportData $transportData
	 */
	private function createTransport($referenceName, TransportData $transportData) {
		$transportEditFacade = $this->get('ss6.shop.transport.transport_edit_facade');
		/* @var $transportEditFacade \SS6\ShopBundle\Model\Transport\TransportEditFacade */

		$transport = $transportEditFacade->create($transportData);
		$this->addReference($referenceName, $transport);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getDependencies() {
		return array(
			VatDataFixture::class,
		);
	}

}
