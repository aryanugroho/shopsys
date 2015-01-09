<?php

namespace SS6\ShopBundle\Model\Payment;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Prezent\Doctrine\Translatable\Annotation as Prezent;
use SS6\ShopBundle\Model\Grid\Ordering\OrderableEntityInterface;
use SS6\ShopBundle\Model\Localization\AbstractTranslatableEntity;
use SS6\ShopBundle\Model\Payment\PaymentData;
use SS6\ShopBundle\Model\Pricing\Vat\Vat;
use SS6\ShopBundle\Model\Transport\Transport;

/**
 * @ORM\Table(name="payments")
 * @ORM\Entity
 */
class Payment extends AbstractTranslatableEntity implements OrderableEntityInterface {

	/**
	 * @var integer
	 *
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 */
	protected $id;

	/**
	 * @var \SS6\ShopBundle\Model\Payment\PaymentTranslation[]
	 *
	 * @Prezent\Translations(targetEntity="SS6\ShopBundle\Model\Payment\PaymentTranslation")
	 */
	protected $translations;

	/**
	 * @var string
	 *
	 * @ORM\Column(type="decimal", precision=20, scale=6)
	 */
	private $price;

	/**
	 * @var \SS6\ShopBundle\Model\Pricing\Vat\Vat
	 *
	 * @ORM\ManyToOne(targetEntity="SS6\ShopBundle\Model\Pricing\Vat\Vat")
	 */
	private $vat;

	/**
	 * @var Collection
	 *
	 * @ORM\ManyToMany(targetEntity="SS6\ShopBundle\Model\Transport\Transport")
	 * @ORM\JoinTable(name="payments_transports")
	 */
	private $transports;

	/**
	 * @var integer
	 *
	 * @ORM\Column(type="boolean")
	 */
	private $hidden;

	/**
	 * @var integer
	 *
	 * @ORM\Column(type="boolean")
	 */
	private $deleted;

	/**
	 * @var int|null
	 *
	 * @ORM\Column(type="integer", nullable=true)
	 */
	private $position;

	/**
	 * @param \SS6\ShopBundle\Model\Payment\PaymentData $paymentData
	 */
	public function __construct(PaymentData $paymentData) {
		$this->translations = new ArrayCollection();
		$this->price = $paymentData->price;
		$this->vat = $paymentData->vat;
		$this->transports = new ArrayCollection();
		$this->hidden = $paymentData->hidden;
		$this->deleted = false;
		$this->setTranslations($paymentData);
	}

	/**
	 * @param \SS6\ShopBundle\Model\Transport\Transport $transport
	 */
	public function addTransport(Transport $transport) {
		if (!$this->transports->contains($transport)) {
			$this->transports->add($transport);
		}
	}

	/**
	 * @param array $transports
	 */
	public function setTransports(array $transports) {
		$this->transports->clear();
		foreach ($transports as $transport) {
			/* @var $transport \SS6\ShopBundle\Model\Transport\Transport */
			$this->addTransport($transport);
		}
	}

	/**
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getTransports() {
		return $this->transports;
	}

	/**
	 * @param \SS6\ShopBundle\Model\Payment\PaymentData $paymentData
	 */
	private function setTranslations(PaymentData $paymentData) {
		foreach ($paymentData->name as $locale => $name) {
			$this->translation($locale)->setName($name);
		}
		foreach ($paymentData->description as $locale => $description) {
			$this->translation($locale)->setDescription($description);
		}
	}

	/**
	 * @param \SS6\ShopBundle\Model\Payment\PaymentData $paymentData
	 */
	public function edit(PaymentData $paymentData) {
		$this->price = $paymentData->price;
		$this->vat = $paymentData->vat;
		$this->hidden = $paymentData->hidden;
		$this->setTranslations($paymentData);
	}

	/**
	 * @param \SS6\ShopBundle\Model\Pricing\Vat\Vat $vat
	 */
	public function changeVat(Vat $vat) {
		$this->vat = $vat;
	}

	/**
	 * @param string $price
	 */
	public function setPrice($price) {
		$this->price = $price;
	}

	/**
	 * @return integer
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param string|null $locale
	 * @return string
	 */
	public function getName($locale = null) {
		return $this->translation($locale)->getName();
	}

	/**
	 * @return string
	 */
	public function getPrice() {
		return $this->price;
	}

	/**
	 * @return \SS6\ShopBundle\Model\Pricing\Vat\Vat
	 */
	public function getVat() {
		return $this->vat;
	}

	/**
	 * @param string|null $locale
	 * @return string|null
	 */
	public function getDescription($locale = null) {
		return $this->translation($locale)->getDescription();
	}

	/**
	 * @return boolean
	 */
	public function isHidden() {
		return $this->hidden;
	}

	/**
	 * @return boolean
	 */
	public function isDeleted() {
		return $this->deleted;
	}

	/**
	 * @param boolean $deleted
	 */
	public function markAsDeleted() {
		$this->deleted = true;
		$this->transports->clear();
	}

	/**
	 * @return int|null
	 */
	public function getPosition() {
		return $this->position;
	}

	/**
	 * @param int $position
	 */
	public function setPosition($position) {
		$this->position = $position;
	}

	/**
	 * @return \SS6\ShopBundle\Model\Payment\PaymentTranslation
	 */
	protected function createTranslation() {
		return new PaymentTranslation();
	}

}
