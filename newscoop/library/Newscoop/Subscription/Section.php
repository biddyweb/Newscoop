<?php
/**
 * @package Newscoop
 * @copyright 2011 Sourcefabric o.p.s.
 * @license http://www.gnu.org/licenses/gpl.txt
 */

namespace Newscoop\Subscription;

/**
 * Subscription Section relation entity
 * @Entity(repositoryClass="Newscoop\Entity\Repository\SubscriptionSectionRepository")
 * @Table(name="SubsSections")
 */
class Section
{
    /**
     * @Id @GeneratedValue
     * @Column(type="integer")
     * @var int
     */
    private $id;

    /**
     * @ManyToOne(targetEntity="Newscoop\Subscription\Subscription", inversedBy="sections")
     * @JoinColumn(name="IdSubscription", referencedColumnName="Id")
     * @var Newscoop\Subscription\Subscription
     */
    private $subscription;

    /**
     * @Column(type="integer", name="SectionNumber")
     * @var int
     */
    private $sectionNumber;

    /**
     * @ManyToOne(targetEntity="Newscoop\Entity\Language")
     * @JoinColumn(name="IdLanguage", referencedColumnName="Id")
     * @var Newscoop\Entity\Language
     */
    private $language;

    /**
     * @Column(type="date", name="StartDate")
     * @var DateTime
     */
    private $startDate;

    /**
     * @Column(type="integer", name="Days")
     * @var int
     */
    private $days;

    /**
     * @Column(type="integer", name="PaidDays")
     * @var int
     */
    private $paidDays;

    /**
     * @Column(name="NoticeSent")
     * @var string
     */
    private $noticeSent;

    /**
     * @param Newscoop\Subscription\Subscription $subscription
     * @param int $sectionNumber
     */
    public function __construct(Subscription $subscription, $sectionNumber)
    {
        $this->subscription = $subscription;
        $this->subscription->addSection($this);

        $this->sectionNumber = (int) $sectionNumber;
        $this->noticeSent = 'N';
        $this->paidDays = 0;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get section number
     *
     * @return string
     */
    public function getSectionNumber()
    {
        return $this->sectionNumber;
    }

    /**
     * Set language
     *
     * @param Newscoop\Entity\Language $language
     * @return void
     */
    public function setLanguage(\Newscoop\Entity\Language $language)
    {
        $this->language = $language;
    }

    /**
     * Get language id
     *
     * @return int
     */
    public function getLanguageId()
    {
		try {
			return $this->language ? $this->language->getId() : 0;
		} catch (\Doctrine\ORM\EntityNotFoundException $exc) {
			return 0;
		}
    }

    /**
     * Get language name
     *
     * @return string
     */
    public function getLanguageName()
    {
		try {
			return $this->language ? $this->language->getName() : '';
		} catch (\Doctrine\ORM\EntityNotFoundException $exc) {
			return '';
		}
    }

    /**
     * Get language
     *
     * @return Newscoop\Entity\Language
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Test if has language set
     *
     * @return bool
     */
    public function hasLanguage()
    {
        return $this->language !== null;
    }

    /**
     * Set start date
     *
     * @param DateTime $date
     * @return Newscoop\Entity\SubscriptionSection
     */
    public function setStartDate(\DateTime $date)
    {
        $this->startDate = $date;
    }

    /**
     * Get start date
     *
     * @return DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set days
     *
     * @param int $days
     * @return Newscoop\Entity\SubscriptionSection
     */
    public function setDays($days)
    {
        $this->days = abs($days);
        return $this;
    }

    /**
     * Get days
     *
     * @return int
     */
    public function getDays()
    {
        return $this->days;
    }

    /**
     * Set paid days
     *
     * @param int $paidDays
     * @return Newscoop\Entity\SubscriptionSection
     */
    public function setPaidDays($paidDays)
    {
        $this->paidDays = abs($paidDays);
    }

    /**
     * Get paid days
     *
     * @return int
     */
    public function getPaidDays()
    {
        return $this->paidDays;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        if ($this->subscription->getPublication() === null) {
            return '';
        }

        foreach ($this->subscription->getPublication()->getIssues() as $issue) {
            if ($this->hasLanguage() && $issue->getLanguage() !== $this->language) {
                continue;
            }

            foreach ($issue->getSections() as $section) {
                if ($section->getNumber() == $this->sectionNumber) {
                    return $section->getName();
                }
            }
        }

        return '';
    }

    /**
     * Get subscription
     *
     * @return Newscoop\Subscription\Subscription
     */
    public function getSubscription()
    {
        return $this->subscription;
    }
}
